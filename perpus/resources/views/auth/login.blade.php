@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-center align-items-center" style="background-color: #842A3B; min-height: 100vh;">
    <div class="card auth-card border-0 shadow-lg" style="background-color: #FFF5E1; border-radius: 20px; width: 400px; padding: 25px;">
        <div class="text-center mb-3">
            <img src="{{ asset('images/LOGO.png') }}" alt="Logo" style="height: 50px;" class="mb-2">
            <h5 class="fw-bold" style="color: #4A2C2C;">Sistem Perpustakaan</h5>
        </div>

        <div class="text-center mb-3">
            <ul class="nav nav-pills justify-content-center" id="pills-tab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-pill px-4" id="pills-admin-tab" data-bs-toggle="pill" data-bs-target="#pills-admin" type="button" role="tab" aria-controls="pills-admin" aria-selected="true" style="font-size: 0.85rem; background-color: #842A3B; color: white; border: 1px solid #800000;" onclick="setRole('admin')">Admin</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill px-4 ms-2" id="pills-student-tab" data-bs-toggle="pill" data-bs-target="#pills-student" type="button" role="tab" aria-controls="pills-student" aria-selected="false" style="font-size: 0.85rem; background-color: white; color: #842A3B; border: 1px solid #800000;" onclick="setRole('student')">Siswa</button>
              </li>
            </ul>
        </div>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-admin" role="tabpanel">
                    <div class="mb-3">
                        <label class="form-label text-start w-100 small fw-bold" style="color: #800000;">Username</label>
                        <input type="text" name="username" class="form-control" style="background-color: transparent; border-color: #800000;" placeholder="Masukkan Username">
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-siswa" role="tabpanel">
                    <div class="mb-3">
                        <label class="form-label text-start w-100 small fw-bold" style="color: #800000;">NIS</label>
                        <input type="text" name="nis" class="form-control" style="background-color: transparent; border-color: #800000;" placeholder="Masukkan NIS">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label text-start w-100 small fw-bold" style="color: #800000;">Password</label>
                <div class="input-group">
                    <input type="password" name="password" class="form-control" style="background-color: transparent; border-color: #800000;" placeholder="Masukkan Password">
                    <span class="input-group-text bg-transparent border-danger text-danger"><i class="bi bi-eye"></i></span>
                </div>
            </div>

            <button type="submit" class="btn w-100 text-white fw-bold py-2" style="background-color: #800000; border-radius: 20px;">
                Masuk
            </button>
        </form>

        <div id="admin-register-section" class="mt-3 pt-2 border-top border-secondary-subtle text-center">
            <p class="small mb-1 text-muted">Belum punya akun Admin?</p>
            <a href="{{ route('register.admin') }}" class="text-decoration-none fw-bold" style="color: #800000; font-size: 0.9rem;">
                Daftar Admin Disini
            </a>
        </div>
    </div>
</div>

<script>
    function setRole(role) {
        const adminBtn = document.getElementById('pills-admin-tab');
        const siswaBtn = document.getElementById('pills-student-tab');
        const adminRegisterSection = document.getElementById('admin-register-section');
        
        if(role === 'admin') {
            adminBtn.style.backgroundColor = '#842A3B';
            adminBtn.style.color = 'white';
            siswaBtn.style.backgroundColor = 'white';
            siswaBtn.style.color = '#842A3B';
            

            adminRegisterSection.style.display = 'block';
        } else {
            siswaBtn.style.backgroundColor = '#842A3B';
            siswaBtn.style.color = 'white';
            adminBtn.style.backgroundColor = 'white';
            adminBtn.style.color = '#842A3B';

            adminRegisterSection.style.display = 'none';
        }
    }
</script>
@endsection
