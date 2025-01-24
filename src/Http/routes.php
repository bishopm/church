<?php

use Bishopm\Church\Http\Middleware\CheckLogin;
use Illuminate\Support\Facades\Route;

$url=substr(env('APP_URL'),3+strpos(env('APP_URL'),':'));

// Livewire::setUpdateRoute(function ($handle) {
//    return Route::post('/custom/livewire/update', $handle)->middleware(['web']);
// });

// Admin routes
Route::get('/admin/reports/venues/{reportdate?}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@allvenues','as' => 'reports.allvenues']);
Route::get('/admin/reports/barcodes/{newonly?}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@barcodes','as' => 'reports.barcodes']);
Route::get('/admin/reports/calendar/{yr?}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@calendar','as' => 'reports.calendar']);
Route::get('/admin/reports/group/{id}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@group','as' => 'reports.group']);
Route::get('/admin/reports/meeting/a4/{id}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@a4meeting','as' => 'reports.a4meeting']);
Route::get('/admin/reports/meeting/a5/{id}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@a5meeting','as' => 'reports.a5meeting']);
Route::get('/admin/reports/minutes/{id}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@minutes','as' => 'reports.minutes']);
Route::get('/admin/reports/removenames', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@removenames','as' => 'reports.removenames']);
Route::get('/admin/reports/roster/{id}/{year}/{month}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@roster','as' => 'reports.roster']);
Route::get('/admin/reports/service/{id}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@service','as' => 'reports.service']);
Route::get('/admin/reports/song/{id}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@song','as' => 'reports.song']);
Route::get('/admin/reports/venue/{id}/{reportdate}', ['uses' => '\Bishopm\Church\Http\Controllers\ReportsController@venue','as' => 'reports.venue']);

// Website routes
Route::domain($url)->group(function() {
    Route::middleware(['web'])->controller('\Bishopm\Church\Http\Controllers\HomeController')->group(function () {
        Route::get('/', 'home')->name('web.home');
        Route::post('/', 'home')->name('web.home');
        Route::get('/blog/{year}/{month}/{slug}', 'blogpost')->name('web.blogpost');
        Route::get('/blog', 'blog')->name('web.blog');
        Route::get('/blog/{slug}', 'blogger')->name('web.blogger');
        Route::get('/groups', 'groups')->name('web.groups');
        Route::get('/groups/{id}', 'group')->name('web.group');
        Route::get('/offline', 'offline')->name('web.offline');
        Route::get('/people/{slug}', 'person')->name('web.person');
        Route::get('/preacher/{slug}', 'preacher')->name('web.preacher');
        Route::get('/projects/{id}', 'project')->name('web.project');
        Route::get('/projects', 'projects')->name('web.projects');
        Route::get('/quietmoments', 'quietmoments')->name('web.quietmoments');
        Route::get('/rosters/{slug}', 'roster')->name('web.roster');
        Route::get('/sermons', 'sermons')->name('web.sermons');
        Route::get('/sermons/{year}/{slug}', 'series')->name('web.series');
        Route::get('/sermon/{year}/{slug}/{id}', 'sermon')->name('web.sermon');
        Route::get('/stayingconnected', 'stayingconnected')->name('web.stayingconnected');
        Route::get('/subject/{slug}', 'subject')->name('web.subject');
        Route::get('/sundaydetails', 'sunday')->name('web.sunday');
        if (substr(str_replace(env('APP_URL'),'',url()->current()),1)<>"admin"){
            Route::get('/{page}', 'page')->name('web.page');
        }
    });
});

// App routes
Route::domain('app.' . $url)->group(function() {
    Route::middleware(['web',CheckLogin::class])->controller('\Bishopm\Church\Http\Controllers\HomeController')->group(function () {
        Route::get('/', 'app')->name('app.home');
        Route::get('/blog/{year}/{month}/{slug}', 'blogpost')->name('app.blogpost');
        Route::get('/blog', 'blog')->name('app.blog');
        Route::get('/blog/{slug}', 'blogger')->name('app.blogger');
        Route::get('/books/{id}', 'book')->name('app.book');
        Route::get('/books', 'books')->name('app.books');
        Route::get('/calendar/{full?}', 'calendar')->name('app.calendar');
        Route::get('/details', 'details')->name('app.details');
        Route::get('/devotionals', 'devotionals')->name('app.devotionals');
        Route::get('/groups', 'groups')->name('app.groups');
        Route::get('/groups/{id}', 'group')->name('app.group');
        Route::get('/login', 'login')->name('app.login');
        Route::get('/offline', 'offline')->name('app.offline');
        Route::get('/practices', 'practices')->name('app.practices');
        Route::get('/people/{slug}', 'person')->name('app.person');
        Route::get('/projects/{id}', 'project')->name('app.project');
        Route::get('/projects', 'projects')->name('app.projects');
        Route::get('/pastoral', 'pastoral')->name('app.pastoral');
        Route::get('/preacher/{slug}', 'preacher')->name('app.preacher');
        Route::get('/sermons', 'sermons')->name('app.sermons');
        Route::get('/sermons/{year}/{slug}', 'series')->name('app.series');
        Route::get('/sermon/{year}/{slug}/{id}', 'sermon')->name('app.sermon');
        Route::get('/songs', 'songs')->name('app.songs');
        Route::get('/songs/{id}', 'song')->name('app.song');
        Route::get('/subject/{slug}', 'subject')->name('app.subject');
    });
});
