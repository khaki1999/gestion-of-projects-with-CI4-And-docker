<?= $this->extend('backend/layout/pages_layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Profile</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Profile
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
        <div class="pd-20 card-box height-100-p">
            <div class="profile-photo">
                <a href="javascript:;" onclick="event.preventDefault();document.getElementById('user_profile_file').click();" class="edit-avatar"><i class="fa fa-pencil"></i></a>
                <input type="file" name="user_profile_file" id="user_profile_file" class="d-none" style="opacity:0;">
                <img src="<?= get_user()->picture  == null? '/images/users/default_avatar.png' :'/images/users/'. get_user()->picture ?>" alt="" class="avatar-photo ci-avatar-photo">
            </div>
            <h5 class="text-center h5 mb-0 ci-user-name"><?= get_user()->username ?></h5>
            <p class="text-center text-muted font-14 ci-user-email"><?= get_user()->email ?></p>
        </div>
    </div>
    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
        <div class="card-box height-100-p overflow-hidden">
            <div class="profile-tab height-100-p">
                <div class="tab height-100-p">
                    <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#personal_details" role="tab">Détails personnels</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#change_password" role="tab">Changer le mot de passe</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <!-- Personal Details Tab start -->
                        <div class="tab-pane fade show active" id="personal_details" role="tabpanel">
                            <div class="pd-20">
                                <!-- Ajouter une zone pour les messages de succès -->
                                <div id="success-message" class="alert alert-success" style="display: none;"></div>

                                <!-- Ajouter une zone pour les messages d'erreur -->
                                <div id="error-message" class="alert alert-danger" style="display: none;"></div>

                                <form id="personal_details_form" method="POST" action="<?= route_to('update-personal-detail'); ?>">
                                    <?= csrf_field(); ?>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Nom</label>
                                                <input type="text" name="name" class="form-control" placeholder="Entrez votre nom" value="<?= get_user()->name ?>">
                                                <span class="text-danger error-text name_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Prénom</label>
                                                <input type="text" name="first_name" class="form-control" placeholder="Entrez votre prénom" value="<?= get_user()->first_name ?>">
                                                <span class="text-danger error-text first_name_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Nom d'utilisateur</label>
                                                <input type="text" name="username" class="form-control" placeholder="Entrez votre nom d'utilisateur" value="<?= get_user()->username ?>">
                                                <span class="text-danger error-text username_error"></span>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="">Date de naissance</label>
                                            <input type="date" class="form-control" id="datenais" name="datenais" value="<?= get_user()->datenais ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Enregistrer les changements</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Personal Details Tab End -->
                        <!-- Change Password Tab start -->
                        <div class="tab-pane fade" id="change_password" role="tabpanel">
                            <div class="pd-20 profile-task-wrap">
                                -- password --
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#personal_details_form').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            var formdata = new FormData(form);

            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: formdata,
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    $('#success-message').hide().text('');
                    $('#error-message').hide().text('');
                    $(form).find('span.error-text').text('');
                },
                success: function(response) {
                    if ($.isEmptyObject(response.error)) {
                        if (response.status == 1) {
                            $('.ci-user-name').text(response.user_info.username);
                            $('.ci-user-email').text(response.user_info.email);
                            $('#success-message').text(response.msg).show();
                        } else {
                            $('#error-message').text(response.msg).show();
                        }
                    } else {
                        $.each(response.error, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Erreur AJAX:', xhr.responseText);
                    console.log('Statut:', status);
                    console.log('Erreur:', error);
                    $('#error-message').text('Une erreur est survenue. Veuillez réessayer.').show();
                }
            });
        });
    });

    $('#user_profile_file').ijaboCropTool({
        preview: '.ci-avatar-photo',
        setRatio: 1,
        allowedExtensions: ['jpg', 'jpeg', 'png'],
        processUrl: '<?= route_to('update-profile-picture')?>',
        withCSRF: ['<?= csrf_token() ?>', '<?= csrf_hash() ?>'],
        onSuccess: function(message, element, status) {
            if(status == 1){
                toastr.success(message);
            }else{
                toastr.error(message);
            }
        },
        onError: function(message, element, status) {
            alert(message);
        }
    });
</script>
<?= $this->endSection() ?>