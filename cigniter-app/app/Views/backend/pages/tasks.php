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
                        <a href="<?= route_to('tasks.create') ?>" class="btn btn-default btn-sm p-0" role="button">
                            <i class="fa fa-plus-circle"> Ajouter</i>
                        </a>
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
                                        <a href="<?= route_to('tasks.edit', $task['id']) ?>" class="btn btn-primary btn-sm">Modifier</a>
                                        <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?= route_to('tasks.delete', $task['id']) ?>')">
                                            Supprimer
                                        </button>
                                        <a href="<?= route_to('tasks.subtasks', $task['id']) ?>" class="btn btn-info btn-sm">Voir les Sous-Tâches</a>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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