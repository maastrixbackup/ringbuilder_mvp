@extends('admin.layouts.guest')
@section('content')
    <div class="row justify-content-center w-50">
        <div class="col-md-6 ">
            <div class="card mb-0">
                <div class="card-body">
                    <a href="/" class="text-nowrap logo-img text-center d-block py-3 w-50">
                        <img src="{{ asset('assets/images/logos/ring_builder.png') }}" width="280" alt="">
                        {{-- <h3> Admin Login</h3> --}}
                    </a>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />


                    <form method="POST" action="{{ route('admin.loginSubmit') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="form-control"
                                id="exampleInputEmail1" aria-describedby="emailHelp">
                            <x-input-error :messages="$errors->get('email')" class="mt-2 alert alert-danger" role="alert" />
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" name="password" required autocomplete="current-password"
                                class="form-control" id="exampleInputPassword1">
                            <x-input-error :messages="$errors->get('password')" class="mt-2 alert alert-danger" role="alert" />
                        </div>

                        <!-- Remember Me -->
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="form-check">
                                <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked"
                                    checked>
                                <label class="form-check-label text-dark" for="flexCheckChecked">
                                    Remeber this Device
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="text-primary fw-bold" href="#">Forgot Password ?</a>
                            @endif
                        </div>
                        {{-- <a href="#" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Sign
                            In</a> --}}
                        <x-primary-button class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">
                            {{ __('Log in') }}
                        </x-primary-button>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
