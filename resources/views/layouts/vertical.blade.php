<!DOCTYPE html>
<html lang="en" @yield('html_attribute')>

<head>
    @include('layouts.partials/title-meta')

    @include('layouts.partials/head-css')
</head>

<body>

    <div class="wrapper">

        

       @if(Auth::guard('web')->check())
    @include('layouts.partials.user-sidenav')
@else
    @include('layouts.partials.sidenav')
@endif


        @include('layouts.partials/topbar')

        <div class="page-content">

            <div class="page-container">

                @yield('content')

            </div>

            @include('layouts.partials/footer')
        </div>

    </div>

    @include('layouts.partials/customizer')

    @include('layouts.partials/footer-scripts')

</body>

</html>