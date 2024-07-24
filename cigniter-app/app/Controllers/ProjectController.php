<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class ProjectController extends BaseController

{

    protected $modelName = 'App\Models\Project';
    protected $format    = 'json';

    protected $model;
    protected $taskModel;

    public function __construct()
    {
        $this->model = new Project();
        $this->taskModel = new Task();
    }


    public function index()
    {
        $projects = $this->model->orderBy('created_at', 'DESC')->findAll();
        return view('backend/pages/projects', ['projects' => $projects]);
    }


    public function create()
    {
        return view('backend/pages/create_project');
    }

    // public function store()
    // {
    //     $data = $this->request->getPost();

    //     if (empty($data)) {
    //         return $this->response->setJSON(['error' => 'Aucune donnée fournie'], 400);
    //     }

    //     if (!$this->model->insert($data)) {
    //         return $this->response->setJSON(['error' => 'Échec de la création du projet'], 500);
    //     }

    //     return redirect()->route('projects.list')->with('success', 'Projet créé avec succès');
    // }

    public function store()
    {
        $data = $this->request->getPost();

        if (empty($data)) {
            $session = session();
            $session->setFlashdata('alert', ['type' => 'error', 'message' => 'Aucune donnée fournie']);
            return redirect()->route('projects.list');
        }

        if (!$this->model->insert($data)) {
            $session = session();
            $session->setFlashdata('alert', ['type' => 'error', 'message' => 'Échec de la création du projet']);
            return redirect()->route('projects.list');
        }

        $session = session();
        $session->setFlashdata('alert', ['type' => 'success', 'message' => 'Projet créé avec succès']);
        return redirect()->route('projects.list');
    }



    public function getAllProjects()
    {
        $projects = $this->model->getAllProjects();
        return view('backend/pages/projects', ['projects' => $projects]);
    }


    public function getProjectBYId($id = null)
    {
        $project = $this->model->getProjectBYId($id);
        if (!$project) {
            return $this->response->setJSON(['error' => 'Project not found'])->setStatusCode(404);
        }
        return $this->response->setJSON($project)->setStatusCode(200);
    }

    // public function update($id)
    // {
    //     $data = $this->request->getPost();

    //     if (empty($data)) {
    //         return redirect()->back()->with('error', 'Aucune donnée fournie pour la mise à jour');
    //     }

    //     if (!$this->model->updateProject($id, $data)) {
    //         return redirect()->back()->with('error', 'Échec de la mise à jour du projet');
    //     }
    //     return redirect()->route('projects.list')->with('success', 'Projet mis à jour avec succès');
    // }

    public function update($id)
    {
        $data = $this->request->getPost();

        if (empty($data)) {
            $session = session();
            $session->setFlashdata('alert', ['type' => 'error', 'message' => 'Aucune donnée fournie pour la mise à jour']);
            return redirect()->back();
        }

        if (!$this->model->updateProject($id, $data)) {
            $session = session();
            $session->setFlashdata('alert', ['type' => 'error', 'message' => 'Échec de la mise à jour du projet']);
            return redirect()->back();
        }

        $session = session();
        $session->setFlashdata('alert', ['type' => 'success', 'message' => 'Projet mis à jour avec succès']);
        return redirect()->route('projects.list');
    }




    // public function delete($id = null)
    // {
    //     $this->model->deleteProject($id);
    //     return redirect()->route('projects.list')->with('success', 'Projet mis à jour avec succès')->setStatusCode(200);
    // }

    public function delete($id = null)
    {
        if ($this->model->delete($id) === false) {
            $session = session();
            $session->setFlashdata('alert', ['type' => 'error', 'message' => 'Échec de la suppression du projet']);
            return redirect()->route('projects.list');
        }

        $session = session();
        $session->setFlashdata('alert', ['type' => 'success', 'message' => 'Projet supprimé avec succès']);
        return redirect()->route('projects.list');
    }


    public function getTasks($projectId)
    {
        $tasks = $this->model->getTasks($projectId);
        return $this->response->setJSON($tasks);
    }

    public function edit($id)
    {
        $project = $this->model->getProjectBYId($id);
        if (!$project) {
            return redirect()->route('projects.list')->with('error', 'Projet non trouvé');
        }
        return view('backend/pages/edit-project', ['project' => $project]);
    }

    public function showTasks($projectId)
    {

        $tasks = $this->taskModel->getTasksByProject($projectId);
        return view('backend/pages/project_tasks', [
            'tasks' => $tasks,
            'project' => $this->model->find($projectId),
        ]);
    }

    
    
}
