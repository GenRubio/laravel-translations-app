<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i
            class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-globe"></i>
        {{ trans('translationsystem.translations_nav') }}</a>
    <ul class="nav-dropdown-items">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('language') }}'><i
                    class='nav-icon la la-flag-checkered'></i> {{ trans('translationsystem.languages_nav') }}</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('lang-file') }}'><i
                    class="nav-icon lar la-file-alt"></i> {{ trans('translationsystem.lang_files_nav') }}</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('lang-section') }}'><i
                    class="nav-icon las la-list"></i> {{ trans('translationsystem.lang_sections_nav') }}</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('lang-translation') }}'><i
                    class="nav-icon las la-language"></i> {{ trans('translationsystem.lang_texts_nav') }}</a></li>
    </ul>
</li>
