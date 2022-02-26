<?php

namespace App\Http\Controllers\Admin;

use App\Models\Language;
use App\Models\LangFile;
use App\Http\Requests\LangFileRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class LangFileCrudController extends CrudController
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
        CRUD::setModel(\App\Models\LangFile::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/lang-file');
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
        CRUD::setValidation(LangFileRequest::class);

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
            if (count($langFile->langTranslations)) {
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
        return LangFile::find($id);
    }

    private function getLanguages()
    {
        return Language::where('active', 1)->orderBy('default', 'DESC')->get();
    }
}
