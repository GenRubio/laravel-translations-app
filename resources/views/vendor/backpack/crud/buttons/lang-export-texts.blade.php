<div class="btn-group">
    <button id="dropdownMenuButton" class="btn btn-secondary buttons-collection dropdown-toggle btn-sm" tabindex="0" aria-controls="crudTable"
        type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span>
            <i class="la la-file-export"></i> {{ trans('translationsystem.export_texts.title') }}
        </span>
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="#">{{ trans('translationsystem.export_texts.all') }}</a>
        <a class="dropdown-item" href="#">{{ trans('translationsystem.export_texts.witch_conditions') }}</a>
    </div>
</div>
