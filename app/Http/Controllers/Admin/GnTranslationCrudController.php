<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GnTranslationRequest;
use App\Models\GnSection;
use App\Models\GnTranslation;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class GnTranslationCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as traitUpdate;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation {
        destroy as traitDestroy;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\GnTranslation::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/gn-translation');
        CRUD::setEntityNameStrings('text', 'texts');
    }

    protected function setupListOperation()
    {
        $this->crud->setColumns(
            [
                [
                    'name' => 'helper',
                    'type' => 'text',
                    'label' => 'Helper'
                ],
                [
                    'name' => 'section',
                    'type' => 'relationship',
                    'label' => 'Section',
                    'attribute' => 'format_section',
                ],
                [
                    'name' => 'format_key',
                    'type' => 'text',
                    'label' => 'Format key'
                ],
                [
                    'name' => 'key',
                    'type' => 'text',
                    'label' => 'Key'
                ],
                [
                    'name' => 'value',
                    'type' => 'text',
                    'label' => 'Value'
                ]
            ]
        );

        $this->crud->addButtonFromView('top', 'make-transletable-file', 'make-transletable-file', 'end');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(GnTranslationRequest::class);

        $this->crud->addFields(
            [
                [
                    'name' => 'key',
                    'type' => 'text',
                    'label' => 'Key <br> <small>Auto format: input = Hello Word | result = hello_word</small>'
                ],
                [
                    'name' => 'format_key',
                    'type' => 'hidden',
                ],
                [
                    'label'     => "Section",
                    'type'      => 'select2',
                    'name'      => 'section_id',
                    'entity'    => 'section',
                    'model'     => "App\Models\GnSection",
                    'attribute' => 'section',
                ],
                [
                    'name' => 'value',
                    'type' => 'textarea',
                    'label' => 'Value'
                ]
            ]
        );
    }

    protected function setupUpdateOperation()
    {
        $this->crud->addFields(
            [
                [
                    'name' => 'format_key',
                    'type' => 'text',
                    'label' => 'Format Key',
                    'attributes' => [
                        'readonly'  => 'readonly',
                    ],
                ],
                [
                    'name' => 'helper',
                    'type' => 'text',
                    'label' => 'Helper',
                    'attributes' => [
                        'readonly'  => 'readonly',
                    ],
                ],
                [
                    'name' => 'value',
                    'type' => 'textarea',
                    'label' => 'Value',
                    'attributes' => [
                        'required'  => 'required',
                    ],
                ]
            ]
        );
    }

    public function store()
    {
        $response = $this->traitStore();
        $this->makeLaguagesDirectories();
        return $response;
    }

    public function update()
    {
        $response = $this->traitUpdate();
        $this->makeLaguagesDirectories();
        return $response;
    }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $response = $this->crud->delete($id);
        $this->makeLaguagesDirectories();
        return $response;
    }

    public function makeTransletableFile()
    {
        $this->makeLaguagesDirectories();
        \Alert::add('success', 'El archivo de traducción se ha actualizado correctamente.')->flash();
        return redirect()->back();
    }

    private function makeLaguagesDirectories()
    {
        $folder = resource_path('lang');

        foreach ($this->getLanguages() as $lang) {
            $path = $folder . '/' . $lang;
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
            $this->makeOrUpdateLaguagesFile($path, $lang);
        }
    }

    private function makeOrUpdateLaguagesFile($path, $lang)
    {
        $fullPath = $path . '/trans.php';

        $oldLocale = app()->getLocale();
        app()->setLocale($lang);
        \File::put($fullPath, $this->getFileContent());
        app()->setLocale($oldLocale);
    }

    private function getFileContent()
    {
        $header = "<?php \n\n";
        $returnLine = "return [\n\n";
        $content = "";
        foreach ($this->getTextsWithoutSection() as $text) {
            $content .= "  '" . $text->format_key . "' => '" . $this->refactorValue($text->value) . "',\n";
        }
        foreach ($this->getAllSections() as $section) {
            $content .= "  '" . $section->format_section . "' => [\n";
            foreach ($section->translations as $translation) {
                $content .= "   '" . $translation->format_key . "' => '" . $this->refactorValue($translation->value) . "',\n";
            }
            $content .= "  ],\n";
        }
        $content .= "\n];";
        return $header . $returnLine . $content;
    }

    private function refactorValue($value)
    {
        return str_replace("'", "\'", $value);
    }

    private function getTextsWithoutSection()
    {
        return GnTranslation::where('section_id', null)->get();
    }

    private function getAllSections()
    {
        return GnSection::all();
    }

    private function getLanguages()
    {
        /**
         * Hacer la consulta a la tabla de lenguajes del proyecto devolver en array los lenguajes
         */
        return ['es', 'en', 'ru'];
    }
}
