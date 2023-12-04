@extends('layouts.app')
@section('content')
<div class="container-md">
     <!-- Button trigger modal -->
     <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Tambah Data Category
        </button>
    
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Masukan Data Category</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/category/create" method="POST" >
            @csrf
                <div class="modal-body">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" name="name" class="form-control" id="nama" placeholder="Category" required>
                            </div>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit"  class="btn btn-primary" >Save changes</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Masukan Data Category</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/category/update" method="POST" >
            @csrf
            @method('PUT')
                <div class="modal-body">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Category" required>
                                <input type="hidden" name="id" id="id">
                            </div>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit"  class="btn btn-primary" >Save changes</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
        
    <table class="table mt-5 mb-5">
        <thead>
            <tr>
                <th scope="col">No.</th>
                <th scope="col">Category</th>
            </tr>
        </thead>
        <tbody>

            @if (!isset($categories))
                <tr><td colspan="7">Data Kosong</td></tr>
            @else
                @foreach ($categories as $u)
                    <tr>
                        <td>{{ $loop->index + 1}} </td>
                        <td>{{ $u->name }} </td>
                        <td>
                            {{-- @if($u->id != Auth::user()->id)
                                <form action="/superadmin/update" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $u->id }}">
                                    <select name="role" onchange="this.form.submit()" {{ $u->id == Auth::user()->id ? 'disabled' : '' }}>
                                        <option value="0" {{ $u->is_admin == 0 ? 'selected' : '' }}>User</option>
                                        <option value="1" {{ $u->is_admin == 1 ? 'selected' : '' }}>Admin</option>
                                        <option value="2" {{ $u->is_admin == 2 ? 'selected' : '' }}>Super Admin</option>
                                    </select>
                                </form>
                            @else
                                @if ($u->is_admin == 0)
                                    User
                                @elseif ($u->is_admin == 1)
                                    Admin
                                @else
                                    Super Admin
                                @endif
                            @endif --}}
                        </td>
                        <td>
                            
                            <button type="submit" class="btn btn-primary btn-edit" data-id="{{ $u->id }}" data-name="{{ $u->name }}" data-bs-toggle="modal" data-bs-target="#EditModal">Edit</button>
                            <button type="submit" class="btn btn-danger btn-delete" data-id="{{ $u->id }}">Delete</button>
                            {{-- <form action="category/delete" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name='id' value={{ $u->id }}>
                            </form> --}}
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
    <script>
        let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $('.btn-edit').click(function(){
            let id = $(this).data('id');
            let name = $(this).data('name');
            $('#EditModal').find('.modal-body #name').val(name);
            $('#EditModal').find('.modal-body #id').val(id);
        });

        $('.btn-delete').click(function(){
            // sweet alert
            let id = $(this).data('id');
            Swal.fire({
                title: "Yakin?",
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('category.destroy') }}",
                        type: 'DELETE',
                        data: {
                            _token: CSRF_TOKEN,
                            id: id
                        },
                        success: function(response){
                            if (response.status == 'success') {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: "Data berhasil dihapus!",
                                    icon: "success",
                                    button: "OK",
                                }).then((result) => {
                                    if(result) {
                                        location.reload();
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: "Gagal!",
                                    text: "Data gagal dihapus!",
                                    icon: "error",
                                    button: "OK",
                                });
                            }
                        },
                        error: function(response){
                            swal({
                                title: "Gagal!",
                                text: "Data gagal dihapus!",
                                icon: "error",
                                button: "OK",
                            });
                        }
                    });
                }
            });
        });

    </script>
@endpush
