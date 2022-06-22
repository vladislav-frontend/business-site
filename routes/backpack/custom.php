<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('category', 'CategoryCrudController');
    Route::crud('post', 'PostCrudController');
    Route::crud('language', 'LanguageCrudController');
    Route::crud('tag', 'TagCrudController');
    Route::crud('review', 'ReviewCrudController');
    Route::crud('user', 'UserCrudController');
    Route::crud('about-us', 'AboutUsCrudController');
    Route::crud('vacation', 'VacationCrudController');
    Route::crud('settings', 'SettingsCrudController');
    Route::crud('contacts', 'ContactsCrudController');
    Route::crud('home', 'HomeCrudController');
});