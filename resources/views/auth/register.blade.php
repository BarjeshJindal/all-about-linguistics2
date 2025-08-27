@extends('layouts.base', ['title' => 'Sign Up'])

@section('css')
@endsection

@section('content')

<div class="auth-bg d-flex min-vh-100">
  
    <div class="row g-0 justify-content-center  w-100 m-xxl-5 px-xxl-4 m-3">
        <div class="col-xxl-4 col-lg-5 col-md-6">
            <!-- <a href="{{ route('any', ['home'])}}" class="auth-brand d-flex justify-content-center mb-2">
                <img src="{{asset('/images/all-about-linguitics.jpg')}}" alt="dark logo" height="26" class="logo-dark">
                <img src="{{asset('/images/all-about-linguitics.jpg')}}" alt="logo light" height="26" class="logo-light">
            </a> -->

            {{-- <p class="fw-semibold mb-4 text-center text-muted fs-15">Admin Panel Design by Coderthemes</p> --}}

            <div class="">
                <div class="card overflow-hidden p-xxl-4 p-3 mb-0">
                    <div class="login-form-logo"><img src="{{ asset('/images/All-About-LOGO.png') }}" height="100" width="100" class="logo-dark">
</div>
                    
        <h4 class="fw-semibold mb-3 fs-20">Sign Up to your account</h4>
                     @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

                <!-- {{-- <form action="{{ route('any', ['index'])}}" class="text-start mb-3"> --}} -->
                     <form action="{{ route('register')}}" class="text-start mb-3" method="POST">
                        @csrf
                    <div class="mb-3">
                        <label class="form-label" for="name">Your Name</label>
                        <input type="text" id="name" name="name" class="form-control" required
                            placeholder="Enter your name">
                          
                    </div>
                     

                    <div class="mb-3">
                            <label class="form-label" for="language">Second Language</label>
                         <select id="language" name="language_id" class="form-control" required>
                                <option value="">-- Select your second language --</option>
                            
                                   @foreach ($languages as $language)
                                                        <option value="{{ $language->id}}">{{ ucfirst($language->second_language) }}</option>
                                     @endforeach
                            </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required
                            placeholder="Enter your email">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required
                            placeholder="Enter your password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="phone">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" required
                            placeholder="Enter your phone number">
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="checkbox-signin">
                            <label class="form-check-label" for="checkbox-signin">I agree to all <a href="#!"
                                    class="link-dark text-decoration-underline">Terms & Condition</a> </label>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-primary fw-semibold signup-btn" type="submit">Sign Up</button>
                    </div>
                </form>

                <p class="text-nuted fs-14 mb-0">Already have an account? <a href="{{ route ('login') }}"
                        class="fw-semibold text-danger ms-1">Login !</a></p>
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
.signup-btn {
    background: #e1c21e;
    border-color: #e1c21e;
}
.signup-btn:hover {
    background: #e1c21ecf;
    border-color: #e1c21e;
}
</style>

@endsection

@section('scripts')

@endsection
