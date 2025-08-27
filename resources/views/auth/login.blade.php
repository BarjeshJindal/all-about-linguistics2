@extends('layouts.base', ['title' => 'Log In'])

@section('css')
@endsection

@section('content')

    <div class="auth-bg d-flex min-vh-100">
        <div class="row g-0 justify-content-center align-items-center w-100 m-xxl-5 px-xxl-4 m-3">
            <div class="col-xxl-4 col-lg-5 col-md-6">
                <!-- <a href="{{ route('any', ['home']) }}" class="auth-brand d-flex justify-content-center mb-2">
                    <img src="{{ asset('/images/all-about-linguitics.png') }}" height="200" class="logo-dark">
                    <img src="{{ asset('/images/all-about-linguitics.png') }}" height="200" class="logo-light">
                </a> -->

                <div class="card overflow-hidden p-xxl-4 p-3 mb-0">
                    <div class="login-form-logo"><img src="{{ asset('/images/All-About-LOGO.png') }}" height="100" width="100" class="logo-dark">
</div>
                    
                    <h4 class="fw-semibold mb-3 fs-20">Log in to your account</h4>

                    {{-- <form action="{{ route('any', ['index'])}}" class="text-start mb-3"> --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ $isAdmin ? route('admin.login.post') : route('login') }}">
                        @csrf

            

                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control"
                                placeholder="Enter your email">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <input type="password" id="password" class="form-control" name="password"
                                placeholder="Enter your password">
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="checkbox-signin">
                                <label class="form-check-label" for="checkbox-signin">Remember me</label>
                            </div>

                            <a href="{{ route('second', ['auth', 'recoverpw']) }}"
                                class="text-muted border-bottom border-dashed">Forget
                                Password</a>
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-primary fw-semibold login-btn" type="submit">Login</button>
                        </div>
                    </form>

                    <p class="text-muted fs-14 mb-0  mt-3">Don't have an account?
                        <a href="{{ route('register') }}" class="fw-semibold text-danger ms-1">Sign Up
                            !</a>
                    </p>

                </div>
                <p class="mt-4 text-center mb-0">
                    <script>
                        document.write(new Date().getFullYear())
                    </script> © All About Linguistics - By <span
                        class="fw-bold text-decoration-underline text-uppercase text-reset fs-12">
                        <a href = "https://arobasetechnologies.com"> Arobase Technologies </a>
                    </span>
                </p>
            </div>
        </div>
    </div>
 <style>
    span.fw-bold.text-decoration-underline.text-uppercase.text-reset.fs-12 a, a.fw-semibold.text-danger.ms-1 {
    color: #000000 !important;
    text-decoration: none;
}
.login-form-logo {
    text-align: center;
    margin-bottom: 15px;
}
.card.overflow-hidden.p-xxl-4.p-3.mb-0 {
    background: #ffffff4a;
}
.login-btn {
    background: #e1c21e;
    border-color: #e1c21e;
}
.login-btn:hover {
    background: #e1c21ecf;
    border-color: #e1c21e;
}

</style>
@endsection

@section('scripts')
@endsection
