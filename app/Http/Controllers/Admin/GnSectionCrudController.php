<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GnSectionRequest;
use App\Models\GnSection;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class GnSectionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation {
        destroy as traitDestroy;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\GnSection::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/gn-section');
        CRUD::setEntityNameStrings('section', 'sections');
    }

    protected function setupListOperation()
    {
        $this->crud->setColumns(
            [
                [
                    'name' => 'format_section',
                    'type' => 'text',
                    'label' => 'Format section'
                ],
                [
                    'name' => 'section',
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
        CRUD::setValidation(GnSectionRequest::class);

        $this->crud->addFields(
            [
                [
                    'name' => 'section',
                    'type' => 'text',
                    'label' => 'Section <br> <small>Auto format: input = Hello Word | result = hello_word</small>'
                ],
                [
                    'name' => 'format_section',
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
                    'name' => 'format_section',
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
            if (count($section->translations)) {
                return \Alert::error('La sección tiene traducciones asignadas.');
            }
        } else {
            return \Alert::error('Ha ocurido un error.');
        }

        return $this->crud->delete($id);
    }

    private function getGnSectionById($id)
    {
        return GnSection::find($id);
    }
}
