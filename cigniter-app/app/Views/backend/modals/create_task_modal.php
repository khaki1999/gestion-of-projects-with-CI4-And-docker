<!-- Modal pour Ajouter une Tâche -->
<div class="modal fade" id="createTaskModal" tabindex="-1" role="dialog" aria-labelledby="createTaskModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createTaskModalLabel">Ajouter une Tâche</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="createTaskForm" method="post" action="<?= route_to('tasks.store') ?>">
          <div class="mb-3">
            <label for="taskName" class="form-label">Nom De La Tache</label>
            <input type="text" class="form-control" id="taskTitle" name="name" required>
          </div>
          <div class="mb-3">
            <label for="taskDescription" class="form-label">Description</label>
            <textarea class="form-control" id="taskDescription" name="description" required></textarea>
          </div>
          <div class="mb-3">
            <label for="taskProject" class="form-label">Projet</label>
            <select class="form-select" id="taskProject" name="project_id" required>
              <option value="">Chargement...</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="taskUser" class="form-label">Utilisateur</label>
            <select class="form-select" id="taskUser" name="user_id" required>
              <option value="">Chargement...</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Créer la Tâche</button>
        </form>
      </div>
    </div>
  </div>
</div>

