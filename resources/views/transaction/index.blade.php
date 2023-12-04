@extends('layouts.app')

@section('content')

    <style>
    .card:hover {
        transform: scale(1.05);
        transition: transform .2s;
    }
    </style>
    <div class="container-md">
           
        <div class="row">

            @foreach ($cartProducts as $p)
            <div class="col-md-4 mt-4 d-flex align-items-stretch">
                <div class="card border border-3 border-black rounded d-flex flex-column justify-content-between" style="width: 18rem;">
                    <div class="image-container">
                        <img src="{{ asset('storage/'.$p->attributes->image) }}" class="card-img-top img-fluid img-zoom" alt="{{ $p->name }}" style="object-fit: cover; height: 200px;">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $p->name }}</h5>
                        <p class="card-text">Rp. {{ $p->price }}</p>
                        <p class="card-text">Qty: {{ $p->quantity }}</p>
                        <p class="card-text">Total Harga Product: {{ $p->quantity * $p->price}}</p>
                    </div>
                    <div class="card-footer">
                        <a href="#" class="btn btn-primary btn-delete-cart" data-id="{{ $p->id }}" data-name="{{ $p->name }}">Delete</a>
                        <a href="#" class="btn btn-success btn-add-cart" data-id="{{ $p->id }}" data-name="{{ $p->name }}"><i class="bi bi-plus-circle"></i></a>
                        <a href="#" class="btn btn-warning btn-min-cart" data-id="{{ $p->id }}" data-name="{{ $p->name }}"><i class="bi bi-dash-circle"></i></a>

                        
                    </div>
                </div>
            </div>
            
            
            @endforeach

        </div>
        <div class="row">
            <div class="col-md-12">
                <h3 class="mt-4">Total Harga: Rp. {{ $total }}</h3>
            </div>
            <a href="" class="btn btn-primary mt-4 btn-co">CHECKOUT</a>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $('.btn-delete-cart').click(function(){
            // sweet alert
            let id = $(this).data('id');
            let name = $(this).data('name');
            let url = "{{ route('transaction.deleteFromCart', ':id') }}".replace(':id', id);
            Swal.fire({
                title: "Yakin?",
                text: "Menghapus " + name + " dari keranjang",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'GET',
                        
                        success: function(response){
                            if (response.success) {
                                Swal.fire({
                                    title: "Berhasil",
                                    text: "Berhasil menghapus " + name + " dari keranjang",
                                    icon: "success",
                                    // showConfirmButton: false,
                                    button: "OK",
                                }).then(function(){
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: "Gagal",
                                    text: "Gagal menghapus " + name + " dari keranjang",
                                    icon: "error",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        },
                        error: function(xhr){
                            Swal.fire({
                                title: "Gagal",
                                text: "Gagal menghapus " + name + " dari keranjang",
                                icon: "error",
                                showConfirmButton: false,
                                timer: 1500
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
        $('.btn-min-cart').click(function(){
            let id = $(this).data('id');
            let name = $(this).data('name');
            let url = "{{ route('transaction.minFromCart', ':id') }}".replace(':id', id);
            $.ajax({
                url: url,
                type: 'GET',
                
                success: function(response){
                    if (response.success) {
                        Swal.fire({
                            title: "Berhasil!",
                            text: response.message,
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
                            text: response.message,
                            icon: "error",
                            button: "OK",
                        });
                    }
                },
                error: function(response){
                    swal({
                        title: "Gagal!",
                        text: response.message,
                        icon: "error",
                        button: "OK",
                    });
                }
            });
        });

        $('.btn-co').on('click', function(e){
            e.preventDefault();
            let url = "{{ route('transaction.store') }}";
            Swal.fire({
                title: "Yakin?",
                text: "Checkout",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Checkout",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _token: CSRF_TOKEN,
                        },
                        
                        success: function(response){
                            if (response.success) {
                                Swal.fire({
                                    title: "Berhasil",
                                    text: response.message,
                                    icon: "success",
                                    // showConfirmButton: false,
                                    button: "OK",
                                }).then(function(){
                                    window.location.href = "{{ route('product.index') }}";
                                });
                            } else {
                                Swal.fire({
                                    title: "Gagal",
                                    text: response.message,
                                    icon: "error",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        },
                        error: function(xhr){
                            Swal.fire({
                                title: "Gagal",
                                text: response.message,
                                icon: "error",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    });
                }
            });
            
            
        });
        
    </script>
@endpush
