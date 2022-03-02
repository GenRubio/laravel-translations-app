<br>
<div class="btn-group mt-2 mb-2">
    <button id="dropdownMenuButton" class="btn btn-secondary buttons-collection dropdown-toggle btn-sm" tabindex="0"
        aria-controls="crudTable" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span>
            <i class="la la-file-export"></i> {{ trans('translationsystem.export_texts.title') }}
        </span>
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item"
            href="{{ url($crud->route . '/export-all') }}">{{ trans('translationsystem.export_texts.all') }}</a>
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
                <h5 class="modal-title" id="exampleModalLongTitle">Exportar Excel Textos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url($crud->route . '/export-by-condition') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exportInputFile">Archivo</label>
                        <select name="file" class="form-control" id="exportInputFile">
                            <option value="all">Todos archivos</option>
                            @foreach ($crud->dataLangFiles as $file)
                                <option value="{{ $file->id }}">{{ $file->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exportInputSection">Seccíon</label>
                        <select name="section" class="form-control" id="exportInputSection">
                            <option value="all">Todas secciones</option>
                            @foreach ($crud->dataLangSections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exportInputLaguage">Idioma</label>
                        <select name="laguage" class="form-control" id="exportInputLaguage">
                            <option value="all">Todas idiomas</option>
                            @foreach ($crud->dataLaguages as $laguage)
                                <option value="{{ $laguage->id }}">{{ $laguage->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Exportar</button>
                </div>
            </form>
        </div>
    </div>
</div>
