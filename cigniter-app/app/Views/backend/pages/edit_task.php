<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Modifier la tâche</h4>
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
                        Modifier la tâche
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card card-box">
            <div class="card-body">
                <form action="<?= route_to('tasks.update', $task['id']) ?>" method="post">
                    <div class="form-group">
                        <label for="name">Nom de la tâche</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= $task['name'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" required><?= $task['description'] ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="project_id">Projet</label>
                        <select class="form-control" id="project_id" name="project_id" required>
                            <?php foreach ($projects as $project): ?>
                                <option value="<?= $project['id'] ?>" <?= ($task['project_id'] == $project['id']) ? 'selected' : '' ?>>
                                    <?= $project['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="user_id">Utilisateur</label>
                        <select class="form-control" id="user_id" name="user_id" required>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['id'] ?>" <?= ($task['user_id'] == $user['id']) ? 'selected' : '' ?>>
                                    <?= $user['username'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Mettre à jour la tâche</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
