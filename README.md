# Laravel translations
Sistema de traducciones para proyectos en Laravel con BackpackForLaravel.

## Funcionamiento 
---

## Archivos usados. Para migrar "Laravel translations" a otro proyecto

- database/migrations
```sh
2022_02_21_052106_create_lang_sections_table.php
2022_02_21_184932_create_lang_files_table.php
2022_02_22_084746_create_lang_translations_table.php
2022_02_22_190535_create_languages_table.php
```

- app/Http/Controllers/Admin
```sh
LangFileCrudController.php
LangSectionCrudController.php
LangTranslationCrudController.php
LanguageCrudController.php
```

- app/Http/Requests
```sh
LangFileRequest.php
LangSectionRequest.php
LangTranslationRequest.php
LanguageRequest.php
```

- app/Models
```sh
LangFile.php
LangSection.php
LangTranslation.php
Language.php
```

- resources/views/vendor/base/crud/buttons
```sh
copy-helper-trans.blade.php
make-transletable-file.blade.php
translate-all-files.blade.php
```

- resources/views/vendor/base/inc/sidebar_content.blade.php
```sh
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-globe"></i>
        {{ trans('translationsystem.translations_nav') }}</a>
    <ul class="nav-dropdown-items">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('language') }}'><i
                    class='nav-icon la la-flag-checkered'></i> {{ trans('translationsystem.languages_nav') }}</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('lang-file') }}'><i
                    class="nav-icon lar la-file-alt"></i> {{ trans('translationsystem.lang_files_nav') }}</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('lang-section') }}'><i
                    class="nav-icon las la-list"></i> {{ trans('translationsystem.lang_sections_nav') }}</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('lang-translation') }}'><i
                    class="nav-icon las la-language"></i> {{ trans('translationsystem.lang_texts_nav') }}</a></li>
    </ul>
</li>
```

- routes/backpack/custom.php
```sh
Route::crud('lang-translation', 'LangTranslationCrudController');
Route::crud('lang-file', 'LangFileCrudController');
Route::crud('lang-section', 'LangSectionCrudController');
Route::crud('language', 'LanguageCrudController');

Route::get('lang-translation/texts/{lang?}/{file?}', 'LangTranslationCrudController@showTexts');
Route::post('lang-translation/update-texts', 'LangTranslationCrudController@updateTexts');
Route::get('lang-translation/make-translations-file', 'LangTranslationCrudController@makeTransletableFile');
```

- resources/lang
```sh
/ca/translationsystem.php
/en/translationsystem.php
/es/translationsystem.php
/pt/translationsystem.php
```

Una vez copiados los archivos a otro proyecto ejecutamos el comando:
```sh
php artisan migrate
```
## License
© Copyright 20022-2099 Copyright.es - Todos los Derechos Reservados
