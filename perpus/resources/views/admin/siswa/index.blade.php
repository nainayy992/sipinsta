@extends('layouts.dashboard')

@section('title', 'Kelola Anggota')

@section('dashboard_content')
<div class="row">
<!-- Form Tambah Siswa -->
    <div class="col-md-4">
        <div class="card card-custom p-4 mb-4">
            <h5 class="fw-bold mb-4 text-maroon">Registrasi Anggota Baru</h5>
            
            <form action="{{ route('admin.siswa.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Caca Merica" 
                        pattern="[a-zA-Z\s]+" title="Hanya diperbolehkan huruf dan spasi"
                        oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')"
                        required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">NIS</label>
                    <input type="text" name="nis" class="form-control" placeholder="Contoh: 1.23.20512" 
                        pattern="[0-9.]+" title="Hanya diperbolehkan angka dan titik (.)" 
                        oninput="this.value = this.value.replace(/[^0-9.]/g, '')"
                        required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Jurusan</label>
                    <input type="text" name="jurusan" class="form-control" placeholder="Contoh: RPL" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Kelas</label>
                    <input type="text" name="kelas" class="form-control" placeholder="Contoh: X,XI,XII" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 Karakter" 
                        pattern="[a-zA-Z0-9]{6}" title="Password 6 karakter" 
                        maxlength="6" oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')" required>
                </div>
                <button type="submit" class="btn btn-maroon w-100 fw-bold">Daftarkan Siswa</button>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Siswa -->
    <div class="col-md-8">
        <div class="card card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Daftar Anggota (Siswa)</h5>
                <span class="badge bg-maroon">{{ count($siswa) }} Siswa</span>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3">#</th>
                            <th class="py-3">Nama</th>
                            <th class="py-3">NIS</th>
                            <th class="py-3">Kelas</th>
                            <th class="py-3">Jurusan</th>
                            <th class="py-3">Tgl Bergabung</th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswa as $key => $s)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="fw-semibold">{{ $s->name }}</td>
                            <td><code class="text-maroon">{{ $s->nis }}</code></td>
                            <td>{{ $s->siswa->kelas ?? '-' }}</td>
                            <td>{{ $s->siswa->jurusan ?? '-' }}</td>
                            <td>{{ $s->created_at->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <div class="btn-action-group">
                                    <button class="btn btn-sm btn-light border btn-edit" 
                                        data-id="{{ $s->id }}" 
                                        data-name="{{ $s->name }}" 
                                        data-nis="{{ $s->nis }}"
                                        data-kelas="{{ $s->siswa->kelas ?? '' }}"
                                        data-jurusan="{{ $s->siswa->jurusan ?? '' }}"
                                        title="Edit">
                                        <i class="bi bi-pencil-square text-primary"></i>
                                    </button>
                                    <form action="{{ route('admin.siswa.destroy', $s->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-light border btn-delete" title="Hapus">
                                            <i class="bi bi-trash text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-info-circle fs-2 d-block mb-2"></i>
                                Belum ada data siswa yang terdaftar.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Siswa -->
<div class="modal fade" id="editSiswaModal" tabindex="-1" aria-labelledby="editSiswaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-maroon" id="editSiswaModalLabel">Edit Data Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSiswaForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nama Lengkap</label>
                        <input type="text" name="name" id="edit_name" class="form-control" 
                            pattern="[a-zA-Z\s]+" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">NIS</label>
                        <input type="text" name="nis" id="edit_nis" class="form-control" 
                            pattern="[0-9.]+" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Jurusan</label>
                        <input type="text" name="jurusan" id="edit_jurusan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Kelas</label>
                        <input type="text" name="kelas" id="edit_kelas" class="form-control" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold text-muted">Password Baru (Kosongkan jika tidak ingin ganti)</label>
                        <input type="password" name="password" class="form-control" placeholder="Tepat 6 Karakter (Huruf & Angka)"
                            pattern="[a-zA-Z0-9]{6}" title="Password harus tepat 6 karakter huruf dan angka"
                            maxlength="6" oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-maroon fw-bold px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<style>
    .btn-action-group {
        display: flex;
        gap: 5px;
        justify-content: center;
        flex-wrap: wrap; /* Ensure buttons wrap on very small screens */
    }
    .btn-action-group .btn {
        padding: 0.25rem 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

@push('scripts')
<script>
    $(document).ready(function() {
        // Handle Edit Button
        $('.btn-edit').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const nis = $(this).data('nis');
            const kelas = $(this).data('kelas');
            const jurusan = $(this).data('jurusan');
            
            $('#edit_name').val(name);
            $('#edit_nis').val(nis);
            $('#edit_kelas').val(kelas);
            $('#edit_jurusan').val(jurusan);
            
            // Use Laravel's named route pattern but replace the placeholder
            let url = "{{ route('admin.siswa.update', ':id') }}";
            url = url.replace(':id', id);
            $('#editSiswaForm').attr('action', url);
            
            $('#editSiswaModal').modal('show');
        });

        // Handle Delete Button
        $('.btn-delete').on('click', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data siswa akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#662222',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
@endsection
