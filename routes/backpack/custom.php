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
    Route::crud('lang-translation', 'LangTranslationCrudController');
    Route::crud('lang-file', 'LangFileCrudController');
    Route::crud('lang-section', 'LangSectionCrudController');
    Route::crud('language', 'LanguageCrudController');

    Route::get('lang-translation/export-all', 'LangTranslationCrudController@exportAllTexts');
    Route::post('lang-translation/export-by-condition', 'LangTranslationCrudController@exportByConditions');
    Route::get('lang-translation/texts/{lang?}/{file?}', 'LangTranslationCrudController@showTexts');
    Route::post('lang-translation/update-texts', 'LangTranslationCrudController@updateTexts');
    Route::get('lang-translation/make-translations-file', 'LangTranslationCrudController@makeTransletableFile');
}); // this should be the absolute last line of this file