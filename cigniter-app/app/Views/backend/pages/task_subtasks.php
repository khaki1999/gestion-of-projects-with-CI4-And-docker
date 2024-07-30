<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Sous-tâches de la tâche <?= $task['name'] ?></h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('tasks.list') ?>">Tâches</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Sous-tâches
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
                        Liste des sous-tâches de la tâche <?= $task['name'] ?>
                    </div>
                    <div class="pull-right">
                        <a href="<?= route_to('subtasks.create', $task['id']) ?>" class="btn btn-default btn-sm p-0" role="button">
                            <i class="fa fa-plus-circle"> Ajouter</i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless table-hover table-striped">
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
                        <?php if (!empty($subtasks)) : ?>
                            <?php foreach ($subtasks as $subtask) : ?>
                                <tr>
                                    <td><?= $subtask['name'] ?></td>
                                    <td><?= $subtask['description'] ?></td>
                                    <td><?= $subtask['project_name'] ?></td>
                                    <td><?= $subtask['user_name'] ?></td>
                                    <td>
                                        <a href="<?= route_to('subtasks.edit', $subtask['id']) ?>" class="btn btn-primary btn-sm">Modifier</a>
                                        <a href="<?= route_to('subtasks.delete', $subtask['id']) ?>" class="btn btn-danger btn-sm">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5">Aucune sous-tâche trouvée.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>