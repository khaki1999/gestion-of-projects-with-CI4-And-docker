<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Project;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\CIAuth;
use App\Models\Task;
use App\Models\User;

class AdminController extends BaseController
{
    public function index()
    {
        // Charger les modèles
        $userModel = new User();
        $projectModel = new Project();
        $taskModel = new Task();


        $totalUsers = $userModel->countAll();
        $totalProjects = $projectModel->countAll();
        $totalTasks = $taskModel->countAll();

        $data = [
            "pageTitle" => "Dashboard",
            "totalUsers" => $totalUsers,
            "totalProjects" => $totalProjects,
            "totalTasks" => $totalTasks,
        ];

        return view('backend/pages/home', $data);
    }

    public function logoutHandler()
    {

        CIAuth::forget();
        return redirect()->route('admin.login.form')->with('fail', 'you are logged out!');
    }

    public function project()
    {

        $data = [
            "pageTitle" => "project",
        ];
        return view('backend/pages/project', $data);
    }

    public function headername()
    {
        $data = [
            'user' => CIAuth::user()
        ];
        return view('backend/layout/inc/header', $data);
    }
}
