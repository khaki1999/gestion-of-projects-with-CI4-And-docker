<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\UsersTask;
use App\Libraries\CIAuth;



class TaskController extends BaseController
{
    protected $helpers = ['url', 'form', 'CIMail', 'CIFunctions '];
    protected $modelName = 'App\Models\Task';
    protected $format    = 'json';

    protected $model;
    protected  $userTaskModel;
    protected $subtaskModel;
    protected $taskModel;



    public function __construct()
    {
        $this->model = new Task();
        $this->userTaskModel = new UsersTask();
        $this->taskModel = new Task();
    }


    public function index()
    {
        $filter = $this->request->getGet('filter');
        $projectId = $this->request->getGet('project_id');
        $taskType = $this->request->getGet('task_type');
        $user = CIAuth::user();


        $query = $this->model->asArray();

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        if ($taskType === 'parent') {
            $query->where('parent_id', null);
        } elseif ($taskType === 'subtask') {
            $query->where('parent_id IS NOT NULL', null, false);
        }

        $query->orderBy('created_at', 'DESC');

        $tasks = $query->findAll();

        $projectModel = new Project();
        $userModel = new User();
        $projects = $projectModel->findAll();

        foreach ($tasks as &$task) {
            $project = $projectModel->find($task['project_id']);
            $task['project_name'] = $project ? $project['name'] : 'Projet inconnu';

            $userTask = $this->userTaskModel->where('task_id', $task['id'])->first();
            $user = $userTask ? $userModel->find($userTask['user_id']) : null;
            $task['user_name'] = $user ? $user['username'] : 'Non attribué';
        }

        return view('backend/pages/tasks', [
            'tasks' => $tasks,
            'projects' => $projects,
            'filter' => $filter,
            'projectId' => $projectId,
            'taskType' => $taskType,
            'user' => $user
        ]);
    }



    public function create()
    {
        $usersModel = new User();
        $projectsModel = new Project();

        $data = [
            'users' => $usersModel->findAll(),
            'projects' => $projectsModel->findAll(),
        ];

        return $this->response->setJSON($data);
    }


    public function store()
    {
        $taskModel = new Task();
        $usersTaskModel = new UsersTask();



        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'project_id' => $this->request->getPost('project_id'),
            'user_id' => $this->request->getPost('user_id'),
        ];


        $taskId = $taskModel->insert($data);
        $session = session();

        if ($taskId) {
            $usersTaskData = [
                'user_id' => $data['user_id'],
                'task_id' => $taskId
            ];
            $usersTaskModel->insert($usersTaskData);
            $session->setFlashdata('alert', ['type' => 'success', 'message' => 'Tâche ajoutée avec succès']);
        } else {
            $session->setFlashdata('alert', ['type' => 'error', 'message' => 'Erreur lors de l\'ajout de la tâche']);
        }

        return redirect()->route('tasks.list');
    }




    public function getAllTask()
    {
        $tasks = $this->model->getAllTasks();
        return $this->response->setJSON($tasks);
    }


    public function edit($id)
    {
        $task = $this->taskModel->find($id);
        $projectModel = new Project();
        $userModel = new User();
        $usersTask = new UsersTask();
        $userIds = $usersTask->getUserIdsByTaskId($id);
        $projects = $projectModel->findAll();
        $users = $userModel->findAll();
        $session = session();

        $alert = $session->getFlashdata('alert');


        return $this->response->setJSON([
            'task' => $task,
            'projects' => $projects,
            'users' => $users,
            'userIds' => $userIds,
            'alert' => $alert
        ]);
    }




    // public function update($id)
    // {
    //     $data = $this->request->getPost();
    //     $session = session();

    //     if (empty($data)) {
    //         $session->setFlashdata('alert', ['type' => 'error', 'message' => 'Aucune donnée fournie pour la mise à jour']);
    //         return redirect()->back();
    //     }

    //     $currentTask = $this->model->find($id);

    //     if ($currentTask) {
    //         if ($this->model->updateTask($id, $data)) {
    //             $usersTaskModel = new UsersTask();

    //             $currentUserTask = $usersTaskModel->where('task_id', $id)->first();

    //             // Si l'utilisateur a changé ou n'est pas encore associé
    //             if ($currentUserTask) {
    //                 if ($currentUserTask['user_id'] != $data['user_id']) {
    //                     // Mettre à jour l'utilisateur associé à la tâche
    //                     $usersTaskModel->where('task_id', $id)->set(['user_id' => $data['user_id']])->update();
    //                 }
    //             } else {
    //                 // Ajouter une nouvelle association si elle n'existe pas
    //                 $usersTaskModel->insert([
    //                     'task_id' => $id,
    //                     'user_id' => $data['user_id']
    //                 ]);
    //             }

    //             $session->setFlashdata('alert', ['type' => 'success', 'message' => 'Tâche mise à jour avec succès']);
    //         } else {
    //             $session->setFlashdata('alert', ['type' => 'error', 'message' => 'Échec de la mise à jour de la tâche']);
    //         }
    //     } else {
    //         $session->setFlashdata('alert', ['type' => 'error', 'message' => 'Tâche non trouvée']);
    //     }

    //     return redirect()->route('tasks.list');
    // }


    //upader
    public function update($id)
    {
        $data = $this->request->getPost();
        //$session = session();

        if (empty($data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Aucune donnée fournie pour la mise à jour'
            ]);
        }

        $currentTask = $this->model->find($id);

        if ($currentTask) {
            if ($this->model->updateTask($id, $data)) {
                $usersTaskModel = new UsersTask();
                $currentUserTask = $usersTaskModel->where('task_id', $id)->first();

                if ($currentUserTask) {
                    if ($currentUserTask['user_id'] != $data['user_id']) {
                        $usersTaskModel->where('task_id', $id)->set(['user_id' => $data['user_id']])->update();
                    }
                } else {
                    $usersTaskModel->insert([
                        'task_id' => $id,
                        'user_id' => $data['user_id']
                    ]);
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tâche mise à jour avec succès'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Échec de la mise à jour de la tâche'
                ]);
            }
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tâche non trouvée'
            ]);
        }
    }

    //delete
    public function delete($id)
    {
        $usersTaskModel = new UsersTask();
        $usersTaskModel->where('task_id', $id)->delete();
        $session = session();

        if ($this->taskModel->deleteTasks($id)) {
            $session->setFlashdata('alert', ['type' => 'success', 'message' => 'Tâche supprimée avec succès']);
        } else {
            $session->setFlashdata('alert', ['type' => 'error', 'message' => 'Échec de la suppression de la tâche']);
        }

        return redirect()->route('tasks.list');
    }


    public function getTaskById($id)
    {
        $task = $this->model->find($id);
        $projectModel = new Project();
        $userModel = new User();

        $project = $projectModel->find($task['project_id']);
        $task['project_name'] = $project ? $project['name'] : 'Projet inconnu';

        $userTask = $this->userTaskModel->where('task_id', $task['id'])->first();
        $user = $userTask ? $userModel->find($userTask['user_id']) : null;
        $task['user_name'] = $user ? $user['username'] : 'Non attribué';

        return view('backend/pages/view_task', ['task' => $task]);
    }


    public function getTasksByProject($project_id)
    {
        $tasks = $this->model->where('project_id', $project_id)->orderBy('created_at', 'DESC')->findAll();

        $projectModel = new Project();
        $userModel = new User();

        foreach ($tasks as &$task) {
            $project = $projectModel->find($task['project_id']);
            $task['project_name'] = $project ? $project['name'] : 'Projet inconnu';

            $userTask = $this->userTaskModel->where('task_id', $task['id'])->first();
            $user = $userTask ? $userModel->find($userTask['user_id']) : null;
            $task['user_name'] = $user ? $user['username'] : 'Non attribué';
        }

        return view('backend/pages/tasks_by_project', ['tasks' => $tasks]);
    }


    public function assignUser($taskId, $userId)
    {
        $userTaskModel = new UsersTask();
        $data = ['task_id' => $taskId, 'user_id' => $userId];

        if ($userTaskModel->insert($data)) {
            return redirect()->route('tasks.list')->with('success', 'Utilisateur assigné à la tâche avec succès');
        }

        return redirect()->back()->with('error', 'Échec de l\'assignation de l\'utilisateur à la tâche');
    }

    public function listUsers($taskId)
    {
        $userTaskModel = new UsersTask();
        $users = $userTaskModel->where('task_id', $taskId)->findAll();

        return view('backend/pages/task_users', ['users' => $users]);
    }

    public function userTasks($userId)
    {
        $userTaskModel = new UsersTask();
        $tasks = $userTaskModel->where('user_id', $userId)->findAll();

        return view('backend/pages/user_tasks', ['tasks' => $tasks]);
    }


    //suppression groupée
    public function deleteGroup()
    {
        $taskIds = $this->request->getPost('task_ids');
        $session = session();

        if (!empty($taskIds) && is_array($taskIds)) {
            // Supprimer les associations utilisateur-tâche
            $this->userTaskModel->deleteByTaskIds($taskIds);

            // Supprimer les tâches
            if ($this->taskModel->deleteGroup($taskIds)) {
                $session->setFlashdata('alert', ['type' => 'success', 'message' => 'Les Tâches selectionnées ont été  supprimée avec succès']);
            } else {
                $session->setFlashdata('alert', ['type' => 'error', 'message' => 'Échec de la suppression des tâches selectionnée']);
            }
        }

        return redirect()->route('tasks.list');
    }


    //crud lier a subtask



    public function createSubtask($taskId)
    {
        $taskModel = new Task();
        $userModel = new User();


        // Récupérer les informations sur la tâche principale
        $task = $taskModel->find($taskId);

        // Récupérer tous les utilisateurs
        $users = $userModel->findAll();

        return $this->response->setJSON([
            'taskId' => $taskId,
            'users' => $users,
            'project_id' => $task['project_id']

        ]);
    }


    public function storeSubtask()
    {
        $taskModel = new Task();
        $usersTaskModel = new UsersTask();

        // Récupérer les données du formulaire
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'project_id' => $this->request->getPost('project_id'),
            'parent_id' => $this->request->getPost('parent_id')
        ];

        // Assigner l'utilisateur à la sous-tâche
        $userId = $this->request->getPost('user_id');
        if (!empty($userId)) {
            $data['user_id'] = $userId;
        }

        // Insérer la sous-tâche
        $subtaskId = $taskModel->insert($data);

        if ($subtaskId) {
            // Insérer l'association utilisateur-sous-tâche si un utilisateur est sélectionné
            if (!empty($userId)) {
                $usersTaskData = [
                    'user_id' => $userId,
                    'task_id' => $subtaskId
                ];
                $usersTaskModel->insert($usersTaskData);
            }

            // Répondre avec un message de succès
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Sous-tâche créée avec succès',
                'subtaskId' => $subtaskId
            ]);
        } else {
            // Répondre avec un message d'erreur
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la création de la sous-tâche'
            ]);
        }
    }



    public function getSubtasks($taskId)
    {
        $subtasks = $this->taskModel->where('parent_id', $taskId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $userModel = new User();
        $projectModel = new Project();
        $usersTaskModel = new UsersTask();

        foreach ($subtasks as &$subtask) {
            // Récupérer les informations de l'utilisateur assigné
            $userTask = $usersTaskModel->where('task_id', $subtask['id'])->first();
            if ($userTask) {
                $user = $userModel->find($userTask['user_id']);
                $subtask['user_name'] = $user ? $user['username'] : 'Non attribué';
            } else {
                $subtask['user_name'] = 'Non attribué';
            }

            // Récupérer les informations du projet associé
            $project = $projectModel->find($subtask['project_id']);
            $subtask['project_name'] = $project ? $project['name'] : 'Aucun projet';
        }


        $task = $this->taskModel->find($taskId);

        return $this->response->setJSON([
            'subtasks' => $subtasks,
            'task' => $task
        ]);
    }
}
