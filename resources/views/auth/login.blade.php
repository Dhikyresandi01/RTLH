@extends('guest')

@section('content')
    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <div class="auth-logo">
                    <a href="{{ route('welcome') }}"><img src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo"></a>
                </div>
                <h1 class="auth-title">Log in.</h1>
                <p class="auth-subtitle mb-4">Masuk dengan kredensial akun Anda.</p>

                <!-- Status Notifikasi / Session -->
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible show fade mb-4">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Pesan Error Umum Validasi -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible show fade mb-4">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="email" name="email" class="form-control form-control-xl @error('email') is-invalid @enderror"
                               placeholder="Email" value="{{ old('email') }}" required autofocus autocomplete="username">
                        <div class="form-control-icon">
                            <i class="bi bi-person"></i>
                        </div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" name="password" class="form-control form-control-xl @error('password') is-invalid @enderror"
                               placeholder="Password" required autocomplete="current-password">
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check form-check-lg d-flex align-items-end">
                        <input class="form-check-input me-2" type="checkbox" name="remember" id="flexCheckDefault">
                        <label class="form-check-label text-gray-600" for="flexCheckDefault">
                            Ingat saya
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-4">Log in</button>
                </form>

                <div class="text-center mt-5 text-lg fs-4">
                    <p class="text-gray-600">Belum punya akun? <a href="{{ route('welcome') }}" class="font-bold">Hubungi Admin</a>.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right">

            </div>
        </div>
    </div>
@endsection
