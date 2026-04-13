@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-center align-items-center" style="background-color: #662222; min-height: 100vh;">
    <div class="text-center w-100">

        <div class="mb-4" style="margin-top: -30px;">
            <h4 class="fw-bold text-white mb-1">Sistem Perpustakaan</h4>
            <p class="text-white-50 small">Manajemen Peminjaman Buku</p>
        </div>

        <div class="card border-0 mx-auto shadow-lg" style="background-color: #FFF5E1; border-radius: 30px; max-width: 500px; padding: 40px;">
            <h5 class="fw-bold mb-4" style="color: #4A2C2C;">Pilih Role</h5>
            <p class="text-muted small mb-4">Silahkan pilih Role anda untuk melanjutkan</p>

            <div class="d-grid gap-3">
                <a href="{{ route('login.siswa') }}" class="btn py-3 text-start position-relative d-flex align-items-center justify-content-center shadow-sm btn-role" style="border: 1px solid #662222; color: #662222; border-radius: 15px; background: transparent;">
                    <div class="text-center w-100">
                        <i class="bi bi-person fs-4 d-block mb-1"></i>
                        <span class="fw-bold small">Masuk sebagai Siswa</span>
                    </div>
                </a>

                <a href="{{ route('login.admin') }}" class="btn py-3 text-start position-relative d-flex align-items-center justify-content-center shadow-sm btn-role" style="border: 1px solid #662222; color: #662222; border-radius: 15px; background: transparent;">
                     <div class="text-center w-100">
                        <i class="bi bi-person-lock fs-4 d-block mb-1"></i>
                        <span class="fw-bold small">Masuk sebagai Admin</span>
                    </div>
                </a>
            </div>
            
            <div class="mt-4 pt-3 border-top border-secondary-subtle">
            </div>
        </div>

    </div>
</div>

<style>
    .btn-role:hover {
        background-color: #662222 !important;
        color: white !important;
    }
</style>
@endsection
