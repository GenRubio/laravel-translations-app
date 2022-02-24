@extends(backpack_view('blank'))

@php
$defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.list') => false,
];

// if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
$breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
    <div class="container-fluid">
        <h2>
            <span class="text-capitalize">Traducir textos del sitio.</span>
        </h2>
    </div>
@endsection

@section('content')
    <!-- Default box -->
    <div class="row">
        <!-- THE ACTUAL CONTENT -->
        @php
            $langFileSelected = null;
        @endphp
        <div class="{{ $crud->getListContentClass() }}" style="margin-bottom: 15px;">
            <ul class="nav nav-tabs">
                @if ($file)
                    @foreach ($langFiles as $langFile)
                        @if ($langFile->format_name == $file)
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page">{{ $langFile->name }}</a>
                            </li>
                            @php
                                $langFileSelected = $langFile;
                            @endphp
                        @else
                            <li class="nav-item">
                                <a class="nav-link"
                                    href="{{ url($crud->route . '/texts/' . $lang . '/' . $langFile->format_name) }}">{{ $langFile->name }}</a>
                            </li>
                        @endif
                    @endforeach
                @else
                    @foreach ($langFiles as $key => $langFile)
                        @if ($key == 0)
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page">{{ $langFile->name }}</a>
                            </li>
                            @php
                                $langFileSelected = $langFile;
                            @endphp
                        @else
                            <li class="nav-item">
                                <a class="nav-link"
                                    href="{{ url($crud->route . '/texts/' . $lang . '/' . $langFile->format_name) }}">{{ $langFile->name }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            </ul>
            <div class="p-3" style="background-color: white">
                <form action="" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-lg-2 mb-3">
                            <h5>Key</h5>
                        </div>
                        <div class="col-lg-5 mb-3">
                            <h5>Texto</h5>
                        </div>
                        <div class="col-lg-5 mb-3">
                            <h5>Traduccion</h5>
                        </div>
                        @foreach ($langFileSelected->translations as $translation)
                            <div class="col-lg-2">
                                {{ $translation->format_key }}
                            </div>
                            <div class="col-lg-5">
                                {{ $translation->key }}
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="2"> {{ $translation->key }}</textarea>
                                  </div>
                            </div>
                        @endforeach
                    </div>

                    <hr>
                    <button class="btn btn-success ladda-button" type="submit">
                        <i class="fa fa-save"></i> {{ trans('admin.save') }}
                    </button>
                    <a href="{{ backpack_url('photo') }}" class="btn btn-danger ladda-button"
                        onclick="return confirm('Los cambios no guardados se perderan.')">
                        <i class="fa fa-times"></i> {{ trans('admin.back') }}
                    </a>
                </form>
            </div>
        </div>

    </div>
@endsection

@section('after_styles')
    <!-- DATA TABLES -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('packages/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('packages/datatables.net-fixedheader-bs4/css/fixedHeader.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('packages/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('packages/backpack/crud/css/crud.css') . '?v=' . config('backpack.base.cachebusting_string') }}">
    <link rel="stylesheet"
        href="{{ asset('packages/backpack/crud/css/form.css') . '?v=' . config('backpack.base.cachebusting_string') }}">
    <link rel="stylesheet"
        href="{{ asset('packages/backpack/crud/css/list.css') . '?v=' . config('backpack.base.cachebusting_string') }}">

    <!-- CRUD LIST CONTENT - crud_list_styles stack -->
    @stack('crud_list_styles')
@endsection

@section('after_scripts')
    <script src="{{ asset('packages/backpack/crud/js/crud.js') . '?v=' . config('backpack.base.cachebusting_string') }}">
    </script>
    <script src="{{ asset('packages/backpack/crud/js/form.js') . '?v=' . config('backpack.base.cachebusting_string') }}">
    </script>
    <script src="{{ asset('packages/backpack/crud/js/list.js') . '?v=' . config('backpack.base.cachebusting_string') }}">
    </script>

    <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
    @stack('crud_list_scripts')
@endsection
