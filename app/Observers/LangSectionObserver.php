<?php

namespace App\Observers;

use App\Models\LangSection;

class LangSectionObserver
{
    /**
     * Handle the LangSection "created" event.
     *
     * @param  \App\Models\LangSection  $langSection
     * @return void
     */
    public function created(LangSection $langSection)
    {
        //
    }

    /**
     * Handle the LangSection "updated" event.
     *
     * @param  \App\Models\LangSection  $langSection
     * @return void
     */
    public function updated(LangSection $langSection)
    {
        //
    }

    /**
     * Handle the LangSection "deleted" event.
     *
     * @param  \App\Models\LangSection  $langSection
     * @return void
     */
    public function deleted(LangSection $langSection)
    {
        $langSection->update([
            'name' => time() . '::' . $langSection->name
        ]);
    }

    /**
     * Handle the LangSection "restored" event.
     *
     * @param  \App\Models\LangSection  $langSection
     * @return void
     */
    public function restored(LangSection $langSection)
    {
        //
    }

    /**
     * Handle the LangSection "force deleted" event.
     *
     * @param  \App\Models\LangSection  $langSection
     * @return void
     */
    public function forceDeleted(LangSection $langSection)
    {
        //
    }
}
