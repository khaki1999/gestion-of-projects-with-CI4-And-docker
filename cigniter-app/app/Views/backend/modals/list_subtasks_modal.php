<!-- Modal pour afficher les sous-tâches -->
<div class="modal fade" id="subtasksModal" tabindex="-1" role="dialog" aria-labelledby="subtasksModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subtasksModalLabel">Sous-Tâches</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="loadingIndicator" class="text-center mb-3" style="display: none;">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                </div>
                <ul id="subtasksList" class="list-group">
                    <!-- Liste des sous-tâches sera insérée ici -->
                </ul>
                <button id="showAddSubtaskFormBtn" class="btn btn-primary mt-3">Ajouter une sous-tâche</button>
                <div id="addSubtaskFormContainer" style="display: none;">
                    <form id="addSubtaskForm">
                        <input type="hidden" id="parentId" name="parent_id" />
                        <input type="hidden" id="projectId" name="project_id" />
                        <div class="form-group">
                            <label for="subtaskName">Nom de la sous-tâche</label>
                            <input type="text" class="form-control" id="subtaskName" name="name" required />
                        </div>
                        <div class="form-group">
                            <label for="subtaskDescription">Description</label>
                            <textarea class="form-control" id="subtaskDescription" name="description" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="subtaskUser">Utilisateur assigné</label>
                            <select class="form-control" id="subtaskUser" name="user_id" required>
                                <!-- Options des utilisateurs seront insérées ici -->
                            </select>
                        </div>
                        <div id="subtaskLoadingIndicator" class="text-center mb-3" style="display: none;">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Chargement...</span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" id="submitSubtaskBtn">
                            Ajouter Sous-Tâche
                            <span class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true" style="display: none;"></span>
                        </button>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>