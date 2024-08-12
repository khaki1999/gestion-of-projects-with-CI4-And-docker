<!-- Modal pour Éditer un Projet -->
<div class="modal fade" id="editProjectModal" tabindex="-1" role="dialog" aria-labelledby="editProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProjectModalLabel">Éditer le Projet</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editProjectForm" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" id="editProjectId" name="id">
                    <div class="form-group">
                        <label for="editName">Nom du Projet</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="editDescription">Description</label>
                        <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" id="editProjectSubmitButton">
                        Mettre à Jour
                        <span id="loadingSpinnerButton" class="spinner-border spinner-border-sm ml-2" style="display:  none;" role="status">
                            <span class="sr-only">Chargement...</span>
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>