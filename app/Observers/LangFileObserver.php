<?php

namespace App\Observers;

use App\Models\LangFile;

class LangFileObserver
{
    /**
     * Handle the LangFile "created" event.
     *
     * @param  \App\Models\LangFile  $langFile
     * @return void
     */
    public function created(LangFile $langFile)
    {
        //
    }

    /**
     * Handle the LangFile "updated" event.
     *
     * @param  \App\Models\LangFile  $langFile
     * @return void
     */
    public function updated(LangFile $langFile)
    {
        //
    }

    /**
     * Handle the LangFile "deleted" event.
     *
     * @param  \App\Models\LangFile  $langFile
     * @return void
     */
    public function deleted(LangFile $langFile)
    {
        $langFile->update([
            'name' => time() . '::' . $langFile->name
        ]);
    }

    /**
     * Handle the LangFile "restored" event.
     *
     * @param  \App\Models\LangFile  $langFile
     * @return void
     */
    public function restored(LangFile $langFile)
    {
        //
    }

    /**
     * Handle the LangFile "force deleted" event.
     *
     * @param  \App\Models\LangFile  $langFile
     * @return void
     */
    public function forceDeleted(LangFile $langFile)
    {
        //
    }
}
