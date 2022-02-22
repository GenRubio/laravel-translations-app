# Laravel translations
Sistema de traducciones para proyectos en Laravel con BackpackForLaravel.

## Funcionamiento 
El sistema está compuesto por 2 modelos GnSection y GnTranslation:

- GnSection: se usa para especificar la sección a la cual pertenece una traducción.
- GnTranslation: Contiene el identificador y el valor de la traducción.

Al crear, eliminar o actualizar una GnTranslation se nos crearan las carpetas de los lenguajes que utiliza nuestra aplicación en la carpeta lang 
del resource en el caso de que no existan.

Dentro de las carpetas se creará nuestro archivo trans.php que se sobreescribirá al hacer cambio en algún GnTranslation.

Para cada lenguaje se crea un archivo con diferente contenido basándose en la traducción del campo value de cada GnTranslation.

## Archivos usados. Para migrar "Laravel translations" a otro proyecto

- database/migrations
```sh
2022_02_21_052106_create_gn_sections_table.php
2022_02_22_084746_create_gn_translations_table.php
```

- app/Http/Controllers/Admin
```sh
GnSectionCrudController.php
GnTranslationCrudController.php
```

- app/Http/Requests
```sh
GnSectionRequest.php
GnTranslationRequest.php
```

- app/Models
```sh
GnSection.php
GnTranslation.php
```

- resources/views/vendor/base/inc/sidebar_content.blade.php
```sh
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="las la-language"></i> Translations</a>
    <ul class="nav-dropdown-items">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('gn-translation') }}'><i class="las la-file-alt"></i> Texts</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('gn-section') }}'><i class="las la-list"></i> Sections</a></li>
    </ul>
</li>
```

- routes/backpack/custom.php
```sh
Route::prefix('gn-translation')->group(function () {
    Route::crud('/', 'GnTranslationCrudController');
    Route::get('/make-translations-file', 'GnTranslationCrudController@makeTransletableFile');
});
Route::crud('gn-section', 'GnSectionCrudController');
```

Una vez copiados los archivos a otro proyecto ejecutamos el comando:
```sh
php artisan migrate
```
## License
© Copyright 20022-2099 Copyright.es - Todos los Derechos Reservados
