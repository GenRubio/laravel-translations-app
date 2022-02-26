<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LangSectionRequest;
use App\Models\LangSection;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class LangSectionCrudController extends CrudController
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
        CRUD::setModel(\App\Models\LangSection::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/lang-section');
        CRUD::setEntityNameStrings('lang section', 'lang sections');
    }

    protected function setupListOperation()
    {
        $this->crud->setColumns(
            [
                [
                    'name' => 'format_name',
                    'type' => 'text',
                    'label' => 'Format section'
                ],
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => 'Section name'
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
        CRUD::setValidation(LangSectionRequest::class);

        $this->crud->addFields(
            [
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => 'Section <br> <small>Auto format: input = Hello Word | result = hello_word</small>'
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
                    'label' => 'Format section',
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
        $section = $this->getGnSectionById($id);

        if ($section) {
            if (count($section->langTranslations)) {
                return \Alert::error('La sección tiene traducciones asignadas.');
            }
        } else {
            return \Alert::error('Ha ocurido un error.');
        }

        return $this->crud->delete($id);
    }

    private function getGnSectionById($id)
    {
        return LangSection::find($id);
    }
}
