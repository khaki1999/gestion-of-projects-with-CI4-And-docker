<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'first_name', 'datenais', 'username', 'password', 'email'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

   
    //     public function getTasks($userId)
    //     {
    //         $taskModel = new Task();
    //         return $taskModel->where('user_id', $userId)->findAll();
    //     }

    //     public function assignTask($userId, $taskId)
    //     {
    //         $taskModel = new Task();
    //         return $taskModel->update($taskId, ['user_id' => $userId]);
    //     }

    // CRUD Operations
    public function createUser($data)
    {
        return $this->insert($data);
    }

    public function getUserById($id)

    {
        return $this->find($id);
    }

    public function getAllUsers()
    {
        return $this->findAll();
    }

    public function updateUser($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteUser($id)
    {
        return $this->delete($id);
    }
    public function getTasks($userId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users_task');
        $builder->select('tasks.*');
        $builder->join('tasks', 'tasks.id = users_task.task_id');
        $builder->where('users_task.user_id', $userId);
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function assignTask($userId, $taskId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users_task');
        $data = [
            'user_id' => $userId,
            'task_id' => $taskId
        ];
        return $builder->insert($data);
    }

    // Récupérer les tâches associées à un utilisateur
    public function getAssignedTasks($userId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users_task');
        $builder->select('tasks.*');
        $builder->join('tasks', 'tasks.id = users_task.task_id');
        $builder->where('users_task.user_id', $userId);
        $query = $builder->get();
        return $query->getResultArray();
    }

    // Récupérer les utilisateurs assignés à des tâches
    public function getUsersWithTasks()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('users.*');
        $builder->join('users_task', 'users.id = users_task.user_id');
        $builder->distinct();
        $query = $builder->get();
        return $query->getResultArray();
    }
}
