<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Éditer Projet</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Accueil</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('projects.list') ?>">Projets</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Éditer Projet
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
                <h5>Détails du Projet</h5>
            </div>
            <div class="card-body">
                <form action="<?= route_to('projects.update', $project['id']) ?>" method="post">
                    <div class="form-group">
                        <label for="name">Nom du Projet</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= $project['name'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description"><?= $project['description'] ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer les Modifications</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
