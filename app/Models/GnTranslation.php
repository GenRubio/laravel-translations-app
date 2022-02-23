<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Support\Str;

class GnTranslation extends Model
{
    use CrudTrait;
    use HasTranslations;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'gn_translations';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'key',
        'format_key',
        'value',
        'gn_section_id',
        'gn_lang_file_id'
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

    public function section()
    {
        return $this->hasOne(GnSection::class, 'id', 'gn_section_id');
    }

    public function file(){
        return $this->hasOne(GnLangFile::class, 'id', 'gn_lang_file_id');
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
        $route = ($this->file ? $this->file->format_name . '.' : "") . ($this->section ? $this->section->format_section . '.' : "") . $this->attributes['format_key'];
        return "trans('" . $route . "')";
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setFormatKeyAttribute()
    {
        $this->attributes['format_key'] = Str::slug($this->attributes['key'], '_');
    }
}
