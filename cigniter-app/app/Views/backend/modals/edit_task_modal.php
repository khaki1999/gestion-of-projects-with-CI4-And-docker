<!-- Modal for editing tasks -->
<div class="modal fade" id="editTaskModal" tabindex="-1" role="dialog" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalLabel">Modifier la Tâche</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Loading spinner -->
                <div id="loadingSpinner" class="text-center" style="display: none;">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                </div>

                <!-- Edit task form -->
                <form id="update-task-form">
                    <input type="hidden" id="task-id" name="task_id">
                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" id="name" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="project_id">Projet</label>
                        <select id="project_id" name="project_id" class="form-control">
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="user_id">Utilisateur</label>
                        <select id="user_id" name="user_id" class="form-control">
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" id="updateTaskButton">
                        <span id="buttonText">Mettre à Jour</span>
                        <span id="loadingSpinnerButton" class="spinner-border spinner-border-sm ml-2" style="display: none;" role="status">
                            <span class="sr-only">Chargement...</span>
                        </span>
                    </button>

                </form>
            </div>
        </div>
    </div>
</div>