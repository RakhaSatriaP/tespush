@extends('layouts.app')
@section('content')

    <style>
    .card:hover {
        transform: scale(1.05);
        transition: transform .2s;
    }
    </style>
    <div class="container-md">
       
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Tambah Data Product
            </button>
        
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Masukan Product</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/product" method="POST" enctype="multipart/form-data">
                @csrf
                    <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" id="nama" placeholder="Name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Price</label>
                                    <input type="number" name="price" class="form-control" id="price" placeholder="Price" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Stock</label>
                                    <input type="number" name="stock" class="form-control" id="stock" placeholder="stock" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Image</label>
                                    <input type="file" name="image" class="form-control" id="image"  required>
                                </div>
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <select name="category_id" id="category_id" class="form-control">
                                        @foreach ($category as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                    
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Masukan Product</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/product" method="POST" enctype="multipart/form-data" id="form-edit">
                @csrf
                @method('PUT')
                    <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Name" required>
                                    <input type="hidden" name="id" id="id">
                                </div>
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Price</label>
                                    <input type="number" name="price" class="form-control" id="price" placeholder="Price" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Stock</label>
                                    <input type="number" name="stock" class="form-control" id="stock" placeholder="stock" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Image</label>
                                    <input type="file" name="image" class="form-control" id="image" >
                                </div>
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <select name="category_id" id="category_id" class="form-control">
                                        @foreach ($category as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                    
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
        
        <div class="row">


            @foreach ($products as $p)
            <div class="col-md-4 mt-4 d-flex align-items-stretch">
                <div class="card border border-3 border-black rounded d-flex flex-column justify-content-between" style="width: 18rem;">
                    <div class="image-container">
                        <img src="{{ asset('storage/'.$p->image) }}" class="card-img-top img-fluid img-zoom" alt="{{ $p->name }}" style="object-fit: cover; height: 200px;">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $p->name }}</h5>
                        <p class="card-text">Rp. {{ $p->price }}</p>
                        <p class="card-text">Stock: {{ $p->stock }}</p>
                    </div>
                    <div class="card-footer">
                        <a href="#" class="btn btn-primary btn-add-cart" data-id="{{ $p->id }}" data-name="{{ $p->name }}">Add to Cart</a>
                        
                        <button href="#" class="btn btn-danger btn-delete" data-id="{{ $p->id }}"><i class="bi bi-trash"></i></button>
                        <button type="submit" class="btn btn-warning btn-edit" data-id="{{ $p->id }}" data-name="{{ $p->name }}" data-price="{{ $p->price }}" data-stock="{{ $p->stock }}" data-image="{{ $p->image }}" data-category_id="{{ $p->category_id }}" data-bs-toggle="modal" data-bs-target="#EditModal"><i class="bi bi-pencil"></i></button>
                       
                    </div>
                </div>
            </div>
            
            
            @endforeach

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $('.btn-edit').click(function(){
            let id = $(this).data('id');
            let name = $(this).data('name');
            let price = $(this).data('price');
            let stock = $(this).data('stock');
            let image = $(this).data('image');
            let category_id = $(this).data('category_id');
            console.log(category_id);
            $('#EditModal').find('.modal-body #name').val(name);
            $('#EditModal').find('.modal-body #id').val(id);
            $('#EditModal').find('.modal-body #price').val(price);
            $('#EditModal').find('.modal-body #stock').val(stock);
            // $('#EditModal').find('.modal-body #image').val(image);
            // $('#EditModal').find('.modal-body #category_id').val(category_id);
            // make selected in dropdown category
            $('#EditModal').find('.modal-body #category_id option').each(function(){
                if($(this).val() == category_id) {
                    $(this).attr('selected', 'selected');
                }
            });

            $('#form-edit').attr('action', '/product/'+id);
        });

        $('.btn-delete').click(function(){
            // sweet alert
            let id = $(this).data('id');
            let url = "{{ route('product.destroy', ':id') }}".replace(':id', id);
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
                        url: url,
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

        $('.btn-add-cart').click(function(){
            let id = $(this).data('id');
            let name = $(this).data('name');
            let url = "{{ route('product.addToCart', ':id') }}".replace(':id', id);
            $.ajax({
                url: url,
                type: 'GET',
                
                success: function(response){
                    if (response.success) {
                        Swal.fire({
                            title: "Berhasil!",
                            text: "Data berhasil ditambahkan!",
                            icon: "success",
                            button: "OK",
                        }).then((result) => {
                            if(result) {
                                location.reload();
                            }
                        });
                        //make toast background success and text white
                        // toastr.options = {
                        //     "closeButton": true,
                        //     "progressBar": true,
                        //     "positionClass": "toast-top-right",
                        //     "showDuration": "300",
                        //     "hideDuration": "1000",
                        //     "timeOut": "5000",
                        //     "extendedTimeOut": "1000",
                        //     "toastClass": "toast success"
                        // }
                        // toastr["success"](response.message , 'Berhasil!');

                    } else {
                        Swal.fire({
                            title: "Gagal!",
                            text: "Data gagal ditambahkan!",
                            icon: "error",
                            button: "OK",
                        });
                    }
                },
                error: function(response){
                    swal({
                        title: "Gagal!",
                        text: "Data gagal ditambahkan!",
                        icon: "error",
                        button: "OK",
                    });
                }
            });
        });

        
    </script>
@endpush
