@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-center align-items-center" style="background-color: #662222; min-height: 100vh;">
    <div class="card auth-card border-0 shadow-lg" style="background-color: #FFF5E1; border-radius: 30px; width: 600px; padding: 30px;">
        <div class="text-center mb-3">
             
            <div class="mx-auto d-flex align-items-center justify-content-center mb-2" style="width: 65px; height: 65px; background-color: #662222; border-radius: 50%;">
                <i class="bi bi-person-lock text-white fs-4"></i>
            </div>
            <h5 class="fw-bold" style="color: #4A2C2C;">Login Admin</h5>
            <p class="text-muted small mb-0">Masukkan akun Admin</p>
        </div>

        @if($errors->any())
        <div class="alert alert-danger py-2 px-3 small border-0 mb-3" style="border-radius: 10px;">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            
            <div class="mb-2">
                <label class="form-label text-start w-100 small fw-bold mb-1" style="color: #662222;">Email</label>
                <input type="text" name="username" class="form-control" style="background-color: transparent; border-color: #662222; border-radius: 10px;" placeholder="Masukkan Username / Email">
            </div>

            <div class="mb-3">
                <label class="form-label text-start w-100 small fw-bold mb-1" style="color: #662222;">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" style="background-color: transparent; border-color: #662222; border-radius: 10px 0 0 10px;" placeholder="Masukkan Password">
                    <span class="input-group-text bg-transparent border-danger text-danger" style="border-radius: 0 10px 10px 0; cursor: pointer;" onclick="togglePassword()"><i class="bi bi-eye" id="toggleIcon"></i></span>
                </div>
            </div>

            <button type="submit" class="btn w-100 text-white fw-bold py-2 mb-2" style="background-color: #662222; border-radius: 20px;">
                <i class="bi bi-box-arrow-in-right me-2"></i> Masuk
            </button>
        </form>
        
        <div class="text-center border-top border-secondary-subtle pt-2">
             <a href="{{ route('login') }}" class="text-decoration-none small fw-bold mb-1 d-block" style="color: #662222;">
                Kembali pilih Role
            </a>
            
             <p class="small mb-1 text-muted" style="font-size: 0.8rem;">Belum punya akun Admin?</p>
            <a href="{{ route('register.admin') }}" class="text-decoration-none small fw-bold" style="color: #662222;">
                <i class="bi bi-person-plus me-1"></i> Daftar Admin Disini
            </a>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        }
    }
</script>
@endsection
