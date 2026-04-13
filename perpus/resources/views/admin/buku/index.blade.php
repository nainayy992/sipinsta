@extends('layouts.dashboard')

@section('title', 'Kelola Buku')

@section('dashboard_content')
<div class="row">
    <div class="col-12">
        <div class="card card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Manajemen Koleksi Buku</h5>
                <button class="btn btn-maroon fw-bold" data-bs-toggle="modal" data-bs-target="#addBukuModal">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Buku
                </button>
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
                            <th class="py-3">Cover</th>
                            <th class="py-3">Judul & Pengarang</th>
                            <th class="py-3">Kategori</th>
                            <th class="py-3">Stok</th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($buku as $b)
                        <tr>
                            <td>
                                @if($b->foto)
                                    <img src="{{ asset('images/' . $b->foto) }}" alt="Cover" class="rounded" style="width: 50px; height: 70px; object-fit: cover;">
                                @else
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 70px;">
                                        <i class="bi bi-book small"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold d-block">{{ $b->judul_buku }}</span>
                                <small class="text-muted">{{ $b->pengarang }} | {{ $b->penerbit }}</small>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $b->kategori->nama_kategori }}</span></td>
                            <td><span class="badge {{ $b->stok > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">{{ $b->stok }} eks</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light border btn-edit-buku" 
                                    data-id="{{ $b->id_buku }}" 
                                    data-judul="{{ $b->judul_buku }}"
                                    data-pengarang="{{ $b->pengarang }}"
                                    data-penerbit="{{ $b->penerbit }}"
                                    data-tahun="{{ $b->tahun_terbit }}"
                                    data-stok="{{ $b->stok }}"
                                    data-kategori="{{ $b->id_kategori }}">
                                    <i class="bi bi-pencil-square text-primary"></i>
                                </button>
                                <form action="{{ route('admin.buku.destroy', $b->id_buku) }}" method="POST" class="d-inline delete-form-buku">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-light border btn-delete-buku">
                                        <i class="bi bi-trash text-danger"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-5">Belum ada buku.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Buku -->
<div class="modal fade" id="addBukuModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content card-custom border-0 shadow-lg">
            <div class="modal-header border-0">
                <h5 class="fw-bold text-maroon">Tambah Koleksi Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.buku.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Judul Buku</label>
                            <input type="text" name="judul_buku" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Kategori</label>
                            <select name="id_kategori" class="form-select" required>
                                @foreach($kategori as $k)
                                    <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Pengarang</label>
                            <input type="text" name="pengarang" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Penerbit</label>
                            <input type="text" name="penerbit" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold">Tahun Terbit</label>
                            <input type="number" name="tahun_terbit" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold">Stok</label>
                            <input type="number" name="stok" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold">Cover (Optional)</label>
                            <input type="file" name="foto" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-maroon fw-bold px-4 w-100">Simpan Buku</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Buku -->
<div class="modal fade" id="editBukuModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content card-custom border-0 shadow-lg">
            <div class="modal-header border-0">
                <h5 class="fw-bold text-maroon">Edit Detail Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editBukuForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Judul Buku</label>
                            <input type="text" name="judul_buku" id="edit_judul" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Kategori</label>
                            <select name="id_kategori" id="edit_kategori" class="form-select" required>
                                @foreach($kategori as $k)
                                    <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Pengarang</label>
                            <input type="text" name="pengarang" id="edit_pengarang" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Penerbit</label>
                            <input type="text" name="penerbit" id="edit_penerbit" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold">Tahun Terbit</label>
                            <input type="number" name="tahun_terbit" id="edit_tahun" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold">Stok</label>
                            <input type="number" name="stok" id="edit_stok" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold">Cover Baru (Optional)</label>
                            <input type="file" name="foto" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-maroon fw-bold px-4 w-100">Update Buku</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.btn-edit-buku').on('click', function() {
        const id = $(this).data('id');
        $('#edit_judul').val($(this).data('judul'));
        $('#edit_pengarang').val($(this).data('pengarang'));
        $('#edit_penerbit').val($(this).data('penerbit'));
        $('#edit_tahun').val($(this).data('tahun'));
        $('#edit_stok').val($(this).data('stok'));
        $('#edit_kategori').val($(this).data('kategori'));
        $('#editBukuForm').attr('action', `/admin/buku/${id}`);
        $('#editBukuModal').modal('show');
    });

    $('.btn-delete-buku').on('click', function() {
        const form = $(this).closest('.delete-form-buku');
        Swal.fire({
            title: 'Hapus Buku?',
            text: "Data buku ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#662222',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
@endpush
@endsection
