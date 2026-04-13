@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-center align-items-center" style="background-color: #662222; min-height: 100vh;">
    <div class="card auth-card border-0 shadow-lg" style="background-color: #FFF5E1; border-radius: 30px; width: 450px; padding: 30px;">
        <div class="text-center mb-3">
             <div class="mx-auto d-flex align-items-center justify-content-center mb-2" style="width: 65px; height: 65px; background-color: #662222; border-radius: 50%;">
                <i class="bi bi-pencil-square text-white fs-4"></i>
            </div>
            <h5 class="fw-bold" style="color: #4A2C2C;">Daftar Admin</h5>
            <p class="small text-muted mb-0">Buat akun untuk mengelola perpustakaan</p>
        </div>

        <form action="{{ route('register.admin.post') }}" method="POST">
            @csrf

            @if ($errors->any())
                <div class="alert alert-danger pb-0 small">
                    <ul class="mb-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="mb-2">
                <label class="form-label text-start w-100 small fw-bold mb-1" style="color: #662222;">Email</label>
                <input type="email" name="email" class="form-control" style="background-color: transparent; border-color: #662222; border-radius: 10px;" placeholder="Masukkan Email" required>
            </div>

            <div class="mb-2">
                <label class="form-label text-start w-100 small fw-bold mb-1" style="color: #662222;">Username</label>
                <input type="text" name="username" class="form-control" style="background-color: transparent; border-color: #662222; border-radius: 10px;" placeholder="Masukkan Username" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-start w-100 small fw-bold mb-1" style="color: #662222;">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" style="background-color: transparent; border-color: #662222; border-radius: 10px 0 0 10px;" 
                        placeholder="Masukkan Password (Huruf & Angka)" 
                        title="Password harus berupa huruf dan angka" 
                        oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')" required>
                    <span class="input-group-text bg-transparent border-danger text-danger" style="border-radius: 0 10px 10px 0; cursor: pointer;" onclick="togglePassword()"><i class="bi bi-eye" id="toggleIcon"></i></span>
                </div>
            </div>
            
            <button type="submit" class="btn w-100 text-white fw-bold py-2 mb-2" style="background-color: #662222; border-radius: 20px;">
                Daftar
            </button>
        </form>

        <div class="text-center border-top border-secondary-subtle pt-2">
            <p class="small mb-1 text-muted">Sudah punya akun?</p>
            <a href="{{ route('login.admin') }}" class="text-decoration-none small fw-bold" style="color: #662222;">
                 Login Disini
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
