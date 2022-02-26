<?php

namespace App\Http\Controllers\Admin;

use App\Models\Language;
use App\Models\LangSection;
use App\Models\LangFile;
use Illuminate\Http\Request;
use App\Models\LangTranslation;
use App\Http\Requests\LangTranslationRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class LangTranslationCrudController extends CrudController
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
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation {
        bulkDelete as traitBulkDelete;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\LangTranslation::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/lang-translation');
        CRUD::setEntityNameStrings('lang text', 'lang texts');
    }

    public function fetchLangFile()
    {
        return $this->fetch(\App\Models\LangFile::class);
    }

    public function fetchLangSection()
    {
        return $this->fetch(\App\Models\LangSection::class);
    }

    protected function setupListOperation()
    {
        $this->crud->setColumns(
            [
                [
                    'type'            => 'custom_html',
                    'name'            => 'blank_first_column',
                    'label'           => ' ',
                    'priority'        => 0,
                    'searchLogic'     => false,
                    'orderable'       => false,
                    'visibleInTabel'  => true,
                    'visibleInModal'  => false,
                    'visibleInExport' => false,
                    'visibleInShow'   => false,
                    'hasActions'      => true,
                ],
                [
                    'type'            => 'checkbox',
                    'name'            => 'bulk_actions',
                    'label'           => ' <input type="checkbox" class="crud_bulk_actions_main_checkbox" style="width: 16px; height: 16px;" />',
                    'priority'        => 0,
                    'searchLogic'     => false,
                    'orderable'       => false,
                    'visibleInTable'  => true,
                    'visibleInModal'  => false,
                    'visibleInExport' => false,
                    'visibleInShow'   => false,
                    'hasActions'      => true,
                ],
                [
                    'name' => 'helper',
                    'type' => 'text',
                    'label' => 'Helper'
                ],
                [
                    'name' => 'value',
                    'type' => 'text',
                    'label' => 'Texto'
                ],
                [
                    'name' => 'langFile',
                    'type' => 'relationship',
                    'label' => 'Lang File',
                    'attribute' => 'format_name',
                ],
                [
                    'name' => 'langSection',
                    'type' => 'relationship',
                    'label' => 'Lang Section',
                    'attribute' => 'format_name',
                ],
                [
                    'name' => 'format_name',
                    'type' => 'text',
                    'label' => 'Format name'
                ]
            ]
        );

        $this->setFilters();
        $this->setShowNumberRows();
        if (count($this->getLanguages())){
            $this->crud->addButtonFromView('line', 'copy-helper-trans', 'copy-helper-trans', 'beginning');
            $this->crud->addButtonFromView('top', 'translate-all-files', 'translate-all-files', 'end');
            $this->crud->addButtonFromView('bottom', 'make-transletable-file', 'make-transletable-file', 'end');
        }
        else{
            $this->crud->removeButton('create');
        }
    }

    private function setShowNumberRows()
    {
        $this->crud->setDefaultPageLength(100);
    }

    private function setFilters()
    {
        $this->crud->addFilter([
            'name' => 'lang_file_id',
            'type' => 'select2',
            'label' => 'Lang File',
        ], function () {
            $data = [];
            foreach ($this->getAllLangFiles() as $file) {
                $data[$file->id] = $file->name;
            }
            return $data;
        }, function ($value) {
            $this->crud->addClause('where', 'lang_file_id', $value);
        });

        $this->crud->addFilter([
            'name' => 'lsng_section_id',
            'type' => 'select2',
            'label' => 'Lang Section',
        ], function () {
            $data = [];
            foreach ($this->getAllSections() as $section) {
                $data[$section->id] = $section->name;
            }
            return $data;
        }, function ($value) {
            $this->crud->addClause('where', 'lang_section_id', $value);
        });
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(LangTranslationRequest::class);

        $this->crud->addFields(
            [
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => 'Name <br> <small>Auto format: input = Hello Word | result = hello_word</small>'
                ],
                [
                    'name' => 'format_name',
                    'type' => 'hidden',
                ],
                [
                    'label' => "Lang File",
                    'type' => "relationship",
                    'name' => 'lang_file_id',
                    'entity' => 'langFile',
                    'attribute' => 'name',
                    'ajax' => true,
                    'inline_create' => true,
                    'minimum_input_length' => 0,
                ],
                [
                    'label'     => "Lang Section",
                    'type' => "relationship",
                    'name' => 'lang_section_id',
                    'entity' => 'langSection',
                    'attribute' => 'name',
                    'ajax' => true,
                    'inline_create' => true,
                    'minimum_input_length' => 0,
                ]
            ]
        );

        foreach($this->getLanguages() as $lang){
            $this->crud->addFields(
                [
                    [
                        'name' => 'laguages[' . $lang->abbr . ']',
                        'type' => 'textarea',
                        'label' => 'Lang (' . $lang->name . ')'
                    ]
                ]
            );
        }

        $this->crud->addFields(
            [
                [
                    'name' => 'value',
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
                    'label' => 'Format Name',
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
        if ($this->keyInUse()) {
            return redirect()->back()->withErrors(['error' => "Esta key ya esta en uso."]);
        } else {
            if (!$this->validateLaguagesInputs()){
                return redirect()->back()->withErrors(['error' => "Debes rellenar al manos 1 campo de lenguaje."]);
            }

            $languages = $this->prepareDataLaguages($this->crud->getRequest()->get('laguages'));
            $response = $this->traitStore();
            $this->updateValueFromGnTranslation($this->data['entry']->id, $languages);
            $this->makeLaguagesDirectories();
            return $response;
        }
    }

    private function prepareDataLaguages($languages){
        $default = "";
        foreach($languages as $lang){
            if ($lang && !empty($lang)){
                $default = $lang;
                break;
            }
        }
        foreach($languages as $key => $lang){
            if (is_null($lang) || empty($lang)){
                $languages[$key] = $default;
            }
        }
        return json_encode($languages);
    }

    private function validateLaguagesInputs(){
        $success = false;
        foreach($this->crud->getRequest()->get('laguages') as $lang){
            if ($lang && !empty($lang)){
                $success = true;
                break;
            }
        }
        return $success;
    }

    private function keyInUse()
    {
        $key = $this->crud->getRequest()->get('key');
        $langFile = $this->crud->getRequest()->get('lang_file_id');
        $section = $this->crud->getRequest()->get('lang_section_id');
        return $this->getTranslationByParameters($key, $langFile, $section) ? true : false;
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

    public function bulkDelete()
    {
        $this->crud->hasAccessOrFail('bulkDelete');

        $entries = request()->input('entries', []);
        $deletedEntries = [];

        foreach ($entries as $key => $id) {
            if ($entry = $this->crud->model->find($id)) {
                $deletedEntries[] = $entry->delete();
            }
        }

        $this->makeLaguagesDirectories();
        return $deletedEntries;
    }

    public function showTexts($lang = null, $file = null){
        return view('admin.multi-edit-languages')->with([
            'lang' => $lang ? $lang : $this->getDefaultLaguage()->abbr,
            'file' => $file,
            'langFiles' => $this->getAllLangFiles(),
            'laguages' => $this->getLanguages(),
            'crud' => $this->crud
        ]);
    }

    public function updateTexts(Request $request){
        $oldLocale = app()->getLocale();
        app()->setLocale($request->lang);
        foreach($request->translations as $key => $value){
            $translation = $this->getTranslationById($key);
            $translation->update([
                'value' => $value
            ]);
        }
        app()->setLocale($oldLocale);
        $this->makeLaguagesDirectories();
        \Alert::add('success', 'Traducciones actualizadas correctamente.')->flash();
        return redirect()->back();
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
            $path = $folder . '/' . $lang->abbr;
            foreach ($languagesFiles as $languagesFile) {
                $this->makeOrUpdateLaguagesFile($path, $lang, $languagesFile);
            }
        }
    }

    private function createFolders($folder, $languages)
    {
        foreach ($languages as $lang) {
            $path = $folder . '/' . $lang->abbr;
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
        }
    }

    private function makeOrUpdateLaguagesFile($path, $lang, $languagesFile)
    {
        $fullPath = $path . '/' . $languagesFile->format_name . '.php';

        $oldLocale = app()->getLocale();
        app()->setLocale($lang->abbr);
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
            $content .= "  '" . $text->format_name . "' => '" . $this->refactorValue($text->value) . "',\n";
        }

        foreach ($sections as $section) {
            $content .= "  '" . $section->format_name . "' => [\n";
            foreach ($this->getTranslationsBySection($translations, $section->id) as $translation) {
                $content .= "   '" . $translation->format_name . "' => '" . $this->refactorValue($translation->value) . "',\n";
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

    private function getTranslationsBySection($translations, $section)
    {
        return $translations->filter(function ($item) use ($section) {
            return $item->lang_section_id == $section;
        })->all();
    }

    private function updateValueFromGnTranslation($id, $languages){
        LangTranslation::where('id', $id)->update([
            'value' => $languages
        ]);
    }

    private function getTranslationByParameters($key, $langFile, $section)
    {
        return LangTranslation::where('name', $key)
            ->where('lang_file_id', $langFile)
            ->where('lang_section_id', $section)
            ->first();
    }

    private function getAllLangFiles()
    {
        return LangFile::all();
    }

    private function getAllSections()
    {
        return LangSection::all();
    }

    private function getTranslationById($id){
        return LangTranslation::find($id);
    }

    private function getLaguageFileTexts($languagesFile)
    {
        return LangTranslation::where('lang_file_id', $languagesFile->id)->get();
    }

    private function getFileTextsSectionsIds($languagesFile)
    {
        return LangTranslation::where('lang_file_id', $languagesFile->id)->pluck('lang_section_id')->toArray();
    }

    private function getSectionsByIds($ids)
    {
        return LangSection::whereIn('id', $ids)->get();
    }

    private function getLanguages()
    {
        return Language::where('active', 1)->orderBy('default', 'DESC')->get();
    }

    private function getDefaultLaguage(){
        return Language::where('active', 1)->where('default', 1)->first();
    }
}
