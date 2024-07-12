@extends('index')

@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h4 class="">Edit Kategori</h4>
                            <hr style="background-color: black">
                            @if (Session::has('status'))
                                <div class="alert alert-success text-white opacity-5" role="alert">
                                    {{ Session::get('message') }}
                                </div>
                            @endif
                        </div>

                        <div class="card-body px-0 pt-0 pb-2 ps-4 me-4">
                            <form action="{{ route('kategori.update', $kategori->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="nama" class="form-label text-sm required-label">Nama Kategori</label>
                                    <input type="text" class="form-control" name="nama" id="nama"
                                        value="{{ $kategori->nama }}" required>
                                </div>
                                <a href="{{ url()->previous() }}" class="btn bg-gradient-danger ">Back</a>
                                <button type="submit" class="btn btn-success float-end">Simpan Perubahan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function previewImage(event) {
            const preview = document.querySelector('#imagePreview img');
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onloadend = function() {
                preview.src = reader.result;
            }

            if (file) {
                reader.readAsDataURL(file);
                document.getElementById('imagePreview').style.display = 'block'; // Tampilkan image preview
            } else {
                preview.src = "#";
                document.getElementById('imagePreview').style.display = 'none'; // Sembunyikan image preview
            }
        }
    </script>
@endsection
