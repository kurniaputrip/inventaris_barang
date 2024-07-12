@extends('index')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h4 class="">Edit User</h4>
                        <hr class="bg-dark px-auto">
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <form action="{{ route('User.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $user->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ $user->email }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="level" class="form-label">Level</label>
                                <select class="form-select" id="level" name="level" required>
                                    <option value="member" @if ($user->level == 'member') selected @endif>Member</option>
                                    <option value="admin" @if ($user->level == 'admin') selected @endif>Admin</option>
                                    <option value="operator" @if ($user->level == 'operator') selected @endif>Operator
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password (leave blank if not changing)</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
