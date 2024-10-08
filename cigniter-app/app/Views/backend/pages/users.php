<?= $this->extend('backend/layout/pages_layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Utilisateurs</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Utilisateurs
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card card-box">
            <div class="card-header">
                <div class="clearfix">
                    <div class="pull-left">
                        Utilisateurs
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-default btn-sm p-0" role="button" data-toggle="modal" data-target="#addUserModal">
                            <i class="fa fa-plus-circle"> Ajouter</i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <form method="get" action="<?= route_to('users.list') ?>">
                        <div class="form-group">
                            <label for="filter">Filtrer par:</label>
                            <select name="filter" id="filter" class="form-control">
                                <option value="">Tous les utilisateurs</option>
                                <option value="with_tasks" <?= $filter == 'with_tasks' ? 'selected' : '' ?>>Utilisateurs assignés à des tâches</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-secondary"> <i class="fa fa-filter"></i></button>
                    </form>
                </div>

                <form id="deleteGroupForm" method="post" action="<?= route_to('users.delete_group') ?>">
                    <?= csrf_field() ?>
                    <table class="table table-sm table-borderless table-hover table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Selectionner</th>
                                <th scope="col">Nom</th>
                                <th scope="col">Prénom</th>
                                <th scope="col">Nom d'utilisateur</th>
                                <th scope="col">Email</th>
                                <th scope="col">Tâches Assignées</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)) : ?>
                                <?php foreach ($users as $user) : ?>
                                    <tr>
                                        <td><input type="checkbox" name="users_ids[]" value="<?= $user['id'] ?>" class="users-checkbox"></td>
                                        <td><?= $user['name'] ?></td>
                                        <td><?= $user['first_name'] ?></td>
                                        <td><?= $user['username'] ?></td>
                                        <td><?= $user['email'] ?></td>
                                        <td>
                                            <?php if (!empty($user['assignedTasks'])) : ?>
                                                <ul>
                                                    <?php foreach ($user['assignedTasks'] as $task) : ?>
                                                        <li><?= $task['name'] ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else : ?>
                                                <p>Aucune tâche assignée</p>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editUserModal" onclick="openEditUserModal(<?= $user['id'] ?>);return false;"> <i class="fa fa-pencil-alt"></i></button>
                                            <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?= route_to('users.delete', $user['id']) ?>');return false;">
                                                <i class="fa fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="7">Aucun utilisateur trouvé.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <!-- suppression groupée -->
                    <button id="deleteGroupBtn" type="submit" class="btn btn-danger">Supprimer les Utilisateurs sélectionnées</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= view('backend/modals/create_user_modal') ?>
<?= view('backend/modals/edit_user_modal') ?>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    //add user //
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.users-checkbox');
        const deleteGroupBtn = document.getElementById('deleteGroupBtn');
        const userTable = document.querySelector('table tbody');

        // Vérifie s'il y a des utilisateurs et ajuste la visibilité du bouton
        if (userTable.children.length === 1 && userTable.children[0].children.length === 1) {
            deleteGroupBtn.style.display = 'none';
        } else {
            deleteGroupBtn.style.display = 'none'; // Cache initialement en cas de liste vide
        }

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                deleteGroupBtn.style.display = anyChecked ? 'inline-block' : 'none';
            });
        });

        // Gère la visibilité du bouton de suppression si des tâches sont ajoutées dynamiquement
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    const hasUsers = userTable.children.length > 0;
                    deleteGroupBtn.style.display = hasUsers && Array.from(checkboxes).some(cb => cb.checked) ? 'inline-block' : 'none';
                }
            });
        });

        observer.observe(userTable, {
            childList: true
        });

        //add user//
        const addUserForm = document.getElementById('addUserForm');
        const editUserForm = document.getElementById('editUserForm');

        addUserForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Empêche l'envoi par défaut du formulaire

            const formData = new FormData(addUserForm);
            const submitButton = document.getElementById('submitButton');
            const spinner = submitButton.querySelector('.spinner-border');

            submitButton.disabled = true;
            spinner.classList.remove('d-none');

            fetch(addUserForm.action, {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    submitButton.disabled = false;
                    spinner.classList.add('d-none');

                    if (data.status === 'success') {
                        // Vous pouvez rediriger ou fermer la modal ici si nécessaire
                        window.location.reload(); // Recharger la page pour voir la liste des utilisateurs mise à jour
                    } else {
                        // Afficher le message d'erreur dans la modal
                        alert(data.message);
                    }
                })
                .catch(error => {
                    submitButton.disabled = false;
                    spinner.classList.add('d-none');
                    console.error('Erreur:', error);
                    alert('Une erreur s\'est produite. Veuillez réessayer.');
                });
        });

    });

    //ajout d un utilisateur //
    //modifier un utilisateur //
    function openEditUserModal(userId) {
        console.log('ID de l utilisateur :', userId);
        document.getElementById('loadingSpinner').style.display = 'block'; // Show loading spinner

        fetch('/users/edit' + userId)
            .then(response => response.json())
            .then(data => {
                console.log('Données reçues :', data);
                if (data && data.user) { // Check if data and data.user exist
                    const user = data.user; // Correctly assign user
                    showEditUserModal(user.id, user.name, user.first_name, user.datenais, user.username, user.password, user.email);
                } else {
                    console.error('L utilisateur est introuvable');
                }
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des données :', error);
            })
            .finally(() => {
                document.getElementById('loadingSpinner').style.display = 'none'; // Hide loading spinner
            });

    }

    function showEditUserModal(userId, name, first_name, datenais, username, password, email) {
        document.getElementById('user-id').value = userId;
        document.getElementById('nam-e').value = name;
        document.getElementById('first-name').value = first_name;
        document.getElementById('datenaiss').value = datenais;
        document.getElementById('user-name').value = username;
        document.getElementById('passwor-d').value = password;
        document.getElementById('emai-l').value = email;


        $('#editUserModal').modal('show'); // Show the modal
    }

    document.getElementById('update-user-form').addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        // Afficher le spinner sur le bouton
        var button = document.getElementById('updateUserButton');
        var buttonText = document.getElementById('buttonText');
        var loadingSpinnerButton = document.getElementById('loadingSpinnerButton');

        buttonText.style.display = 'none'; // Cacher le texte du bouton
        loadingSpinnerButton.style.display = 'inline-block'; // Afficher le spinner

        fetch('/users/update/' + formData.get('id'), {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // Traiter la réponse en JSON
            .then(result => {
                if (result.status === 'success') {
                    Swal.fire('Succès', result.message, 'success').then(() => {
                        window.location.reload(); // Recharger la page après succès
                    });
                } else {
                    Swal.fire('Erreur', result.message, 'error'); // Afficher le message d'erreur
                }
            })
            .catch(error => {
                console.error('Erreur de requête :', error);
                Swal.fire('Erreur', 'Échec de la requête.', 'error');
            })
            .finally(() => {
                // Masquer le spinner et réafficher le texte du bouton
                buttonText.style.display = 'inline-block';
                loadingSpinnerButton.style.display = 'none';
            });
    });

    //modifier un utilisateur //
    function confirmDelete(url) {
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Vous ne pourrez pas revenir en arrière après la suppression de cet utilisateur.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, supprimer !',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to the URL for deletion
                window.location.href = url;
            }
        });
    }

    // Show SweetAlert based on session flashdata
    <?php if (session()->has('alert')) : ?>
        Swal.fire({
            title: "<?= session('alert')['type'] === 'success' ? 'Succès' : 'Erreur' ?>",
            text: "<?= session('alert')['message'] ?>",
            icon: "<?= session('alert')['type'] ?>",
            confirmButtonText: 'OK'
        });
    <?php endif; ?>

    //delete group//
    document.addEventListener('DOMContentLoaded', function() {
        const deleteGroupBtn = document.getElementById('deleteGroupBtn');
        const deleteGroupForm = document.getElementById('deleteGroupForm');

        deleteGroupBtn.addEventListener('click', function() {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Vous ne pourrez pas récupérer ces tâches une fois supprimées !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer !',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Afficher un indicateur de chargement
                    Swal.fire({
                        title: 'Suppression en cours...',
                        text: 'Veuillez patienter.',
                        allowOutsideClick: false,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    // Soumettre le formulaire après avoir affiché l'indicateur de chargement
                    deleteGroupForm.submit();
                }
            });
        });
    });

    //end delete group/
</script>

<?= $this->endSection() ?>