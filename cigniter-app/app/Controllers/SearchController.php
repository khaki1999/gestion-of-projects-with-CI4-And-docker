<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use CodeIgniter\HTTP\ResponseInterface;

class SearchController extends BaseController
{
    protected $helpers = ['url', 'form', 'CIMail','CIFunctions '];
    public function results()
    {

        $query = $this->request->getGet('query');

        if (!$query) {
            return view('backend/layout/search_results', ['error' => 'Veuillez entrer un terme de recherche.']);
        }

        $userModel = new User();
        $taskModel = new Task();
        $projectModel = new Project();

        $users = $userModel->like('username', $query)->findAll();
        $tasks = $taskModel->like('name', $query)->findAll();
        $projects = $projectModel->like('name', $query)->findAll();

        $errorMessage = '';
        if (empty($users) && empty($tasks) && empty($projects)) {
            $errorMessage = 'Aucun résultat pour "' . esc($query) . '"';
        }

        return view('backend/layout/search_results', [
            'users' => $users,
            'tasks' => $tasks,
            'projects' => $projects,
            'error' => $errorMessage
        ]);
    }
}
