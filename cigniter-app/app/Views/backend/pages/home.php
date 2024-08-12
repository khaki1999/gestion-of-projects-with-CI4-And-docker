<?= $this->extend('backend/layout/pages_layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Home</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="alert alert-info text-center">
            <h4>Bienvenue, <?= get_user()->username ?> !</h4>
            <p>Nous sommes ravis de vous voir aujourd'hui. Voici un aperçu des statistiques actuelles.</p>
        </div>
    </div>
</div>

<div class="row">
    <!-- Cadre pour le total des utilisateurs -->
    <div class="col-md-4 mb-4">
        <div class="card card-statistics">
            <div class="card-body">
                <div class="card-title d-flex align-items-center">
                    <i class="fa fa-users fa-2x mr-2"></i>
                    <h5>Total Utilisateurs</h5>
                </div>
                <div class="card-value">
                    <?= $totalUsers ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Cadre pour le total des projets -->
    <div class="col-md-4 mb-4">
        <div class="card card-statistics">
            <div class="card-body">
                <div class="card-title d-flex align-items-center">
                    <i class="fa fa-project-diagram fa-2x mr-2"></i>
                    <h5>Total Projets</h5>
                </div>
                <div class="card-value">
                    <?= $totalProjects ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Cadre pour le total des tâches -->
    <div class="col-md-4 mb-4">
        <div class="card card-statistics">
            <div class="card-body">
                <div class="card-title d-flex align-items-center">
                    <i class="fa fa-tasks fa-2x mr-2"></i>
                    <h5>Total Tâches</h5>
                </div>
                <div class="card-value">
                    <?= $totalTasks ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
