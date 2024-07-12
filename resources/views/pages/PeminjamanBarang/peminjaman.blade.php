@extends('index')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 p-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="">Peminjaman Barang</h4>
                            </div>
                        </div>
                        <hr class="bg-dark px-auto">
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger text-white opacity-5" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (Session::has('status'))
                            <div class="alert alert-success text-white opacity-5" role="alert">
                                {{ Session::get('message') }}
                            </div>
                        @endif
                        <div class=" p-0">
                            <form action="/pinjam" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="user_id" class="form-label">Nama</label>
                                        <input type="text" class="form-control" id="user_id"
                                            value="{{ auth()->user()->name }}" readonly>
                                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="keluar" class="form-label">Tanggal Peminjaman</label>
                                        <input type="date" class="form-control" id="keluar" name="keluar"
                                            value="{{ date('Y-m-d') }}" required readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="jumlah" class="form-label">Jumlah</label>
                                        <input type="number" class="form-control" min="1" id="jumlah"
                                            name="jumlah" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="barang_id" class="form-label">Barang</label>
                                        <select class="form-control" id="barang_id" name="barang_id" required>
                                            @foreach ($barangs as $barang)
                                                <option value="{{ $barang->id }}">{{ $barang->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
