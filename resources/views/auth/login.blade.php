@extends('layouts.app') 

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="card shadow-lg my-5">
                    
                    <div class="card-header bg-white text-center border-bottom-0 pt-4">
                        <h4 class="mb-0">Login</h4>
                    </div>

                    <div class="card-body pt-3">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="email" class="form-label visually-hidden">Email Address</label>
                                <input id="email" type="email" class="form-control" 
                                    name="email" value="{{ old('email') }}" placeholder="Email Address" required autofocus>
                                @error('email')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label visually-hidden">Password</label>
                                <input id="password" type="password" class="form-control" 
                                    name="password" placeholder="Password" required autocomplete="current-password">
                                @error('password')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="remember">
                                        Remember Me
                                    </label>
                                </div>
                                
                                @if (Route::has('password.request'))
                                    <a class="text-decoration-none small" href="{{ route('password.request') }}">
                                        Forgot Your Password?
                                    </a>
                                @endif
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                            
                            <div class="text-center mt-3">
                                <a href="{{ route('register') }}" class="text-decoration-none">
                                    新規登録
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection