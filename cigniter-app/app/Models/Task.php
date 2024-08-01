<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\UsersTask;

class Task extends Model
{
    protected $table            = 'tasks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'description', 'project_id', 'parent_id'];


    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';



    public function updateTasks($taskId, $taskData)
    {
        $taskModel = new Task();
        return $taskModel->update($taskId, $taskData);
    }

    public function deleteTasks($taskId)
    {

        return $this->delete($taskId);
    }

    public function deleteGroup(array $taskIds): bool
    {
    
        $taskIds = array_filter($taskIds, 'is_numeric');
        $taskIds = array_map('intval', $taskIds);

        if (empty($taskIds)) {
            return false;
        }

        // Suppression en masse des tâches
        return $this->whereIn('id', $taskIds)->delete();
    }

    public function createTask($data)
    {
        return $this->insert($data);
    }

    public function getTaskById($id)
    {
        return $this->find($id);
    }

    public function getAllTasks()
    {
        return $this->findAll();
    }

    public function updateTask($id, $data)
    {
        return $this->update($id, $data);
    }


    public function getTasksByProject($project_id)
    {
        return $this->where('project_id', $project_id)->findAll();
    }

    public function getProject()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function getUsers()
    {
        $userTaskModel = new UsersTask();
        return $userTaskModel->where('task_id', $this->id)->findAll();
    }

    public function getSubtasks($taskId)
    {
        return $this->where('parent_id', $taskId)->findAll();
    }

    public function getParentTask($taskId)
    {
        $task = $this->find($taskId);
        if ($task && $task['parent_id']) {
            return $this->find($task['parent_id']);
        }
        return null;
    }

    public function getSubtasksByTask($task_id)
    {
        return $this->where('parent_id', $task_id)->findAll();
    }
}
