<?= $this->extend('backend/layout/pages-layout') ?>

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

<div class="row">
    <!-- Cadre pour le total des utilisateurs -->
    <div class="col-md-4">
        <div class="card card-statistics">
            <div class="card-body">
                <div class="card-title">
                    <h5>Totals utilisateurs</h5>
                </div>
                <div class="card-value">
                    <?= $totalUsers ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Cadre pour le total des projets -->
    <div class="col-md-4">
        <div class="card card-statistics">
            <div class="card-body">
                <div class="card-title">
                    <h5>Totals Projetss</h5>
                </div>
                <div class="card-value">
                    <?= $totalProjects ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Cadre pour le total des taches -->
    <div class="col-md-4">
        <div class="card card-statistics">
            <div class="card-body">
                <div class="card-title">
                    <h5>Totals Taches</h5>
                </div>
                <div class="card-value">
                    <?= $totalTasks ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
