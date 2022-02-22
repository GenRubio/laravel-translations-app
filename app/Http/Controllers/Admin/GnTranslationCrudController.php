<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GnTranslationRequest;
use App\Models\GnLangFile;
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
        CRUD::setEntityNameStrings('lang text', 'lang texts');
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
                    'name' => 'file',
                    'type' => 'relationship',
                    'label' => 'Lang File',
                    'attribute' => 'format_name',
                ],
                [
                    'name' => 'section',
                    'type' => 'relationship',
                    'label' => 'Lang Section',
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
                    'label'     => "Lang File",
                    'type'      => 'select2',
                    'name'      => 'gn_lang_file_id',
                    'entity'    => 'file',
                    'model'     => "App\Models\GnLangFile",
                    'attribute' => 'name',
                ],
                [
                    'label'     => "Lang Section",
                    'type'      => 'select2',
                    'name'      => 'gn_section_id',
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
        $languages = $this->getLanguages();
        $this->createFolders($folder, $languages);
        $this->createLangFiles($folder, $languages);
    }

    private function createLangFiles($folder, $languages)
    {
        $languagesFiles = $this->getAllLangFiles();
        foreach ($languages as $lang) {
            $path = $folder . '/' . $lang;
            foreach ($languagesFiles as $languagesFile) {
                $this->makeOrUpdateLaguagesFile($path, $lang, $languagesFile);
            }
        }
    }

    private function createFolders($folder, $languages)
    {
        foreach ($languages as $lang) {
            $path = $folder . '/' . $lang;
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
        }
    }

    private function makeOrUpdateLaguagesFile($path, $lang, $languagesFile)
    {
        $fullPath = $path . '/' . $languagesFile->format_name . '.php';

        $oldLocale = app()->getLocale();
        app()->setLocale($lang);
        \File::put($fullPath, $this->getFileContent($languagesFile));
        app()->setLocale($oldLocale);
    }

    private function getFileContent($languagesFile)
    {
        $translations = $this->getLaguageFileTexts($languagesFile);
        $sectionIds = $this->getFileTextsSectionsIds($languagesFile);
        $sections = $this->getSectionsByIds($sectionIds);

        $header = "<?php \n\n";
        $returnLine = "return [\n\n";
        $content = "";

        foreach ($this->getTranslationsBySection($translations, null) as $text) {
            $content .= "  '" . $text->format_key . "' => '" . $this->refactorValue($text->value) . "',\n";
        }

        foreach ($sections as $section) {
            $content .= "  '" . $section->format_section . "' => [\n";
            foreach ($this->getTranslationsBySection($translations, $section->id) as $translation) {
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

    private function getTranslationsBySection($translations, $section){
        return $translations->filter(function($item) use ($section) {
            return $item->gn_section_id == $section;
        })->all();
    }

    private function getAllLangFiles()
    {
        return GnLangFile::all();
    }

    private function getLaguageFileTexts($languagesFile)
    {
        return GnTranslation::where('gn_lang_file_id', $languagesFile->id)->get();
    }

    private function getFileTextsSectionsIds($languagesFile)
    {
        return GnTranslation::where('gn_lang_file_id', $languagesFile->id)->pluck('gn_section_id')->toArray();
    }

    private function getSectionsByIds($ids){
        return GnSection::whereIn('id', $ids)->get();
    }

    private function getLanguages()
    {
        /**
         * Hacer la consulta a la tabla de lenguajes del proyecto devolver en array los lenguajes
         */
        return ['es', 'en', 'ru'];
    }
}
