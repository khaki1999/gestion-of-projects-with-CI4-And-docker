<?= $this->extend('backend/layout/pages-layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Projets</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Projets
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
                        Projets
                    </div>
                    <div class="pull-right">
                        <a href="<?= route_to('projects.create') ?>" class="btn btn-default btn-sm p-0" role="button">
                            <i class="fa fa-plus-circle"> Ajouter</i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if (session()->has('alert')) : ?>
                    <script>
                        Swal.fire({
                            icon: '<?= esc(session()->getFlashdata('alert')['type']) ?>',
                            title: '<?= esc(session()->getFlashdata('alert')['type']) ?>',
                            text: '<?= esc(session()->getFlashdata('alert')['message']) ?>'
                        });
                    </script>
                <?php endif; ?>

                <table class="table table-sm table-borderless table-hover table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Description</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($projects)) : ?>
                            <?php foreach ($projects as $project) : ?>
                                <tr>
                                    <td><?= esc($project['name']) ?></td>
                                    <td><?= esc($project['description']) ?></td>
                                    <td>
                                        <a href="<?= route_to('projects.edit', $project['id']) ?>" class="btn btn-primary btn-sm">Modifier</a>
                                        <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?= route_to('projects.delete', $project['id']) ?>')">
                                            Supprimer
                                        </button>
                                        <a href="<?= route_to('projects.tasks', $project['id']) ?>" class="btn btn-secondary btn-sm">Voir les Tâches</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4">Aucun projet trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(url) {
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Vous ne pourrez pas revenir en arrière après la suppression de ce projet.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, supprimer !',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to the URL for deletion
                window.location.href = url;
            }
        });
    }
</script>

<?= $this->endSection() ?>