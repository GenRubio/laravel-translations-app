<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class LangTranslation extends Model
{
    use CrudTrait;
    use SoftDeletes;
    use HasTranslations;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'lang_translations';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'format_name',
        'value',
        'lang_section_id',
        'lang_file_id'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    protected $translatable = [
        'value'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function langSection(){
        return $this->hasOne(LangSection::class, 'id', 'lang_section_id');
    }

    public function langFile(){
        return $this->hasOne(LangFile::class, 'id', 'lang_file_id');
    }


    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getHelperAttribute(){
        $route = ($this->langFile ? $this->langFile->format_name . '.' : "") . ($this->langSection ? $this->langSection->format_name . '.' : "") . $this->attributes['format_name'];
        return "trans('" . $route . "')";
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setFormatNameAttribute()
    {
        $this->attributes['format_name'] = Str::slug($this->attributes['name'], '_');
    }
}
