<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-globe"></i> Translations</a>
    <ul class="nav-dropdown-items">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('language') }}'><i class='nav-icon la la-flag-checkered'></i> Languages</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('gn-lang-file') }}'><i class="nav-icon lar la-file-alt"></i> Lang Files</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('gn-section') }}'><i class="nav-icon las la-list"></i> Lang Sections</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('gn-translation') }}'><i class="nav-icon las la-language"></i> Lang Texts</a></li>
    </ul>
</li>