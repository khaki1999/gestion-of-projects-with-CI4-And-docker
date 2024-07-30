<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Tâches</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Tâches
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
                        Tâches
                    </div>
                    <div class="pull-right">
                        <button type="button" class="btn btn-default btn-sm p-0" data-toggle="modal" data-target="#createTaskModal">
                            <i class="fa fa-plus-circle"> Ajouter</i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="get" action="<?= route_to('tasks.list') ?>">
                    <div class="form-group">
                        <label for="task_type">Type de Tâche</label>
                        <select id="task_type" name="task_type" class="form-control">
                            <option value="">Toutes les tâches</option>
                            <option value="parent" <?= $taskType === 'parent' ? 'selected' : '' ?>>
                                Tâches principales
                            </option>
                            <option value="subtask" <?= $taskType === 'subtask' ? 'selected' : '' ?>>
                                Sous-tâches
                            </option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                </form>

                <table class="table table-sm table-borderless table-hover table-striped mt-3">
                    <thead>
                        <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Description</th>
                            <th scope="col">Projet</th>
                            <th scope="col">Utilisateur</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($tasks)) : ?>
                            <?php foreach ($tasks as $task) : ?>
                                <tr>
                                    <td><?= esc($task['name']) ?></td>
                                    <td><?= esc($task['description']) ?></td>
                                    <td><?= esc($task['project_name']) ?></td>
                                    <td><?= esc($task['user_name']) ?></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editTaskModal" onclick="openEditTaskModal(<?= $task['id'] ?>)">Modifier</button>

                                        <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?= route_to('tasks.delete', $task['id']) ?>')">
                                            Supprimer
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#subtasksModal" data-task-id="<?= $task['id'] ?>" data-task-name="<?= htmlspecialchars($task['name']) ?>">
                                            Voir les Sous-Tâches
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4">Aucune tache trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= view('backend/modals/create_task_modal') ?>
<?= view('backend/modals/edit_task_modal') ?>
<?= view('backend/modals/list_subtasks_modal') ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    //create task
    document.addEventListener('DOMContentLoaded', function() {
        $('#createTaskModal').on('show.bs.modal', function() {
            // Fetch data for users and projects
            fetch('<?= route_to('tasks.create') ?>')
                .then(response => response.json())
                .then(data => {
                    const projectSelect = document.getElementById('taskProject');
                    const userSelect = document.getElementById('taskUser');

                    // Clear existing options
                    projectSelect.innerHTML = '';
                    userSelect.innerHTML = '';

                    // Add default option
                    projectSelect.innerHTML += '<option value="" selected>Choisir un projet</option>';
                    userSelect.innerHTML += '<option value="" selected>Choisir un utilisateur</option>';

                    // Populate project options
                    data.projects.forEach(project => {
                        projectSelect.innerHTML += `<option value="${project.id}">${project.name}</option>`;
                    });

                    // Populate user options
                    data.users.forEach(user => {
                        userSelect.innerHTML += `<option value="${user.id}">${user.username}</option>`;
                    });
                });
        });
    });
    //end create task//
    // Afficher les sous-tâches dans la modal
    // subtasks_modal.js//
    document.addEventListener('DOMContentLoaded', function() {
        $('#subtasksModal').on('show.bs.modal', function(e) {
            var taskId = $(e.relatedTarget).data('task-id');
            var taskName = $(e.relatedTarget).data('task-name');
            var modalTitle = document.getElementById('subtasksModalLabel');
            var subtasksList = document.getElementById('subtasksList');
            var loadingIndicator = document.getElementById('loadingIndicator');

            modalTitle.textContent = 'Sous-Tâches de : ' + taskName;
            subtasksList.innerHTML = '';
            loadingIndicator.style.display = 'block';

            fetch('/tasks/subtasks/' + taskId)
                .then(response => response.json())
                .then(data => {
                    loadingIndicator.style.display = 'none';

                    if (data.subtasks.length) {
                        var ul = document.createElement('ul');
                        ul.className = 'list-group';
                        data.subtasks.forEach(subtask => {
                            var li = document.createElement('li');
                            li.className = 'list-group-item';
                            li.textContent = subtask.name + ' - ' + subtask.description;
                            ul.appendChild(li);
                        });
                        subtasksList.appendChild(ul);
                    } else {
                        subtasksList.textContent = 'Aucune sous-tâche trouvée.';
                    }
                })
                .catch(error => {
                    loadingIndicator.style.display = 'none';
                    console.error('Erreur lors de la récupération des sous-tâches :', error);
                    subtasksList.textContent = 'Erreur lors de la récupération des sous-tâches.';
                });

            fetch('/tasks/subtasks/create/' + taskId)
                .then(response => response.json())
                .then(data => {
                    const userSelect = document.getElementById('subtaskUser');
                    userSelect.innerHTML = '<option value="" selected>Choisir un utilisateur</option>';

                    data.users.forEach(user => {
                        userSelect.innerHTML += `<option value="${user.id}">${user.username}</option>`;
                    });

                    document.getElementById('projectId').value = data.project_id;
                    document.getElementById('parentId').value = taskId;
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des utilisateurs :', error);
                });
        });

        document.getElementById('showAddSubtaskFormBtn').addEventListener('click', function() {
            document.getElementById('addSubtaskFormContainer').style.display = 'block';
        });

        document.getElementById('addSubtaskForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var submitButton = document.getElementById('submitSubtaskBtn');
            var spinner = submitButton.querySelector('.spinner-border');

            // Afficher l'indicateur de chargement et désactiver le bouton
            spinner.style.display = 'inline-block';
            submitButton.disabled = true;

            fetch('/tasks/storeSubtask', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    spinner.style.display = 'none'; // Masquer l'indicateur de chargement
                    submitButton.disabled = false; // Réactiver le bouton

                    if (result.success) {
                        Swal.fire('Succès', result.message, 'success').then(() => {
                            // Recharger la page pour voir les changements
                            location.reload();
                        });
                    } else {
                        Swal.fire('Erreur', result.message, 'error');
                    }
                })
                .catch(error => {
                    spinner.style.display = 'none'; // Masquer l'indicateur de chargement
                    submitButton.disabled = false; // Réactiver le bouton
                    console.error('Erreur de requête :', error);
                    Swal.fire('Erreur', 'Échec de la requête.', 'error');
                });
        });
    });

    //end subtask//
    //edit task//
    function openEditTaskModal(taskId) {
        console.log('ID de la tâche :', taskId);
        document.getElementById('loadingSpinner').style.display = 'block'; // Show loading spinner

        fetch('/tasks/edit/' + taskId)
            .then(response => response.json())
            .then(data => {
                console.log('Données reçues :', data);
                if (data.task) {
                    const {
                        task,
                        projects,
                        users,
                        userId
                    } = data;
                    showEditTaskModal(task.id, task.name, task.description, projects, users, userId);
                } else {
                    console.error('La tâche est indéfinie');
                }
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des données :', error);
            })
            .finally(() => {
                document.getElementById('loadingSpinner').style.display = 'none'; // Hide loading spinner
            });
    }


    function showEditTaskModal(taskId, name, description, projects, users, userId) {
        document.getElementById('task-id').value = taskId;
        document.getElementById('name').value = name;
        document.getElementById('description').value = description;

        const projectSelect = document.getElementById('project_id');
        projectSelect.innerHTML = '<option value="" selected>Choisir un projet</option>';
        projects.forEach(project => {
            projectSelect.innerHTML += `<option value="${project.id}">${project.name}</option>`;
        });

        const userSelect = document.getElementById('user_id');
        userSelect.innerHTML = '<option value="" selected>Choisir un utilisateur</option>';
        users.forEach(user => {
            userSelect.innerHTML += `<option value="${user.id}">${user.username}</option>`;
        });

        if (userId) {
            userSelect.value = userId;
        }

        $('#editTaskModal').modal('show'); // Show the modal
    }

    document.getElementById('update-task-form').addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        // Afficher le spinner sur le bouton
        var button = document.getElementById('updateTaskButton');
        var buttonText = document.getElementById('buttonText');
        var loadingSpinnerButton = document.getElementById('loadingSpinnerButton');

        buttonText.style.display = 'none'; // Cacher le texte du bouton
        loadingSpinnerButton.style.display = 'inline-block'; // Afficher le spinner

        fetch('/tasks/update/' + formData.get('task_id'), {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    Swal.fire('Succès', result.message, 'success').then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Erreur', result.message, 'error');
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
    //end edit task//


    function confirmDelete(url) {
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Vous ne pourrez pas revenir en arrière après la suppression de cette tâche.",
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
</script>

<?= $this->endSection() ?>