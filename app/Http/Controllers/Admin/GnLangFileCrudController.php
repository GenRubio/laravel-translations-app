<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GnLangFileRequest;
use App\Models\GnLangFile;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class GnLangFileCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\GnLangFile::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/gn-lang-file');
        CRUD::setEntityNameStrings('lang file', 'lang files');
    }

    protected function setupListOperation()
    {
        $this->crud->setColumns(
            [
                [
                    'name' => 'format_name',
                    'type' => 'text',
                    'label' => 'Format name'
                ],
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => 'File name'
                ]
            ]
        );

        //$this->crud->addButtonFromView('top', 'import-actual-lang-files', 'import-actual-lang-files', 'end');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(GnLangFileRequest::class);

        $this->crud->addFields(
            [
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => 'File name <br> <small>Auto format: input = Hello Word | result = hello_word</small>'
                ],
                [
                    'name' => 'format_name',
                    'type' => 'hidden',
                ]
            ]
        );

    }

    protected function setupUpdateOperation()
    {
        $this->crud->addFields(
            [
                [
                    'name' => 'format_name',
                    'type' => 'text',
                    'label' => 'Format name',
                    'attributes' => [
                        'readonly'  => 'readonly',
                    ]
                ]
            ]
        );
    }

    public function importFiles(){
        $gnFiles = $this->getAllGnLangFiles();
        $this->makeLaguagesDirectories();
    }

    private function makeLaguagesDirectories()
    {
        $folder = resource_path('lang');
        foreach ($this->getLanguages() as $lang) {
            $path = $folder . '/' . $lang;
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
        }
    }

    private function getAllGnLangFiles(){
        return GnLangFile::all();
    }

    private function getLanguages()
    {
        /**
         * Hacer la consulta a la tabla de lenguajes del proyecto devolver en array los lenguajes
         */
        return ['es', 'en', 'ru'];
    }
}
