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
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as traitUpdate;
    }
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

        if (request()->input('default') == true) {
            $this->updateConfigAppFile(request()->input('abbr'));
        }

        $this->updateConfigBackpackCrudFile(request()->input('abbr'), request()->input('active') == "1" ? true : false);
        \Artisan::call('config:clear');
        return $this->traitStore();
    }

    public function update()
    {
        if (request()->input('default') == true) {
            $this->updateConfigAppFile(request()->input('abbr'));
        }

        $this->updateConfigBackpackCrudFile(request()->input('abbr'), request()->input('active') == "1" ? true : false);
        $response = $this->traitUpdate();
        \Artisan::call('config:clear');
        return $response;
    }

    private function updateConfigBackpackCrudFile($lang, $active)
    {
        if (count($this->getLaguagesCount())) {
            if ($active){
                $this->changeLanguagesBackpack('"' . $lang . '" =>', '"' . $lang . '" =>', true);
            }
            else{
                $this->changeLanguagesBackpack('"' . $lang . '" =>', '"' . $lang . '" =>', false);
            }
        } else {
            $this->changeLanguagesBackpack("'en' =>", "'en' =>", false);
            $this->changeLanguagesBackpack("'fr' =>", "'fr' =>", false);
            $this->changeLanguagesBackpack("'it' =>", "'it' =>", false);
            $this->changeLanguagesBackpack("'ro' =>", "'ro' =>", false);

            $this->changeLanguagesBackpack('"' . $lang . '" =>', '"' . $lang . '" =>', true);
        }
    }

    private function changeLanguagesBackpack($lineFind, $replaceLine, $enable)
    {
        $folder = base_path('config');
        $filePhpPath = $folder . '/backpack/crud.php';
        $fileTmpPath = $folder . '/backpack/crud.tmp';

        $this->updatePhpFile($filePhpPath, $fileTmpPath, $lineFind, $replaceLine, true, $enable);
    }

    private function getLaguagesCount()
    {
        return Language::all();
    }

    private function updateConfigAppFile($lang)
    {
        $folder = base_path('config');
        $filePhpPath = $folder . '/app.php';
        $fileTmpPath = $folder . '/app.tmp';
        $lineFind = "'locale' =>";
        $replaceLine = "'locale' => '" . $lang . "',\n";

        $this->updatePhpFile($filePhpPath, $fileTmpPath, $lineFind, $replaceLine);
    }

    private function updatePhpFile($filePhpPath, $fileTmpPath, $lineFind, $replaceLine, $crud = false, $enable = false)
    {
        $reading = fopen($filePhpPath, 'r');
        $writing = fopen($fileTmpPath, 'w');

        $replaced = false;

        while (!feof($reading)) {
            $line = fgets($reading);
            if (stristr($line, $lineFind)) {
                if ($crud) {
                    if ($enable) {
                        $line = str_replace("//", "", $line);
                    } else {
                        $formatLine = str_replace("'", '"', $line);
                        $line = '//' . $formatLine;
                    }
                } else {
                    $line = $replaceLine;
                }
                $replaced = true;
            }
            fputs($writing, $line);
        }
        fclose($reading);
        fclose($writing);
        if ($replaced) {
            rename($fileTmpPath, $filePhpPath);
        } else {
            unlink($fileTmpPath);
        }
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
        $this->changeLanguagesBackpack('"' . $language->abbr . '" =>', '"' . $language->abbr . '" =>', false);
        $destroyResult = $this->traitDestroy($id);
        \Artisan::call('config:clear');
        return $destroyResult;
    }
}
