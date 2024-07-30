<!-- Modal for editing users -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Modifier un Utilisateur</h5>
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

                <!-- Edit user form -->
                <form id="update-user-form">
                    <input type="hidden" id="user-id" name="id">
                    <div class="form-group">
                        <label for="nam-e">Nom</label>
                        <input type="text" class="form-control" id="nam-e" name="name" required />
                    </div>
                    <div class="form-group">
                        <label for="first-name">Prénom</label>
                        <input type="text" class="form-control" id="first-name" name="first_name" required />
                    </div>
                    <div class="form-group">
                        <label for="user-name">Nom d'utilisateur</label>
                        <input type="text" class="form-control" id="user-name" name="username" required />
                    </div>
                    <div class="form-group">
                        <label for="emai-l">Email</label>
                        <input type="email" class="form-control" id="emai-l" name="email" required />
                    </div>
                    <div class="form-group">
                        <label for="passwor-d">Mot de passe</label>
                        <input type="password" class="form-control" id="passwor-d" name="password" />
                    </div>
                    <div class="form-group">
                        <label for="datenaiss">Date de naissance</label>
                        <input type="date" class="form-control" id="datenaiss" name="datenais" required />
                    </div>
                    <button type="submit" class="btn btn-primary" id="updateUserButton">
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
