@extends('layouts.maintenance')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">👨‍🔧 Technicians Dashboard</h2>
        <a href="{{ route('technicians.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Add Technician
        </a>
    </div>

    @if(session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000,
                    toast: true,
                    position: 'top-end'
                });
            });
        </script>
    @endif

    <div class="row g-4">
        @forelse ($technicians as $tech)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-lg rounded-3 technician-card">
                <div class="card-img-wrapper">
                    <img src="{{ $tech->image ? asset($tech->image) : 'https://via.placeholder.com/400x200' }}" 
                         class="card-img-top rounded-top" 
                         alt="{{ $tech->name }}">
                </div>
                <div class="card-body">
                    <h5 class="card-title fw-bold text-primary">{{ $tech->name }}</h5>
                    <p class="card-text text-muted mb-2">
                        <i class="bi bi-envelope me-1"></i> {{ $tech->email }}
                    </p>
                    {{-- Inline Image Upload Form --}}
                    <form action="{{ route('technicians.uploadImage', $tech->technicians_id) }}" 
                          method="POST" enctype="multipart/form-data" class="upload-image-form">
                        @csrf
                        <input type="file" name="image" accept="image/*" class="form-control form-control-sm mb-2" required>
                        <button type="submit" class="btn btn-sm btn-outline-success w-100">Upload Image</button>
                    </form>
                </div>
                <div class="card-footer bg-white border-0 d-flex justify-content-between">
                    <a href="{{ route('technicians.edit', $tech) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                    <button class="btn btn-outline-danger btn-sm btn-delete" data-id="{{ $tech->technicians_id }}">
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>
                </div>
            </div>
        </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No technicians available yet. Add one above!</div>
            </div>
        @endforelse
    </div>
</div>

{{-- Card hover styles --}}
<style>
    .technician-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .technician-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .card-img-wrapper {
        overflow: hidden;
        max-height: 200px;
    }
    .card-img-top {
        object-fit: cover;
        height: 200px;
        width: 100%;
    }
</style>

{{-- SweetAlert2 & AJAX Upload --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {

    // Delete technician
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            let techId = this.dataset.id;

            Swal.fire({
                title: 'Are you sure?',
                text: "This technician will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.createElement('form');
                    form.action = '/technicians/' + techId;
                    form.method = 'POST';

                    let token = document.createElement('input');
                    token.type = 'hidden';
                    token.name = '_token';
                    token.value = '{{ csrf_token() }}';

                    let method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(token);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });

    // AJAX image upload
    const uploadForms = document.querySelectorAll('.upload-image-form');
    uploadForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = this.action;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token')
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    Swal.fire({
                        icon: 'success',
                        title: 'Image uploaded successfully!',
                        toast: true,
                        position: 'top-end',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error', data.message || 'Upload failed', 'error');
                }
            })
            .catch(err => Swal.fire('Error', 'Upload failed', 'error'));
        });
    });

});
</script>
@endsection
