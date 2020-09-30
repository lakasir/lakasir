<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

    {{-- Sidebar brand logo --}}
    @if(config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    {{-- Sidebar menu --}}
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if(config('adminlte.sidebar_nav_animation_speed') != 300)
                    data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}"
                @endif
                @if(!config('adminlte.sidebar_nav_accordion'))
                    data-accordion="false"
                @endif>
                {{-- Configured sidebar links --}}
                @each('adminlte::partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item')
                @if (app()->environment() == 'local')
                  <li class="nav-header">
                    {{ __('lakasir.title') }}
                  </li>
                  <li id="apss-id" class="nav-item">

                    <a class="nav-link {{ request()->path() == 'applications' ? 'active' : '' }}" href="/applications">

                      <i class="fas fa-fw fa-rocket"></i>

                      <p>
                      {{ __('lakasir.apps') }}
                      </p>

                    </a>

                  </li>
                @endif
            </ul>
        </nav>
    </div>

</aside>
