@extends('layouts.app')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Tambah Role</h1>
        </div>

        <div class="section-body">

            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-unlock"></i> Tambah Role</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('roles.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label>NAMA ROLE</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan Nama Role"
                                class="form-control @error('title') is-invalid @enderror">

                            @error('name')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">PERMISSIONS</label>
                            
                            @foreach ($permissions as $groupName => $permissionGroup)
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ strtoupper($groupName) }}</label>
                                    <div class="row">
                                        @foreach ($permissionGroup as $permission)
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="{{ $permission->id }}">
                                                    <label class="form-check-label" for="{{ $permission->id }}">
                                                        {{ $permission->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                        </div>

                        <button class="btn btn-primary mr-1 btn-submit" type="submit"><i class="fa fa-paper-plane"></i>
                            SIMPAN</button>
                        <button class="btn btn-warning btn-reset" type="reset"><i class="fa fa-redo"></i> RESET</button>

                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@stop