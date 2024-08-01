<?= $this->extend('backend/layout/pages_layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Projets</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Projets
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
                        Projets
                    </div>
                    <div class="pull-right">
                        <button type="button" class="btn btn-default btn-sm p-0" data-toggle="modal" data-target="#createProjectModal">
                            <i class="fa fa-plus-circle"> Ajouter</i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if (session()->has('alert')) : ?>
                    <script>
                        Swal.fire({
                            icon: '<?= esc(session()->getFlashdata('alert')['type']) ?>',
                            title: '<?= esc(session()->getFlashdata('alert')['type']) ?>',
                            text: '<?= esc(session()->getFlashdata('alert')['message']) ?>'
                        });
                    </script>
                <?php endif; ?>

                <form id="deleteGroupForm" method="post" action="<?= route_to('project.delete_group') ?>">
                    <?= csrf_field() ?>
                    <table class="table table-sm table-borderless table-hover table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Selectionner</th>
                                <th scope="col">Nom</th>
                                <th scope="col">Description</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($projects)) : ?>
                                <?php foreach ($projects as $project) : ?>
                                    <tr>
                                        <td><input type="checkbox" name="project_ids[]" value="<?= $project['id'] ?>" class="project-checkbox"></td>
                                        <td><?= esc($project['name']) ?></td>
                                        <td><?= esc($project['description']) ?></td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" onclick="showEditProjectModal(<?= $project['id'] ?>, '<?= esc($project['name']) ?>', '<?= esc($project['description']) ?>'); return false;">
                                                <i class="fa fa-pencil-alt"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?= route_to('projects.delete', $project['id']) ?>'); return false;">
                                                <i class="fa fa-trash-alt"></i>
                                            </button>
                                            <button class="btn btn-secondary btn-sm" onclick="showTasksModal(<?= $project['id'] ?>, '<?= esc($project['name']) ?>'); return false;">
                                                <i class="fa fa-tasks"></i>
                                                <span class="spinner-border spinner-border-sm ml-2" id="tasksLoading-<?= $project['id'] ?>" style="display: none;" role="status">
                                                    <span class="sr-only">Chargement...</span>
                                                </span>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="4">Aucun projet trouvé.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <!-- suppression groupée -->
                    <button id="deleteGroupBtn" type="submit" class="btn btn-danger">Supprimer les projets sélectionnées</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Inclusion des modales -->
<?= view('backend/modals/create_project_modal') ?>
<?= view('backend/modals/edit_project_modal') ?>
<?= view('backend/modals/tasks_modal') ?>

<script>
    //add project + loading//
    $(document).ready(function() {
        const checkboxes = document.querySelectorAll('.project-checkbox');
        const deleteGroupBtn = document.getElementById('deleteGroupBtn');
        const projectTable = document.querySelector('table tbody');

        // Vérifie s'il y a des tâches et ajuste la visibilité du bouton
        if (projectTable.children.length === 1 && projectTable.children[0].children.length === 1) {
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
                    const hasProject = projectTable.children.length > 0;
                    deleteGroupBtn.style.display = hasProject && Array.from(checkboxes).some(cb => cb.checked) ? 'inline-block' : 'none';
                }
            });
        });

        observer.observe(projectTable, {
            childList: true
        });
        //add project//
        $('#createProjectForm').on('submit', function(event) {
            event.preventDefault(); // Empêche le rechargement de la page

            // Affiche l'indicateur de chargement et désactive le bouton
            $('#loadingSpinnerButton').show();
            $('#createProjectSubmitButton').prop('disabled', true);

            // Soumettre le formulaire via AJAX
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    console.log('Réponse reçue:', response); // Débogage

                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès',
                            text: response.message
                        }).then(() => {
                            $('#createProjectModal').modal('hide');
                            location.reload(); // Recharge la page pour voir les modifications
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: response.message || 'Une erreur est survenue'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erreur AJAX:', status, error); // Débogage
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Une erreur est survenue'
                    });
                },
                complete: function() {
                    // Masque l'indicateur de chargement et réactive le bouton
                    $('#loadingSpinnerButton').hide();
                    $('#createProjectSubmitButton').prop('disabled', false);
                }
            });
        });
    });
    //end add project +loading//
    //edit project//
    function showEditProjectModal(id, name, description) {
        $('#editProjectId').val(id);
        $('#editName').val(name);
        $('#editDescription').val(description);
        $('#editProjectModalLabel').text(`Éditer Projet: ${name}`);
        $('#editProjectForm').attr('action', '<?= route_to('projects.update', 0) ?>'.replace('0', id));
        $('#editProjectModal').modal('show');
    }

    $(document).ready(function() {
        $('#editProjectForm').on('submit', function(event) {
            event.preventDefault(); // Empêche le rechargement de la page

            // Affiche l'indicateur de chargement et désactive le bouton
            $('#loadingSpinnerButton').show();
            $('#editProjectSubmitButton').prop('disabled', true);

            // Soumettre le formulaire via AJAX
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    console.log('Réponse reçue:', response); // Débogage

                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès',
                            text: response.message
                        }).then(() => {
                            $('#editProjectModal').modal('hide');
                            location.reload(); // Recharge la page pour voir les modifications
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: response.message || 'Une erreur est survenue'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erreur AJAX:', status, error); // Débogage
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Une erreur est survenue'
                    });
                },
                complete: function() {
                    // Masque l'indicateur de chargement et réactive le bouton
                    $('#loadingSpinnerButton').hide();
                    $('#editProjectSubmitButton').prop('disabled', false);
                }
            });
        });
    });
    //end edit project//
    //list task//
    function showTasksModal(projectId, projectName) {
        // Afficher l'indicateur de chargement pour le projet spécifique
        $(`#tasksLoading-${projectId}`).show();
        $('#tasksList').hide();

        $.get('<?= route_to('projects.tasks', 0) ?>'.replace('0', projectId), function(data) {
            let tasksHtml = '';

            $('#tasksModalLabel').text(`Tâches du Projet : ${projectName}`);

            if (data.length === 0) {
                tasksHtml = `<p>Aucune tâche pour le projet : ${projectName}.</p>`;
            } else {
                data.forEach(task => {
                    tasksHtml += `
                <div class="task-item">
                    <h5>${task.name}</h5>
                    <p>${task.description}</p>
                </div>
            `;
                });
            }

            $('#tasksList').html(tasksHtml);
            $('#tasksList').show(); // Afficher la liste des tâches
            $(`#tasksLoading-${projectId}`).hide(); // Masquer l'indicateur de chargement
            $('#tasksModal').modal('show');
        }).fail(function() {
            $('#tasksList').html('<p>Une erreur est survenue lors du chargement des tâches.</p>').show();
            $(`#tasksLoading-${projectId}`).hide(); // Masquer l'indicateur de chargement en cas d'erreur
        });
    }

    //end list task//
    function confirmDelete(url) {
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Vous ne pourrez pas revenir en arrière après la suppression de ce projet.",
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

    //delete group//
    document.addEventListener('DOMContentLoaded', function() {
        const deleteGroupBtn = document.getElementById('deleteGroupBtn');
        const deleteGroupForm = document.getElementById('deleteGroupForm');

        deleteGroupBtn.addEventListener('click', function(event) {
            event.preventDefault(); // Empêche la soumission du formulaire
            event.stopPropagation(); // Empêche la propagation de l'événement

            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Vous ne pourrez pas récupérer ces projets une fois supprimés !",
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