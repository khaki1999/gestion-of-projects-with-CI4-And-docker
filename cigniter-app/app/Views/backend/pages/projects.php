<?= $this->extend('backend/layout/pages-layout') ?>

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

                <table class="table table-sm table-borderless table-hover table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Description</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($projects)) : ?>
                            <?php foreach ($projects as $project) : ?>
                                <tr>
                                    <td><?= esc($project['name']) ?></td>
                                    <td><?= esc($project['description']) ?></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" onclick="showEditProjectModal(<?= $project['id'] ?>, '<?= esc($project['name']) ?>', '<?= esc($project['description']) ?>')">
                                            Modifier
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?= route_to('projects.delete', $project['id']) ?>')">
                                            Supprimer
                                        </button>
                                        <button class="btn btn-secondary btn-sm" onclick="showTasksModal(<?= $project['id'] ?>, '<?= esc($project['name']) ?>')">
                                            Voir les Tâches
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
            </div>
        </div>
    </div>
</div>

<!-- Inclusion des modales -->
<?= view('backend/modals/create_project_modal') ?>
<?= view('backend/modals/edit_project_modal') ?>
<?= view('backend/modals/tasks_modal') ?>

<script>
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

    function showEditProjectModal(id, name, description) {
        $('#editProjectId').val(id);
        $('#editName').val(name);
        $('#editDescription').val(description);
        $('#editProjectModalLabel').text(`Éditer Projet: ${name}`);
        $('#editProjectForm').attr('action', '<?= route_to('projects.update', 0) ?>'.replace('0', id));
        $('#editProjectModal').modal('show');
    }


    function showTasksModal(projectId, projectName) {
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
            $('#tasksModal').modal('show');
        });
    }
</script>

<?= $this->endSection() ?>