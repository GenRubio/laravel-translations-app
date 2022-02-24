<?php

namespace App\Http\Controllers\Admin;

use App\Models\Language;
use App\Http\Requests\LanguageRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class LanguageCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation {
        destroy as traitDestroy;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\Language::class);
        $this->crud->setRoute(config('backpack.base.route_prefix', 'admin') . '/language');
        $this->crud->setEntityNameStrings('idiomas', 'idiomas');
    }

    public function setupListOperation()
    {
        $this->crud->setColumns([
            [
                'name' => 'name',
                'label' => 'Nombre idioma',
            ],
            [
                'name' => 'active',
                'label' => 'Activo',
                'type' => 'boolean',
            ],
            [
                'name' => 'default',
                'label' => 'Predeterminado',
                'type' => 'boolean',
            ],
        ]);
    }

    public function setupCreateOperation()
    {
        $this->crud->setValidation(LanguageRequest::class);
        $this->crud->addField([
            'name' => 'name',
            'label' => 'Nombre idioma',
            'type' => 'text',
        ]);
        $this->crud->addField([
            'name' => 'native',
            'label' => 'Nombre nativo',
            'type' => 'text',
        ]);
        $this->crud->addField([
            'name' => 'abbr',
            'label' => 'Code (ISO 639-1)',
            'type' => 'text',
        ]);
        $this->crud->addField([
            'name' => 'flag',
            'label' => 'Imagen Bandera',
            'type' => 'browse',
        ]);
        $this->crud->addField([
            'name' => 'active',
            'label' => 'Activo',
            'type' => 'checkbox',
        ]);
        $this->crud->addField([
            'name' => 'default',
            'label' => 'Predeterminado',
            'type' => 'checkbox',
        ]);
    }

    public function setupUpdateOperation()
    {
        return $this->setupCreateOperation();
    }

    public function store()
    {
        $defaultLang = Language::where('default', 1)->first();
        if ($defaultLang) {
            // Copy the default language folder to the new language folder
            \File::copyDirectory(resource_path('lang/' . $defaultLang->abbr), resource_path('lang/' . request()->input('abbr')));
        }

        return $this->traitStore();
    }

    /**
     * After delete remove also the language folder.
     *
     * @param int $id
     *
     * @return string
     */
    public function destroy($id)
    {
        $language = Language::find($id);
        $destroyResult = $this->traitDestroy($id);

        if ($destroyResult) {
            \File::deleteDirectory(resource_path('lang/' . $language->abbr));
        }

        return $destroyResult;
    }
}
