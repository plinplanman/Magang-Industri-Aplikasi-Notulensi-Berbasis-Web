<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

 //home
 $routes->get('/home', 'Home::index');
 //dashboard
$routes->get('/','DashboardController::index');
$routes->get('/dashboard','DashboardController::index');



//departemen
$routes->get('/departemen','DepartemenController::index',['filter' => 'auth']);
$routes->get('/departemens','DepartemenController::index',['filter' => 'auth']);
$routes->get('/departemens/create','DepartemenController::create');
$routes->post('/departemens/store','DepartemenController::store');
$routes->get('/departemens/edit/(:num)','DepartemenController::edit/$1');
$routes->post('/departemens/update/(:num)','DepartemenController::update/$1');
$routes->get('/departemens/delete/(:num)','DepartemenController::delete/$1');
// Routes for Departemen RESTful API
$routes->resource('departemens', ['filter' => 'auth']);

// //test
// $routes->get('/blog', 'Blog::json');

//admin
$routes->get('/admin','AdminController::index',['filter'=>'role:admin']);
$routes->get('/admin/index','AdminController::index',['filter'=>'role:admin']);
$routes->get('/admin/create','AdminController::create',['filter'=>'role:admin']);

$routes->get('/admin/detail/(:num)','AdminController::detail/$1',['filter'=>'role:admin']);

// user
// app/Config/Routes.php

$routes->get('user/create', 'UserController::create', ['filter' => 'auth']);
$routes->post('user/store', 'UserController::store', ['as' => 'user.store', 'filter' => 'auth']);
$routes->get('user/edit/(:num)', 'UserController::edit/$1', ['as' => 'user.edit', 'filter' => 'auth']);
$routes->post('user/update/(:num)', 'UserController::update/$1', ['as' => 'user.update', 'filter' => 'auth']);
$routes->put('user/update/(:num)', 'UserController::update/$1', ['as' => 'user.store', 'filter' => 'auth']);  // Menambahkan route untuk metode PUT
$routes->delete('user/delete/(:segment)', 'UserController::delete/$1'); // Tanpa filter auth untuk pengecekan
$routes->post('user/delete/(:segment)', 'UserController::delete/$1', ['as' => 'user.delete', 'filter' => 'auth']);

//Agenda 
$routes->get('/agenda', 'AgendaController::index'); // Route untuk menampilkan semua agenda
$routes->get('/agenda/create', 'AgendaController::create'); // Route untuk form pembuatan agenda
$routes->post('/agenda/store', 'AgendaController::store'); // Route untuk menyimpan agenda baru
$routes->get('/agenda/detail/(:num)', 'AgendaController::detail/$1'); // Route untuk menampilkan detail agenda berdasarkan ID
$routes->get('/agenda/edit/(:num)', 'AgendaController::edit/$1'); // Route untuk form edit agenda berdasarkan ID
$routes->post('/agenda/update/(:num)', 'AgendaController::update/$1'); // Route untuk update agenda berdasarkan ID
$routes->get('/agenda/delete/(:num)', 'AgendaController::delete/$1'); // Route untuk menghapus agenda berdasarkan ID


// Routes untuk AgendaUserController
$routes->get('agenda/personil/create', 'AgendaUserController::create');
$routes->post('/agenda/personil/store', 'AgendaUserController::store');
$routes->get('agenda/personil/edit/(:num)', 'AgendaUserController::edit/$1');
$routes->post('/agenda/personil/update/(:num)', 'AgendaUserController::update/$1');
$routes->get('agenda/personil/delete/(:num)', 'AgendaUserController::delete/$1');

//calendar
$routes->get('/calendar', 'EventController::index'); // Route ke tampilan kalender
$routes->get('/events', 'EventController::getEvents'); // Route untuk data JSON agenda

// Notulen
$routes->get('/notulen', 'NotulenController::index');
$routes->get('/notulen/create', 'NotulenController::create');
$routes->post('/notulen/store', 'NotulenController::store');
$routes->get('/notulen/edit/(:num)', 'NotulenController::edit/$1');
$routes->post('/notulen/update/(:num)', 'NotulenController::update/$1');
$routes->get('/notulen/delete/(:num)', 'NotulenController::delete/$1');
$routes->get('notulen/detail/(:num)', 'NotulenController::detail/$1');
 

//notifikasi
$routes->get('/notifikasi/kirimReminder', 'NotifikasiController::kirimReminder');

//dokumentasinotulen
$routes->get('notulen/dokumentasi/create/(:num)', 'DokumentasiNotulenController::create/$1');
$routes->post('notulen/dokumentasi/store', 'DokumentasiNotulenController::store');
$routes->get('notulen/dokumentasi/edit/(:num)', 'DokumentasiNotulenController::edit/$1');
$routes->post('notulen/dokumentasi/update/(:num)', 'DokumentasiNotulenController::update/$1');
$routes->get('notulen/dokumentasi/delete/(:num)', 'DokumentasiNotulenController::delete/$1');


//tanda tangan 
$routes->get('/notulen/tandatangan/create/(:num)', 'TandatanganNotulenController::create/$1');
$routes->post('/notulen/tandatangan/store', 'TandatanganNotulenController::store');
$routes->get('/notulen/tandatangan/edit/(:num)', 'TandatanganNotulenController::edit/$1');
$routes->post('/notulen/tandatangan/update/(:num)', 'TandatanganNotulenController::update/$1');
$routes->get('/notulen/tandatangan/delete/(:num)', 'TandatanganNotulenController::delete/$1');

//API Route 
//admin
$routes->get('/api/admin', 'AdminController::getUsersJson');
//agenda
$routes->get('api/agendas', 'AgendaController::getAllApi');
$routes->post('api/agendas/', 'AgendaController::storeApi');
$routes->get('api/agendas/(:num)', 'AgendaController::showApi/$1');
$routes->put('api/agendas/(:num)', 'AgendaController::updateApi/$1');
$routes->delete('api/agendas/(:num)', 'AgendaController::deleteApi/$1');

//AgendaUser
$routes->get('api/agenda/(:num)/personil', 'AgendaUserController::getPersonilByAgenda/$1');
$routes->post('api/agenda/personil', 'AgendaUserController::createPersonilAPI');
$routes->delete('api/agenda/personil/(:num)', 'AgendaUserController::deletePersonilAPI/$1');

//calendar //Event Controller
$routes->get('/api/calendar','EventController::getEvents');

//dashboard
$routes->get('/api/dashboard', 'DashboardController::getAgendasJson');
//departemen
$routes->get('api/departemen', 'DepartemenController::getAll');
$routes->post('api/departemen/', 'DepartemenController::storeApi');
$routes->get('api/departemen/(:num)', 'DepartemenController::showApi/$1');
$routes->put('api/departemen/(:num)', 'DepartemenController::updateApi/$1');
$routes->delete('api/departemen/(:num)', 'DepartemenController::deleteApi/$1');

//notulen
$routes->get('api/notulen','NotulenController::getAllApi');
$routes->get('api/notulen/(:num)','NotulenController::showApi/$1');
$routes->post('api/notulen/','NotulenController::storeApi');
$routes->put('api/notulen/(:num)','NotulenController::updateApi/$1');
$routes->delete('api/notulen/(:num)','NotulenController::deleteApi/$1');

//notulen dokumentasi
$routes->get('api/notulen/dokumentasi/(:num)','DokumentasiNotulenController::getAllApi/$1');
$routes->get('api/notulen/dokumentasi/gambar/(:num)','DokumentasiNotulenController::showApi/$1');
$routes->post('api/notulen/dokumentasi/','DokumentasiNotulenController::storeApi');
$routes->put('api/notulen/dokumentasi/(:num)','DokumentasiNotulenController::updateApi/$1');
$routes->delete('api/notulen/dokumentasi/(:num)','DokumentasiNotulenController::deleteApi/$1');

//notulen tanda tangan
$routes->get('api/notulen/tandatangan/(:num)','TandatanganNotulenController::getAllApi/$1');
$routes->get('api/notulen/tandatangan/gambar/(:num)','TandatanganNotulenController::showApi/$1');
$routes->post('api/notulen/tandatangan/','TandatanganNotulenController::storeApi');
$routes->put('api/notulen/tandatangan/(:num)','TandatanganNotulenController::updateApi/$1');
$routes->delete('api/notulen/tandatangan/(:num)','TandatanganNotulenController::deleteApi/$1');

//notifikasi agenda
$routes->get('api/notifikasi','NotifikasiNotulenController::getNotifikasiApi');
//notifikasi lewat email
$routes->post('api/notifikasi/reminder', 'NotifikasiController::kirimReminderApi');

// login register
$routes->post('/api/login', '\Myth\Auth\Controllers\AuthController::apiLogin');
$routes->post('/api/logout', '\Myth\Auth\Controllers\AuthController::apiLogout');
$routes->post('api/login/token', 'AuthApiController::loginWithToken');


//users
$routes->get('api/users', 'UserController::getAll');
$routes->post('api/users/', 'UserController::createApi');
$routes->get('api/users/(:num)', 'UserController::showApi/$1');
$routes->put('api/users/(:num)', 'UserController::updateApi/$1');
$routes->delete('api/users/(:num)', 'UserController::deleteApi/$1');



