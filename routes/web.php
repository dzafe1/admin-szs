<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::post('/login', 'Auth\LoginController@login');
Route::get('/login', 'Auth\LoginController@showLoginForm');

Auth::routes();

// Dodaje protekciju na rute samo za logovane korisnike
Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard','DashboardController@index');
    Route::get('/','DashboardController@index');
    Route::get('/home','DashboardController@index');

    Route::get('/prijave', function(){
        return view('prijave');
    });

    Route::get('/unauth/priajve', function(){
        return view('uprijave');
    });

    // Vijesti
    Route::get('/news', 'VijestiController@index');
    Route::get('/unauth/news', 'VijestiController@indexWaitingApproval');

    // Korsnici
    Route::get('/users', 'KorisniciController@index');

    // Players
    Route::get('/sportisti', 'PlayerController@approvedPlayersList');
    Route::get('/unauth/sportisti', 'PlayerController@notApprovedPlayersList');

    // Objects
    Route::get('/objekti', 'ObjectController@approvedObjectsList');
    Route::get('/unauth/objekti', 'ObjectController@notApprovedObjectsList');

    // Clubs
    Route::get('/klubovi', 'ClubController@approvedClubsList');
    Route::get('/unauth/klubovi', 'ClubController@notApprovedClubsList');

    // Schools
    Route::get('/skole', 'SchoolController@approvedSchoolsList');
    Route::get('/unauth/skole', 'SchoolController@notApprovedSchoolsList');

    // Staff
    Route::get('/kadrovi', 'StaffController@approvedStaffList');
    Route::get('/unauth/kadrovi', 'StaffController@notApprovedStaffList');

    // Ajax API rute
    Route::prefix('api')->group(function () {
        // Vijesti API routes
        Route::get('news/{id}', 'VijestiController@getNewsById');
        Route::patch('news/approve', 'VijestiController@approveNews');
        Route::patch('news/delete', 'VijestiController@deleteNews');
        Route::put('news/{id}/edit', 'VijestiController@editNews');

        // Korisnici API routes
        Route::get('users/{id}', 'KorisniciController@getUserById');
        Route::patch('users/delete', 'KorisniciController@deleteUser');
        Route::put('users/{id}/edit', 'KorisniciController@editUser');

        // Players API routes
        Route::get('players/{id}', 'PlayerController@getPlayerById');
        Route::get('players/editForm/{id}', 'PlayerController@getPlayerEditForm');
        Route::patch('players/approve', 'PlayerController@approvePlayer');
        Route::patch('players/delete', 'PlayerController@deletePlayer');
        Route::patch('players/refuse', 'PlayerController@refusePlayer');
        Route::patch('players/{id}/edit/general', 'PlayerController@editPlayerGeneral');
        Route::patch('players/{id}/edit/status', 'PlayerController@editPlayerStatus');
        Route::patch('players/{id}/edit/biography', 'PlayerController@editPlayerBiography');
        Route::patch('players/{id}/edit/trophies', 'PlayerController@editPlayerTrophies');
        Route::patch('players/{id}/edit/gallery', 'PlayerController@editPlayerGallery');

        // Objects API routes
        Route::get('objects/{id}', 'ObjectController@getObjectById');
        Route::get('objects/editForm/{id}', 'ObjectController@getObjectEditForm');
        Route::patch('objects/{id}/edit/general', 'ObjectController@editObjectGeneral');
        Route::patch('objects/{id}/edit/status', 'ObjectController@editObjectStatus');
        Route::patch('objects/{id}/edit/history', 'ObjectController@editObjectHistory');
        Route::patch('objects/{id}/edit/balon-fields', 'ObjectController@editObjectBalonFields');
        Route::patch('objects/{id}/edit/balon-prices', 'ObjectController@editObjectBalonPrices');
        Route::patch('objects/{id}/edit/ski-tracks', 'ObjectController@editObjectSkiTracks');
        Route::patch('objects/{id}/edit/ski-prices', 'ObjectController@editObjectSkiPrices');
        Route::patch('objects/{id}/edit/gallery', 'ObjectController@editObjectGallery');
        Route::patch('objects/approve', 'ObjectController@approveObject');
        Route::patch('objects/delete', 'ObjectController@deleteObject');
        Route::patch('objects/refuse', 'ObjectController@refuseObject');

        // Clubs API routes
        Route::get('clubs/{id}', 'ClubController@getClubById');
        Route::get('clubs/editForm/{id}', 'ClubController@getClubEditForm');
        Route::patch('clubs/{id}/edit/general', 'ClubController@editClubGeneral');
        Route::patch('clubs/{id}/edit/trophies', 'ClubController@editClubTrophies');
        Route::patch('clubs/{id}/edit/history', 'ClubController@editClubHistory');
        Route::patch('clubs/{id}/edit/staff', 'ClubController@editClubStaff');
        Route::patch('clubs/{id}/edit/gallery', 'ClubController@editClubGallery');
        Route::patch('clubs/approve', 'ClubController@approveClub');
        Route::patch('clubs/delete', 'ClubController@deleteClub');
        Route::patch('clubs/refuse', 'ClubController@refuseClub');
    });


});
