<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/custom/livewire/update', $handle)
        ->middleware(['web']);
});

Route::middleware(['web'])->controller('\Bishopm\Church\Http\Controllers\HomeController')->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/login', 'login')->name('login');
    Route::get('/blog/{year}/{month}/{slug}', 'blogpost')->name('blogpost');
    Route::get('/blog', 'blog')->name('blog');
    Route::get('/blog/{slug}', 'blogger')->name('blogger');
    Route::get('/books/{id}', 'book')->name('book');
    Route::get('/books', 'books')->name('books');
    Route::get('/giving', 'giving')->name('giving');
    Route::get('/groups', 'groups')->name('groups');
    Route::get('/groups/{id}', 'group')->name('group');
    Route::get('/mymenu', 'mymenu')->name('mymenu');
    Route::get('/people/{slug}', 'person')->name('person');
    Route::get('/projects/{id}', 'project')->name('project');
    Route::get('/projects', 'projects')->name('projects');
    Route::get('/sermons', 'sermons')->name('sermons');
    Route::get('/sermons/{slug}', 'preacher')->name('preacher');
    Route::get('/sermons/{year}/{slug}', 'series')->name('series');
    Route::get('/sermons/{year}/{slug}/{id}', 'sermon')->name('sermon');
    Route::get('/subject/{slug}', 'subject')->name('subject');
});

Route::get('/admin/reports/group/{id}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@group','as' => 'reports.group']);
Route::get('/admin/reports/meeting/a4/{id}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@a4meeting','as' => 'reports.a4meeting']);
Route::get('/admin/reports/meeting/a5/{id}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@a5meeting','as' => 'reports.a5meeting']);
Route::get('/admin/reports/roster/{id}/{year}/{month}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@roster','as' => 'reports.roster']);
Route::get('/admin/reports/service/{id}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@service','as' => 'reports.service']);
Route::get('/admin/reports/song/{id}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@song','as' => 'reports.song']);
Route::get('/admin/reports/venue/{id}/{reportdate}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@venue','as' => 'reports.venue']);
