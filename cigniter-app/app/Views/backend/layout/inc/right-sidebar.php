<div class="right-sidebar">
    <div class="sidebar-title">
        <h3 class="weight-600 font-16 text-blue">
            Layout Settings
            <span class="btn-block font-weight-400 font-12">User Interface Settings</span>
        </h3>
        <div class="close-sidebar" data-toggle="right-sidebar-close">
            <i class="icon-copy ion-close-round"></i>
        </div>
    </div>
    <div class="right-sidebar-body customscroll">
        <div class="right-sidebar-body-content">
            <!-- Mode de la barre latérale -->
            <h4 class="weight-600 font-18 pb-10">Sidebar Background</h4>
            <div class="sidebar-btn-group pb-30 mb-10">
                <a href="javascript:void(0);" class="btn btn-outline-primary sidebar-light">White</a>
                <a href="javascript:void(0);" class="btn btn-outline-primary sidebar-dark active">Dark</a>
            </div>

            <!-- Langue de l'en-tête -->
            <h4 class="weight-600 font-18 pb-10">Header Background</h4>
            <div class="sidebar-btn-group pb-30 mb-10">
                <a href="javascript:void(0);" class="btn btn-outline-primary header-white active">White</a>
                <a href="javascript:void(0);" class="btn btn-outline-primary header-dark">Dark</a>
            </div>

            <!-- Réinitialiser les paramètres -->
            <div class="reset-options pt-30 text-center">
                <button class="btn btn-danger" id="reset-settings">Reset Settings</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion des changements de fond de la barre latérale
        document.querySelectorAll('.sidebar-btn-group .btn').forEach(function(button) {
            button.addEventListener('click', function() {
                document.querySelectorAll('.sidebar-btn-group .btn').forEach(function(btn) {
                    btn.classList.remove('active');
                });
                button.classList.add('active');
                const mode = button.classList.contains('sidebar-dark') ? 'dark' : 'light';
                console.log(`Sidebar mode sélectionné : ${mode}`);
                document.querySelector('.left-side-bar').classList.toggle('sidebar-dark', mode === 'dark');
                document.querySelector('.left-side-bar').classList.toggle('sidebar-light', mode === 'light');
            });
        });

        // Gestion des changements de l'en-tête
        document.querySelectorAll('.header-btn-group .btn').forEach(function(button) {
            button.addEventListener('click', function() {
                document.querySelectorAll('.header-btn-group .btn').forEach(function(btn) {
                    btn.classList.remove('active');
                });
                button.classList.add('active');
                const mode = button.classList.contains('header-dark') ? 'dark' : 'light';
                console.log(`Header mode sélectionné : ${mode}`);
                document.querySelector('.header').classList.toggle('header-dark', mode === 'dark');
                document.querySelector('.header').classList.toggle('header-light', mode === 'light');
            });
        });

        // Réinitialiser les paramètres
        document.getElementById('reset-settings').addEventListener('click', function() {
            console.log('Réinitialisation des paramètres');
            document.querySelectorAll('.sidebar-btn-group .btn').forEach(function(btn) {
                btn.classList.remove('active');
            });
            document.querySelector('.sidebar-light').classList.add('active');
            document.querySelector('.left-side-bar').classList.remove('sidebar-dark');
            document.querySelector('.left-side-bar').classList.add('sidebar-light');

            document.querySelectorAll('.header-btn-group .btn').forEach(function(btn) {
                btn.classList.remove('active');
            });
            document.querySelector('.header-white').classList.add('active');
            document.querySelector('.header').classList.remove('header-dark');
            document.querySelector('.header').classList.add('header-white');
        });
    });
</script>
