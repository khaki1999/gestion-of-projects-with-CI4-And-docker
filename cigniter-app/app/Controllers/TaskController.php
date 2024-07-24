<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\UsersTask;


class TaskController extends BaseController
{
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
        ]);
    }



    public function create()
    {
        $projectModel = new Project();
        $userModel = new User();
        $projects = $projectModel->findAll();
        $users = $userModel->findAll();
        return view('backend/pages/create_task', ['projects' => $projects, 'users' => $users]);
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
        $projects = $projectModel->findAll();
        $users = $userModel->findAll();
        $session = session();
    
        $alert = $session->getFlashdata('alert');
    
        return view('backend/pages/edit_task', [
            'task' => $task,
            'projects' => $projects,
            'users' => $users,
            'alert' => $alert
        ]);
    }
    

    
    // {
    //     $data = $this->request->getPost();

    //     if (empty($data)) {
    //         return $this->response->setJSON(['error' => 'Aucune donnée fournie'], 400);
    //     }

    //     $currentTask = $this->model->find($id);

    //     if (!$this->model->updateTask($id, $data)) {
    //         return $this->response->setJSON(['error' => 'Échec de la mise à jour de la tâche'], 500);
    //     }

    //     // Vérifier si l'utilisateur a changé
    //     if ($currentTask['user_id'] != $data['user_id']) {
    //         // Mettre à jour l'association dans users_task
    //         $usersTaskModel = new UsersTask();
    //         $usersTaskData = ['user_id' => $data['user_id']];
    //         $usersTaskModel->where('task_id', $id)->set($usersTaskData)->update();
    //     }

    //     return redirect()->route('tasks.list')->with('success', 'Tâche mise à jour avec succès');
    // }

    // public function delete($id)
    // {
    //     // Supprimer les associations dans users_task
    //     $usersTaskModel = new UsersTask();
    //     $usersTaskModel->where('task_id', $id)->delete();

    //     // Supprimer la tâche
    //     $this->model->deleteTasks($id);

    //     return redirect()->route('tasks.list')->with('success', 'Tâche supprimée avec succès');
    // }

    public function update($id)
    {
        $data = $this->request->getPost();
        $session = session();

        if (empty($data)) {
            $session->setFlashdata('alert', ['type' => 'error', 'message' => 'Aucune donnée fournie pour la mise à jour']);
            return redirect()->back();
        }

        $currentTask = $this->model->find($id);

        if ($this->model->updateTask($id, $data)) {
            if ($currentTask['user_id'] != $data['user_id']) {
                $usersTaskModel = new UsersTask();
                $usersTaskData = ['user_id' => $data['user_id']];
                $usersTaskModel->where('task_id', $id)->set($usersTaskData)->update();
            }
            $session->setFlashdata('alert', ['type' => 'success', 'message' => 'Tâche mise à jour avec succès']);
        } else {
            $session->setFlashdata('alert', ['type' => 'error', 'message' => 'Échec de la mise à jour de la tâche']);
        }

        return redirect()->route('tasks.list');
    }

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


    //crud lier a subtask



    public function createSubtask($taskId)
    {
        $taskModel = new Task();
        $userModel = new User();

        // Récupérer les informations sur la tâche principale
        $task = $taskModel->find($taskId);

        // Récupérer tous les utilisateurs
        $users = $userModel->findAll();

        // Passer les informations à la vue
        return view('backend/pages/create_subtask', [
            'taskId' => $taskId,
            'users' => $users,
            'project_id' => $task['project_id'] // Passez le project_id de la tâche principale
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
            'parent_id' => $this->request->getPost('task_id')
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

            // Rediriger avec un message de succès
            return redirect()->route('tasks.subtasks', [$data['parent_id']])->with('success', 'Sous-tâche créée avec succès');
        } else {
            // Rediriger avec un message d'erreur
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la création de la sous-tâche');
        }
    }




    public function showSubtasks($taskId)
    {
        $subtasks = $this->taskModel->where('parent_id', $taskId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $userModel = new User();
        $projectModel = new Project();

        foreach ($subtasks as &$subtask) {
            if (isset($subtask['user_id'])) {
                $user = $userModel->find($subtask['user_id']);
                $subtask['user_name'] = $user ? $user['username'] : 'Non attribué';
            } else {
                $subtask['user_name'] = 'Non attribué';
            }

            if (isset($subtask['project_id'])) {
                $project = $projectModel->find($subtask['project_id']);
                $subtask['project_name'] = $project ? $project['name'] : 'Aucun projet';
            } else {
                $subtask['project_name'] = 'Aucun projet';
            }
        }

        $task = $this->taskModel->find($taskId);

        return view('backend/pages/task_subtasks', [
            'subtasks' => $subtasks,
            'task' => $task,
        ]);
    }

    
}
