<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/custom/liewire/update', $handle)->middleware(['web']);
});

Route::middleware(['web'])->controller('\Bishopm\Church\Http\Controllers\HomeController')->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/app', 'app')->name('app.home');
    Route::get('/app/details', 'details')->name('details');
    Route::get('/app/devotionals', 'devotionals')->name('devotionals');
    Route::get('/app/practices', 'practices')->name('practices');
    Route::get('/app/songs/', 'songs')->name('songs');
    Route::get('/app/songs/{id}', 'song')->name('song');
    Route::get('/login', 'login')->name('login');
    Route::get('/blog/{year}/{month}/{slug}/{mode?}', 'blogpost')->name('blogpost');
    Route::get('/blog/{mode?}', 'blog')->name('blog');
    Route::get('/blog/{slug}/{mode?}', 'blogger')->name('blogger');
    Route::get('/book/{id}/{mode?}', 'book')->name('book');
    Route::get('/books/{mode?}', 'books')->name('books');
    Route::get('/giving/{mode?}', 'giving')->name('giving');
    Route::get('/groups/{mode?}', 'groups')->name('groups');
    Route::get('/groups/{id}/{mode?}', 'group')->name('group');
    Route::get('/people/{slug}/{mode?}', 'person')->name('person');
    Route::get('/projects/{id}/{mode?}', 'project')->name('project');
    Route::get('/projects/{mode?}', 'projects')->name('projects');
    Route::get('/sermons/{mode?}', 'sermons')->name('sermons');
    Route::get('/preacher/{slug}/{mode?}', 'preacher')->name('preacher');
    Route::get('/sermons/{year}/{slug}/{mode?}', 'series')->name('series');
    Route::get('/sermon/{year}/{slug}/{id}/{mode?}', 'sermon')->name('sermon');
    Route::get('/subject/{slug}/{mode?}', 'subject')->name('subject');
});

Route::get('/admin/reports/group/{id}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@group','as' => 'reports.group']);
Route::get('/admin/reports/meeting/a4/{id}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@a4meeting','as' => 'reports.a4meeting']);
Route::get('/admin/reports/meeting/a5/{id}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@a5meeting','as' => 'reports.a5meeting']);
Route::get('/admin/reports/roster/{id}/{year}/{month}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@roster','as' => 'reports.roster']);
Route::get('/admin/reports/service/{id}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@service','as' => 'reports.service']);
Route::get('/admin/reports/song/{id}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@song','as' => 'reports.song']);
Route::get('/admin/reports/venue/{id}/{reportdate}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@venue','as' => 'reports.venue']);

