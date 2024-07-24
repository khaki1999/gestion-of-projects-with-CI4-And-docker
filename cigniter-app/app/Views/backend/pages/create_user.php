<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Ajouter un utilisateur</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('users.list') ?>">Utilisateurs</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Ajouter un utilisateur
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
                <?php if (session()->getFlashdata('alert')): ?>
                    <?php $alert = session()->getFlashdata('alert'); ?>
                    <div class="alert alert-<?= $alert['type'] ?>"><?= $alert['message'] ?></div>
                <?php endif; ?>

                <form action="<?= route_to('users.store') ?>" method="post">
                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="first_name">Prénom</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="datenais">Date de naissance</label>
                        <input type="date" class="form-control" id="datenais" name="datenais" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Nom d'utilisateur</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter l'utilisateur</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('form').addEventListener('submit', function(event) {
        var dateOfBirthInput = document.getElementById('datenais');
        var dateOfBirth = new Date(dateOfBirthInput.value);
        var currentDate = new Date();

        // Vérifier que la date de naissance est valide et non dans le futur
        if (dateOfBirth >= currentDate) {
            event.preventDefault();
            alert('La date de naissance doit être antérieure à la date actuelle');
        }
    });
});
</script>

<?= $this->endSection() ?>
