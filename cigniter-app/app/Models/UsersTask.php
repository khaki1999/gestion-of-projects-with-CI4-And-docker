<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersTask extends Model
{
    protected $table            = 'users_task';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'task_id'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;



    public function assignTask($userId, $taskId)
    {
        $taskModel = new Task();
        return $taskModel->update($taskId, ['user_id' => $userId]);
    }

    public function updateUsersTask($taskId, $userId)
    {
        $data = ['user_id' => $userId];
        return $this->where('task_id', $taskId)->set($data)->update();
    }
}
