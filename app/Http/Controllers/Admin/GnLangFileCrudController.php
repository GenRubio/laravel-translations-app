<?php

namespace App\Http\Controllers\Admin;

use App\Models\Language;
use App\Models\GnLangFile;
use App\Http\Requests\GnLangFileRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class GnLangFileCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation {
        destroy as traitDestroy;
    }

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

        $this->setShowNumberRows();
    }

    private function setShowNumberRows(){
        $this->crud->setDefaultPageLength(100);
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

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $langFile = $this->getGnLangFileById($id);

        if ($langFile) {
            if (count($langFile->translations)) {
                return \Alert::error('La archivo tiene traducciones asignadas.');
            }
        } else {
            return \Alert::error('Ha ocurido un error.');
        }
        
        $this->removeLangFile($langFile->format_name);
        return $this->crud->delete($id);
    }

    private function removeLangFile($name){
        $folder = resource_path('lang');
        foreach ($this->getLanguages() as $lang) {
            $path = $folder . '/' . $lang->abbr;
            if (!is_dir($path)) {
                unlink($path . '/' . $name . '.php');
            }
        }
    }

    private function getGnLangFileById($id){
        return GnLangFile::find($id);
    }

    private function getLanguages()
    {
        return Language::where('active', 1)->orderBy('default', 'DESC')->get();
    }
}
