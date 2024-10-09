<?php

use Illuminate\Support\Facades\Route;

Route::controller('\Bishopm\Church\Http\Controllers\HomeController')->group(function () {
    Route::get('/', 'home');
    Route::get('/blog/{year}/{month}/{slug}', 'blogpost');
    Route::get('/blog', 'blog');
    Route::get('/blog/{slug}', 'blogger');
    Route::get('/books/{id}', 'book');
    Route::get('/books', 'books');
    Route::get('/giving', 'giving');
    Route::get('/people/{slug}', 'person');
    Route::get('/projects/{id}', 'project');
    Route::get('/projects', 'projects');
    Route::get('/sermons', 'sermons');
    Route::get('/sermons/{slug}', 'preacher');
    Route::get('/sermons/{year}/{slug}', 'series');
    Route::get('/sermons/{year}/{slug}/{id}', 'sermon');
    Route::get('/subject/{slug}', 'subject');
});

Route::get('/admin/reports/group/{id}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@group','as' => 'reports.group']);
Route::get('/admin/reports/meeting/a4/{id}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@a4meeting','as' => 'reports.a4meeting']);
Route::get('/admin/reports/meeting/a5/{id}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@a5meeting','as' => 'reports.a5meeting']);
Route::get('/admin/reports/roster/{id}/{year}/{month}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@roster','as' => 'reports.roster']);
Route::get('/admin/reports/service/{id}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@service','as' => 'reports.service']);
Route::get('/admin/reports/song/{id}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@song','as' => 'reports.song']);
Route::get('/admin/reports/venue/{id}/{reportdate}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@venue','as' => 'reports.venue']);
