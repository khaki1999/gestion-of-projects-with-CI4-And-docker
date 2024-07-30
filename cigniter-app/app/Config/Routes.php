<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


// $routes->get('(:num)/edit', 'TaskController::edit/$1', ['as' => 'tasks.edit']);

// $routes->post('update/(:num)', 'TaskController::update/$1', ['as' => 'tasks.update']);
$routes->get('tasks/edit/(:num)', 'TaskController::edit/$1');
$routes->post('tasks/update/(:num)', 'TaskController::update/$1');
$routes->get('tasks/subtasks/(:num)', 'TaskController::getSubtasks/$1', ['as' => 'tasks.subtasks']);
//  $routes->post('subtasks/store', 'TaskController::storeSubtask');
$routes->get('tasks/subtasks/create/(:num)', 'TaskController::createSubtask/$1');
$routes->post('tasks/storeSubtask', 'TaskController::storeSubtask');
//users
$routes->get('users/edit(:num)', 'UserController::edit/$1');
$routes->post('users/update/(:num)', 'UserController::update/$1');








$routes->group('admin', function ($routes) {




    $routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {

        $routes->get('home', 'AdminController::index', ['as' => 'admin.home']);
        $routes->get('logout', 'AdminController::logoutHandler', ['as' => 'admin.logout']);
        $routes->get('project', 'AdminController::project', ['as' => 'project']);
        $routes->get('task', 'AdminController::task', ['as' => 'task']);

        //projets
        $routes->group('projects', function ($routes) {
            // $routes->get('projects', 'ProjectController::getAllProjects', ['as' => 'projects.list']);
            $routes->get('projects', 'ProjectController::getAllProjects', ['as' => 'projects.list']);
            // $routes->get('projects/create', 'ProjectController::create', ['as' => 'projects.create']);
            $routes->post('projects/store', 'ProjectController::store', ['as' => 'projects.store']);
            $routes->get('projects/(:num)/delete', 'ProjectController::delete/$1', ['as' => 'projects.delete']);
            // $routes->get('projects/(:num)', 'ProjectController::getProjectById/$1', ['as' => 'projects.view']);
            $routes->post('projects/update/(:num)', 'ProjectController::update/$1', ['as' => 'projects.update']);
            $routes->get('projects/(:num)/tasks', 'ProjectController::showTasks/$1', ['as' => 'projects.tasks']);
        });

        //task
        $routes->group('tasks', function ($routes) {
            $routes->get('/', 'TaskController::index', ['as' => 'tasks.list']);
            $routes->get('create', 'TaskController::create', ['as' => 'tasks.create']);
            $routes->post('store', 'TaskController::store', ['as' => 'tasks.store']);
            // $routes->get('(:num)/edit', 'TaskController::edit/$1', ['as' => 'tasks.edit']);
            // $routes->post('update/(:num)', 'TaskController::update/$1', ['as' => 'tasks.update']);
            // $routes->post('/tasks/update/$1', 'TaskController::update/$1');


            $routes->get('(:num)/delete', 'TaskController::delete/$1', ['as' => 'tasks.delete']);
            $routes->get('(:num)', 'TaskController::getTaskById/$1', ['as' => 'tasks.view']);
            $routes->get('taskbypro/(:num)', 'TaskController::getTasksByProject/$1', ['as' => 'tasks.byproject']);
            $routes->post('tasks/assign', 'TaskController::assignUser', ['as' => 'tasks.assign']);
            $routes->get('tasks/(:num)/users', 'TaskController::listUsers/$1', ['as' => 'tasks.users']);
            $routes->get('users/(:num)/tasks', 'TaskController::userTasks/$1', ['as' => 'user.tasks']);
            // $routes->post('tasks/storeSubtask', 'TaskController::storeSubtask', ['as' => 'tasks.storeSubtask']);
            $routes->get('tasks/subtasks/(:num)', 'TaskController::Subtasks/$1', ['as' => 'tasks.subtasks']);
            // $routes->get('tasks/(:num)/subtasks/create', 'TaskController::createSubtask/$1', ['as' => 'subtasks.create']);
            // $routes->post('subtasks/store', 'TaskController::storeSubtask', ['as' => 'subtasks.store']);
            $routes->post('subtasks/store', 'TaskController::storeSubtask');

            $routes->get('subtasks/(:num)/edit', 'TaskController::editSubtask/$1', ['as' => 'subtasks.edit']);
            $routes->post('subtasks/(:num)/update', 'TaskController::updateSubtask/$1', ['as' => 'subtasks.update']);
            $routes->get('subtasks/(:num)/delete', 'TaskController::deleteSubtask/$1', ['as' => 'subtasks.delete']);
            $routes->get('tasks/subtasks/(:num)', 'TaskController::getSubtasks/$1');

        });

        //users

        $routes->group('users', function ($routes) {
            $routes->get('/', 'UserController::index', ['as' => 'users.list']);
            $routes->get('create', 'UserController::create', ['as' => 'users.create']);
            $routes->post('store', 'UserController::store', ['as' => 'users.store']);
            // $routes->get('(:num)/edit', 'UserController::edit/$1', ['as' => 'users.edit']);
            // $routes->post('users/update/(:num)', 'UserController::update/$1', ['as' => 'users.update']);
            $routes->get('(:num)/delete', 'UserController::delete/$1', ['as' => 'users.delete']);
            $routes->get('(:num)', 'UserController::getUserById/$1', ['as' => 'users.view']);
            $routes->get('(:num)/tasks', 'UserController::getTasks/$1', ['as' => 'users.tasks']);
            $routes->put('(:num)/tasks/(:num)', 'UserController::assignTask/$1/$2', ['as' => 'users.assignTask']);
        });
        $routes->get('/swal-alert/showAlert/(:any)/(:any)', 'SwalController::showAlert/$1/$2');
        $routes->get('profil', 'AuthController::profile', ['as' => 'admin.profil']);
    });
    $routes->group('', ['filter' => 'cifilter:guest'], static function ($routes) {
        $routes->get('login', 'AuthController::loginForm', ['as' => 'admin.login.form']);
        $routes->post('login', 'AuthController::loginHandler', ['as' => 'admin.login.handler']);
        $routes->get('forget-password', 'AuthController::forgotForm', ['as' => 'admin.forgot.form']);
        $routes->post('send-password-reset-link', 'AuthController::sendPasswordResetLink', ['as' => 'send_password_reset_link']);
        $routes->get('password/reset/(:any)', 'AuthController::resetPassword/$1', ['as' => 'admin.reset-password']);
        $routes->get('register', 'AuthController::registerForm', ['as' => 'admin.register.form']);
        $routes->post('register', 'AuthController::registerHandler', ['as' => 'admin.register.handler']);
    });
});
