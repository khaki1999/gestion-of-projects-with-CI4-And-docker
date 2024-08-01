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
    protected $helpers = ['url', 'form', 'CIMail', 'CIFunctions'];
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
    public function task()
    {

        $data = [
            "pageTitle" => "task",
        ];
        return view('backend/pages/task', $data);
    }

    public function someMethod()
    {
        $data = $this->data;
        return view('backend/layout/inc/header', $data);
    }

    public function profile()
    {
        $user = CIAuth::user(); // Récupère les informations de l'utilisateur connecté

        if (!$user) {
            return redirect()->route('admin.login.form')->with('fail', 'Vous devez être connecté pour accéder à cette page.');
        }

        $data = [
            'pageTitle' => 'Profile',
            'user' => $user
        ];

        return view('backend/pages/profile', $data);
    }

    public function updatePersonalDetail()
    {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $user_id = CIAuth::id();

        if ($request->isAJAX()) {
            // Validation des données
            $validation->setRules([
                'name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Le nom est obligatoire.'
                    ]
                ],
                'first_name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Le prénom est obligatoire.'
                    ]
                ],
                'username' => [
                    'rules' => 'required|min_length[4]|is_unique[users.username,id,' . $user_id . ']',
                    'errors' => [
                        'required' => 'Le nom d’utilisateur est requis.',
                        'min_length' => 'Le nom d’utilisateur doit comporter au moins 4 caractères.',
                        'is_unique' => 'Le nom d’utilisateur est déjà utilisé.'
                    ]
                ],
                'datenais' => [
                    'rules' => 'required|valid_date_of_birth',
                    'errors' => [
                        'required' => 'Veuillez entrer une date de naissance.',
                        'valid_date_of_birth' => 'La date de naissance doit être valide et ne peut pas être dans le futur.'
                    ]
                ],
            ]);

            if ($validation->run() == FALSE) {
                $errors = $validation->getErrors();
                return $this->response->setJSON(['status' => 0, 'error' => $errors]);
            } else {
                $user = new User();
                $update = $user->where('id', $user_id)
                    ->set([
                        'name' => $request->getVar('name'),
                        'first_name' => $request->getVar('first_name'),
                        'username' => $request->getVar('username'),
                        'datenais' => $request->getVar('datenais'),
                    ])->update();
                if ($update) {
                    $user_info = $user->find($user_id);
                    return $this->response->setJSON(['status' => 1, 'user_info' => $user_info, 'msg' => 'Vos informations personnelles ont été mises à jour avec succès.']);
                } else {
                    return $this->response->setJSON(['status' => 0, 'msg' => 'Une erreur est survenue lors de la mise à jour.']);
                }
            }
        }
    }




    // public function updatePersonalDetail()
    // {
    //     $request = \Config\Services::request();
    //     $validation = \Config\Services::validation();
    //     $user_id = CIAuth::id(); // Assurez-vous que CIAuth::id() est défini et fonctionne

    //     if ($request->isAJAX()) {
    //         // Définir les règles de validation
    //         $validation->setRules([
    //             'name' => [
    //                 'rules' => 'required',
    //                 'errors' => [
    //                     'required' => 'Nom obligatoire'
    //                 ]
    //             ],
    //             'first_name' => [
    //                 'rules' => 'required',
    //                 'errors' => [
    //                     'required' => 'Prénom obligatoire'
    //                 ]
    //             ],
    //             'username' => [
    //                 'rules' => 'required|min_length[4]|is_unique[users.username,id,' . $user_id . ']',
    //                 'errors' => [
    //                     'required' => 'Nom d utilisateur requis',
    //                     'min_length' => 'Un nom d utilisateur comporte 4 caractères minimum',
    //                     'is_unique' => 'Nom d utilisateur déjà utilisé'
    //                 ]
    //             ],
    //             'datenais' => [
    //                 'rules' => 'required|valid_date_of_birth',
    //                 'errors' => [
    //                     'required' => 'Entrez les informations relatives à la date de naissance',
    //                     'valid_date_of_birth' => 'La date de naissance doit être valide et ne peut pas être dans le futur.'
    //                 ]
    //             ]
    //         ]);

    //         if (!$validation->run($request->getPost())) {
    //             $errors = $validation->getErrors();
    //             return $this->response->setJSON(['status' => 0, 'error' => $errors]);
    //         } else {
    //             // Traitement des données valides
    //             // Par exemple, mettre à jour les informations de l'utilisateur
    //             $userModel = new User();
    //             $userModel->update($user_id, [
    //                 'name' => $request->getPost('name'),
    //                 'first_name' => $request->getPost('first_name'),
    //                 'username' => $request->getPost('username'),
    //                 'datenais' => $request->getPost('datenais')
    //             ]);

    //             return $this->response->setJSON([
    //                 'status' => 1,
    //                 'msg' => 'Les détails ont été mis à jour avec succès.',
    //                 'user_info' => [
    //                     'name' => $request->getPost('name'),
    //                     'email' => $request->getPost('email') // Assurez-vous que cette clé existe
    //                 ]
    //             ]);
    //         }
    //     }
    // }
}
