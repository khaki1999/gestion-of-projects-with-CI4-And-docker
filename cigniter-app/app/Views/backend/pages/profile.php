<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Profil de <?= esc($user['username']) ?></h4>
            </div>
            <!-- Breadcrumb ici si nécessaire -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card card-box">
            <div class="card-body">
                <p><strong>Nom :</strong> <?= esc($user['name']) ?></p>
                <p><strong>Prénom :</strong> <?= esc($user['first_name']) ?></p>
                <p><strong>Date de Naissance :</strong> <?= esc($user['datenais']) ?></p>
                <p><strong>Nom d'utilisateur :</strong> <?= esc($user['username']) ?></p>
                <p><strong>Email :</strong> <?= esc($user['email']) ?></p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
