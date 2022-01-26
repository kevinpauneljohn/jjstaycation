<aside class="control-sidebar control-sidebar-{{ config('adminlte.right_sidebar_theme') }}">
    @yield('right-sidebar')
    @if(auth()->check())
        @if(\Illuminate\Support\Facades\Route::currentRouteName() === 'assigned-staycations.show')
            @include('adminlte::customfile.right-sidebar-content')
        @endif

    @endif

</aside>
