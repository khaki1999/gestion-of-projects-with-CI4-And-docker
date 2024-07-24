<?php

namespace App\Models;

use CodeIgniter\Model;

class Project extends Model
{
    protected $table            = 'projects';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['name', 'description'];
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';



    // CRUD Operations
    public function createProject($data)
    {
        return $this->insert($data);
    }

    public function getProjectBYId($id)
    {
        return $this->find($id);
    }

    public function getAllProjects()
    {
        return $this->findAll();
    }

    public function updateProject($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteProject($id)
    {
        return $this->delete($id);
    }

    public function addTask($projectId, $taskData)
    {
        $taskModel = new Task();
        $taskData['project_id'] = $projectId;
        return $taskModel->insert($taskData);
    }

    public function updateTask($taskId, $taskData)
    {
        $taskModel = new Task();
        return $taskModel->update($taskId, $taskData);
    }
    public function getTasks($projectId)
    {
        return $this->db->table('tasks')
            ->where('project_id', $projectId)
            ->get()
            ->getResultArray();
    }

    public function deleteTask($taskId)
    {
        $taskModel = new Task();
        return $taskModel->delete($taskId);
    }
}
