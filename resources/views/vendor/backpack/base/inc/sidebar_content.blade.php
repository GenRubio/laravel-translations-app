<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="las la-language"></i> Translations</a>
    <ul class="nav-dropdown-items">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('gn-translation') }}'><i class="las la-file-alt"></i> Texts</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('gn-section') }}'><i class="las la-list"></i> Sections</a></li>
    </ul>
</li>