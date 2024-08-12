<?= $this->extend('/backend/layout/auth-layout') ?>
<?= $this->section('content') ?>

<div class="login-box bg-white box-shadow border-radius-10">
    <div class="login-title">
        <h2 class="text-center text-primary">Connexion</h2>
    </div>
    <?php $validation = \Config\Services::validation(); ?>
    <form action="<?= route_to('admin.login.handler') ?>" method="POST">
        <?= csrf_field() ?>

        <?php if (!empty(session()->getFlashdata('success'))) : ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif ?>
        <?php if (!empty(session()->getFlashdata('fail'))) : ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('fail') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif ?>

        <!-- Form fields -->
        <div class="input-group custom">
            <input type="text" class="form-control form-control-lg" placeholder="Username or email" name="login_id" value="<?= set_value('login_id') ?>">
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
            </div>
        </div>
        <?php if ($validation->getError('login_id')) : ?>
            <div class="d-block text-danger" style="margin-top:-25px;margin-bottom:15px;">
                <?= $validation->getError('login_id') ?>
            </div>
        <?php endif; ?>
        
        <div class="input-group custom">
            <input type="password" id="password" class="form-control form-control-lg" placeholder="**********" name="password" value="<?= set_value('password') ?>">
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                <!-- Bouton pour afficher/masquer le mot de passe -->
                <button type="button" id="togglePassword" class="btn btn-outline-secondary">
                    <i class="dw dw-eye"></i>
                </button>
            </div>
        </div>
        <?php if ($validation->getError('password')) : ?>
            <div class="d-block text-danger" style="margin-top:-25px;margin-bottom:15px;">
                <?= $validation->getError('password') ?>
            </div>
        <?php endif; ?>
        
        <div class="row pb-30">
            <div class="col-6">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="customCheck1" name="remember_me">
                    <label class="custom-control-label" for="customCheck1">se rappeler</label>
                </div>
            </div>
            <div class="col-6">
                <div class="forgot-password">
                    <a href="<?= route_to('admin.forgot.form') ?>">mot de passe oublié</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="input-group mb-0">
                    <input class="btn btn-primary btn-lg btn-block" type="submit" value="Connexion">
                </div>
                <div class="font-16 weight-600 pt-10 pb-10 text-center" data-color="#707373" style="color: rgb(112, 115, 115);">
                    Ou
                </div>
                <div class="input-group mb-0">
                    <a class="btn btn-outline-primary btn-lg btn-block" href="<?= route_to('admin.register.form') ?>">Créer un compte</a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Ajout du CSS et du JavaScript pour le spinner -->
<style>
    /* Spinner de chargement */
    #loading {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .spinner {
        border: 4px solid rgba(0, 0, 0, 0.1);
        border-left: 4px solid #3498db;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<div id="loading" style="display: none;">
    <div class="spinner"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const loading = document.getElementById('loading');
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');

        form.addEventListener('submit', function() {
            loading.style.display = 'flex';
        });

        // Toggle password visibility
        togglePassword.addEventListener('click', function() {
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            const icon = togglePassword.querySelector('i');
            icon.classList.toggle('dw-eye');
            icon.classList.toggle('dw-eye-slash');
        });
    });
</script>

<?= $this->endSection('content') ?>
