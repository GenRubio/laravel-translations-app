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
    Route::prefix('gn-translation')->group(function () {
        Route::crud('/', 'GnTranslationCrudController');
        Route::get('/make-translations-file', 'GnTranslationCrudController@makeTransletableFile');
    });
    Route::crud('gn-section', 'GnSectionCrudController');
    Route::prefix('gn-lang-file')->group(function () {
        Route::crud('/', 'GnLangFileCrudController');
        Route::get('/import-actual-lang-files', 'GnLangFileCrudController@importFiles');
    });
}); // this should be the absolute last line of this file