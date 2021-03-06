<?php

namespace App\Providers;

use App\Models\LangFile;
use App\Models\LangSection;
use App\Models\Language;
use App\Observers\LangFileObserver;
use App\Observers\LangSectionObserver;
use App\Observers\LanguageObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        LangFile::observe(LangFileObserver::class);
        LangSection::observe(LangSectionObserver::class);
        Language::observe(LanguageObserver::class);
    }
}
