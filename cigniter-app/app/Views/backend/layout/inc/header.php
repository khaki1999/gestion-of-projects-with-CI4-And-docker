<div class="header">
    <div class="header-left">
        <div class="menu-icon bi bi-list"></div>
        <div class="search-toggle-icon bi bi-search" data-toggle="header_search"></div>
        <div class="header-search">
            <form id="searchForm">
                <div class="form-group mb-0">
                    <i class="dw dw-search2 search-icon"></i>
                    <input type="text" name="query" id="searchQuery" class="form-control search-input" placeholder="Rechercher..." />
                    <div class="dropdown">
                        <a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
                            <i class="ion-arrow-down-c"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Rechercher</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="header-right">
        <div class="dashboard-setting user-notification">
            <div class="dropdown">
                <a class="dropdown-toggle no-arrow" href="javascript:;" data-toggle="right-sidebar">
                    <i class="dw dw-settings2"></i>
                </a>
            </div>
        </div>
        <div class="user-notification">
            <div class="dropdown">
                <a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
                    <i class="icon-copy dw dw-notification"></i>
                    <span class="badge notification-active"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="notification-list mx-h-350 customscroll">
                        <ul>
                            <li>
                                <a href="#">
                                    <img src="/backend/vendors/images/img.jpg" alt="" />
                                    <h3>John Doe</h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
                                </a>
                            </li>
                            <!-- Ajoutez plus de notifications si nécessaire -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="user-info-dropdown">
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <span class="user-icon">
                        <img src="<?= get_user()->picture  == null? '/images/users/default_avatar.png' :'/images/users/'. get_user()->picture ?>" alt="" class="ci-avatar-photo">
                      
                    </span>
                    <span class="user-name ci-user-name"><?=get_user()->username?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    <a class="dropdown-item" href="<?= route_to('admin.profil') ?>"><i class="dw dw-user1"></i> Profile</a>
                    <a class="dropdown-item" href="<?= route_to('admin.logout') ?>"><i class="dw dw-logout"></i> Log Out</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= view('backend/layout/search_modal') ?>
<!-- Inclure jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Inclure Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<!-- Inclure Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('searchForm');
        const searchQuery = document.getElementById('searchQuery');
        const searchResultsContent = document.getElementById('searchResultsContent');
        const loadingIndicator = document.getElementById('loadingIndicator');

        searchForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const query = searchQuery.value;

            if (query.trim() === '') {
                alert('Veuillez entrer un terme de recherche.');
                return;
            }

            // Affiche l'indicateur de chargement
            loadingIndicator.style.display = 'block';
            searchResultsContent.innerHTML = '';

            fetch('<?= route_to('search.results') ?>?query=' + encodeURIComponent(query))
                .then(response => response.text())
                .then(html => {
                    searchResultsContent.innerHTML = html;
                    loadingIndicator.style.display = 'none';
                    $('#searchResultsModal').modal('show');
                })
                .catch(error => {
                    console.error('Erreur lors de la recherche:', error);
                    loadingIndicator.style.display = 'none';
                });
        });
    });
</script>

