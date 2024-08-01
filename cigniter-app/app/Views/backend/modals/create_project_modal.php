<!-- Modal pour Ajouter un Projet -->
<div class="modal fade" id="createProjectModal" tabindex="-1" role="dialog" aria-labelledby="createProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createProjectModalLabel">Ajouter un Projet</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createProjectForm" action="<?= route_to('projects.store') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="name">Nom du Projet</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Entrez le nom du projet" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Entrez la description du projet"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" id="createProjectSubmitButton">
                        Ajouter
                        <span id="loadingSpinnerButton" class="spinner-border spinner-border-sm ml-2" style="display: none;" role="status">
                            <span class="sr-only">Chargement...</span>
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>