<div class="left-side-bar">
    <div class="brand-logo">
        <a href="">
            <img src="/backend/vendors/images/deskapp-logo.svg" alt="" class="dark-logo" />
            <img src="/backend/vendors/images/deskapp-logo-white.svg" alt="" class="light-logo" />
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">
                <!-- Éléments du menu -->
                <li class="dropdown active">
                    <a href="<?= route_to('admin.home') ?>" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-house"></span><span class="mtext">Home</span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="<?= route_to('projects.list') ?>" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-cast"></span><span class="mtext">Projets</span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="<?= route_to('tasks.list') ?>" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-list-task"></span><span class="mtext">Tâches</span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="<?= route_to('users.list') ?>" class="dropdown-toggle no-arrow">
                        <span class="micon fa fa-user-o"></span><span class="mtext">Utilisateurs</span>
                    </a>
                </li>
                <!-- Séparation avant Profil -->
                <li class="nav-item mt-3">
                    <hr class="my-3">
                </li>
                <!-- Profil -->
                <li class="dropdown">
                    <a href="<?= route_to('admin.profil') ?>" class="dropdown-toggle no-arrow">
                        <span class="micon fa fa-user-o"></span><span class="mtext">Profil</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
