<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8" />
	<title><?= isset($pageTitle) ? $pageTitle : 'GESTION DE PROJET'; ?></title>

	<!-- Site favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="/backend/vendors/images/apple-touch-icon.png" />
	<link rel="icon" type="image/png" sizes="32x32" href="/backend/vendors/images/favicon-32x32.png" />
	<link rel="icon" type="image/png" sizes="16x16" href="/backend/vendors/images/favicon-16x16.png" />
	<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pace/1.2.4/themes/blue/pace-theme-minimal.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ladda/1.0.6/ladda-themeless.min.css" /> -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

	<link rel="stylesheet" href="/extra-assets/ijabo/ijaboCropTool.min.css">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
	
	<!-- SweetAlert CDN -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<!-- Bootstrap JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<!-- Bootstrap JS -->
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/ladda/1.0.6/spin.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/ladda/1.0.6/ladda.min.js"></script> -->
	



	<script src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.2.4/pace.min.js"></script>


	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="/backend/vendors/styles/core.css" />
	<link rel="stylesheet" type="text/css" href="/backend/vendors/styles/icon-font.min.css" />
	<link rel="stylesheet" type="text/css" href="/backend/vendors/styles/style.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<link rel="stylesheet" href="/extra-assets/ijabo/ijaboCropTool.min.css">

	<?= $this->renderSection('stylesheets') ?>

</head>

<body>


	<?php include 'inc/header.php'; ?>
	<?php include 'inc/right-sidebar.php' ?>

	<?php include 'inc/left-sidebar.php'  ?>


	<div class="mobile-menu-overlay"></div>

	<div class="main-container">
		<div class="pd-ltr-20 xs-pd-20-10">
			<div class="min-height-200px">

				<div>
					<?= $this->renderSection('content') ?>
				</div>
				<?php include 'inc/footer.php' ?>
			</div>
		</div>
	</div>
	<!-- js -->
	<script src="/backend/vendors/scripts/core.js"></script>
	<script src="/backend/vendors/scripts/script.min.js"></script>
	<script src="/backend/vendors/scripts/process.js"></script>
	<script src="/backend/vendors/scripts/layout-settings.js"></script>
	<script src="/extra-assets/ijabo/ijaboCropTool.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


	<?= $this->renderSection('script') ?>
</body>

</html>
<!-- Toastr CSS -->

<!-- Toastr JS -->
