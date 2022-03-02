<br>
<div class="btn-group mt-2 mb-2">
    <button id="dropdownMenuButton" class="btn btn-secondary buttons-collection dropdown-toggle btn-sm" tabindex="0"
        aria-controls="crudTable" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span>
            <i class="la la-file-export"></i> {{ trans('translationsystem.export_texts.title') }}
        </span>
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="#">{{ trans('translationsystem.export_texts.all') }}</a>
        <a class="dropdown-item" href="#" data-toggle="modal" data-style="zoom-in" data-backdrop="false"
            data-target="#exportByConditions">{{ trans('translationsystem.export_texts.witch_conditions') }}</a>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exportByConditions" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true" style="background-color:#0000005c">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
