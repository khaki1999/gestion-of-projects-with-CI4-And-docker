<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Tâches du Projet <?= $project['name'] ?></h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('projects.list') ?>">Projets</a>
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
                        Tâches du Projet <?= $project['name'] ?>
                    </div>
                    <div class="pull-right">
                        <a href="<?= route_to('tasks.create') ?>" class="btn btn-default btn-sm p-0" role="button">
                            <i class="fa fa-plus-circle"> Ajouter</i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless table-hover table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
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
                                    <td scope="row"><?= $task['id'] ?></td>
                                    <td><?= $task['name'] ?></td>
                                    <td><?= $task['description'] ?></td>
                                    <td><?= $task['project_id'] ?></td>
                                    <td><?= $task['user_id'] ?></td>
                                    <td>
                                        <a href="<?= route_to('tasks.edit', $task['id']) ?>" class="btn btn-primary btn-sm">Modifier</a>
                                        <a href="<?= route_to('tasks.delete', $task['id']) ?>" class="btn btn-danger btn-sm">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6">Aucune tache trouvée pour ce projet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>