<?php

namespace App\Observers;

use App\Models\Language;

class LanguageObserver
{
    /**
     * Handle the Language "created" event.
     *
     * @param  \App\Models\Language  $language
     * @return void
     */
    public function created(Language $language)
    {
        $this->disableDefaultLaguages();
        $this->updateBackpackCrud();
        \Artisan::call('config:clear');
    }

    /**
     * Handle the Language "updated" event.
     *
     * @param  \App\Models\Language  $language
     * @return void
     */
    public function updated(Language $language)
    {
        $this->updateBackpackCrud();
        \Artisan::call('config:clear');
    }

    /**
     * Handle the Language "deleted" event.
     *
     * @param  \App\Models\Language  $language
     * @return void
     */
    public function deleted(Language $language)
    {
        $this->updateBackpackCrudFile('"' . $language->abbr . '" =>', false);
        \Artisan::call('config:clear');
    }

    /**
     * Handle the Language "restored" event.
     *
     * @param  \App\Models\Language  $language
     * @return void
     */
    public function restored(Language $language)
    {
        $this->updateBackpackCrud();
        \Artisan::call('config:clear');
    }

    /**
     * Handle the Language "force deleted" event.
     *
     * @param  \App\Models\Language  $language
     * @return void
     */
    public function forceDeleted(Language $language)
    {
        $this->updateBackpackCrudFile('"' . $language->abbr . '" =>', false);
        \Artisan::call('config:clear');
    }

    private function getLaguages(){
        return Language::all();
    }

    private function updateBackpackCrud(){
        foreach($this->getLaguages() as $language){
            if ($language->active){
                $this->updateBackpackCrudFile('"' . $language->abbr . '" =>', true);
            }
            else{
                $this->updateBackpackCrudFile('"' . $language->abbr . '" =>', false);
            }
        }
    }

    private function disableDefaultLaguages()
    {
        $this->updateBackpackCrudFile("'en' =>", false);
        $this->updateBackpackCrudFile("'fr' =>", false);
        $this->updateBackpackCrudFile("'it' =>", false);
        $this->updateBackpackCrudFile("'ro' =>", false);
    }

    private function updateBackpackCrudFile($lineFind, $enable = false)
    {
        $folder = base_path('config');
        $filePhpPath = $folder . '/backpack/crud.php';
        $fileTmpPath = $folder . '/backpack/crud.tmp';

        $reading = fopen($filePhpPath, 'r');
        $writing = fopen($fileTmpPath, 'w');

        $replaced = false;

        while (!feof($reading)) {
            $line = fgets($reading);
            if (stristr($line, $lineFind)) {
                if ($enable) {
                    $line = str_replace("//", "", $line);
                } else {
                    $formatLine = str_replace("'", '"', $line);
                    $line = '//' . $formatLine;
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
}
