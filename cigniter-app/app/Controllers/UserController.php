<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }


    public function index()
    {
        $filter = $this->request->getGet('filter');

        switch ($filter) {
            case 'with_tasks':
                $users = $this->userModel->getUsersWithTasks();
                break;
            case 'managers':
                $users = $this->userModel->getTaskManagers();
                break;
            default:
                $users = $this->userModel->orderBy('created_at', 'DESC')->findAll();
                break;
        }

        foreach ($users as &$user) {
            $user['assignedTasks'] = $this->userModel->getAssignedTasks($user['id']);
        }

        return view('backend/pages/users', ['users' => $users, 'filter' => $filter]);
    }

    public function create()
    {
        return view('backend/pages/create_user');
    }



    public function store()
    {
        $data = $this->request->getPost();

        // Vérifier que les données existent
        if (empty($data)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Aucune donnée fournie'
            ]);
        }

        // Extraire et valider la date de naissance
        $dateOfBirth = $data['datenais'] ?? null;

        if (!is_string($dateOfBirth) || empty($dateOfBirth)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'La date de naissance est obligatoire et doit être valide'
            ]);
        }

        try {
            $dob = new \DateTime($dateOfBirth);
            $currentDate = new \DateTime();

            if ($dob >= $currentDate) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'La date de naissance doit être antérieure à la date actuelle'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Format de date invalide'
            ]);
        }

        // Ajouter la date de naissance aux données à insérer
        $data['datenais'] = $dateOfBirth;

        // Insérer les données dans la base de données
        $userId = $this->userModel->insert($data);

        // Vérifier si l'insertion a réussi
        if ($userId === false) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Échec de la création de l\'utilisateur'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Utilisateur créé avec succès'
            ]);
        }
    }




    public function edit($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->route('users.list')->with('alert', [
                'type' => 'error',
                'message' => 'Utilisateur non trouvé'
            ]);
        }
        return $this->response->setJSON([
            'user' => $user,
        ]);
    }
    public function update($id)
    {
        $data = $this->request->getPost();

        if (empty($data)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Aucune donnée fournie pour la mise à jour'
            ]);
        }

        if (!$this->userModel->update($id, $data)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Échec de la mise à jour de l\'utilisateur'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Utilisateur mis à jour avec succès'
        ]);
    }

    public function delete($id)
    {
        $this->userModel->delete($id);
        return redirect()->route('users.list')->with('alert', [
            'type' => 'success',
            'message' => 'Utilisateur supprimé avec succès'
        ]);
    }

    public function getUserById($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->response->setJSON(['error' => 'User not found'])->setStatusCode(404);
        }
        return $this->response->setJSON($user)->setStatusCode(200);
    }

    public function getTasks($userId)
    {
        $tasks = $this->userModel->getTasks($userId);
        return $this->response->setJSON($tasks)->setStatusCode(200);
    }

    public function assignTask($userId, $taskId)
    {
        $this->userModel->assignTask($userId, $taskId);
        return $this->response->setJSON(['status' => 'Task assigned to user'])->setStatusCode(200);
    }
}
