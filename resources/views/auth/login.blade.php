@extends('layouts.include')

@section('content')
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex bg-primary align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo text-center">
                                <img src="{{ asset('assets/images/RR2.png') }}" class="img-fluid" style="max-width: 90px;" alt="logo">
                            </div>
                            <h4 class="text-center">Sampurasun Warga RR2!</h4>
                            <h6 class="font-weight-light text-center">Login untuk melanjutkan.</h6>
                            <form method="POST" action="{{ route('login') }}" class="pt-3">
                                @csrf
                                <div class="form-group">
                                    <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" required autofocus placeholder="Username">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        name="password" required placeholder="Password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                                        SIGN IN
                                    </button>
                                </div>
                                <div class="my-2 text-center">
                                    <a href="#" class="auth-link text-black">Forgot password?</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
