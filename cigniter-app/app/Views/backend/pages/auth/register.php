<?= $this->extend('/backend/layout/auth-layout') ?>
<?= $this->section('content') ?>

<div class="login-box bg-white box-shadow border-radius-10">
	<div class="login-title">
		<h2 class="text-center text-primary">Créer un compte</h2>
	</div>
	<?php $validation = \Config\Services::validation(); ?>
	<form action="<?= route_to('admin.register.handler') ?>" method="POST">
		<?= csrf_field() ?>

		<!-- Flash messages -->
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
			<input type="text" class="form-control form-control-lg" placeholder="Nom" name="name" value="<?= set_value('name') ?>">
			<div class="input-group-append custom">
				<span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
			</div>
		</div>
		<?php if ($validation->getError('name')) : ?>
			<div class="d-block text-danger" style="margin-top:-25px;margin-bottom:15px;">
				<?= $validation->getError('name') ?>
			</div>
		<?php endif; ?>

		<div class="input-group custom">
			<input type="text" class="form-control form-control-lg" placeholder="Prénom" name="first_name" value="<?= set_value('first_name') ?>">
			<div class="input-group-append custom">
				<span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
			</div>
		</div>
		<?php if ($validation->getError('first_name')) : ?>
			<div class="d-block text-danger" style="margin-top:-25px;margin-bottom:15px;">
				<?= $validation->getError('first_name') ?>
			</div>
		<?php endif; ?>

		<div class="input-group custom">
			<input type="date" class="form-control form-control-lg" placeholder="Date de Naissance" name="datenais" value="<?= set_value('datenais') ?>">
			<div class="input-group-append custom">
				<span class="input-group-text"><i class="icon-copy dw dw-calendar1"></i></span>
			</div>
		</div>
		<?php if ($validation->getError('datenais')) : ?>
			<div class="d-block text-danger" style="margin-top:-25px;margin-bottom:15px;">
				<?= $validation->getError('datenais') ?>
			</div>
		<?php endif; ?>

		<div class="input-group custom">
			<input type="text" class="form-control form-control-lg" placeholder="Username" name="username" value="<?= set_value('username') ?>">
			<div class="input-group-append custom">
				<span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
			</div>
		</div>
		<?php if ($validation->getError('username')) : ?>
			<div class="d-block text-danger" style="margin-top:-25px;margin-bottom:15px;">
				<?= $validation->getError('username') ?>
			</div>
		<?php endif; ?>

		<div class="input-group custom">
			<input type="email" class="form-control form-control-lg" placeholder="Email" name="email" value="<?= set_value('email') ?>">
			<div class="input-group-append custom">
				<span class="input-group-text"><i class="icon-copy dw dw-envelope1"></i></span>
			</div>
		</div>
		<?php if ($validation->getError('email')) : ?>
			<div class="d-block text-danger" style="margin-top:-25px;margin-bottom:15px;">
				<?= $validation->getError('email') ?>
			</div>
		<?php endif; ?>

		<div class="input-group custom">
			<input type="password" class="form-control form-control-lg" placeholder="Mot de passe" name="password">
			<div class="input-group-append custom">
				<span class="input-group-text"><i class="dw dw-padlock1"></i></span>
			</div>
		</div>
		<?php if ($validation->getError('password')) : ?>
			<div class="d-block text-danger" style="margin-top:-25px;margin-bottom:15px;">
				<?= $validation->getError('password') ?>
			</div>
		<?php endif; ?>

		<div class="row">
			<div class="col-sm-12">
				<div class="input-group mb-0">
					<input class="btn btn-primary btn-lg btn-block" type="submit" value="Créer">
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

		form.addEventListener('submit', function() {
			loading.style.display = 'flex';
		});
	});
</script>

<?= $this->endSection('content') ?>