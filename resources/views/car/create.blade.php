@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="/assets/bundles/select2/dist/css/select2.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Mashina qo'shish</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('car.index') }}" class="btn btn-primary">Ortga</a>
                        </div>
                    </div>
                    <form action="{{ route('car.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomi</label>
                                    <input name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                    @error('name')
                                    <div class="invalid-feedback">
                                        Nomi to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mashina raqami</label>
                                    <input name="number" class="form-control @error('number') is-invalid @enderror" value="{{ old('number') }}" required>
                                    @error('number')
                                    <div class="invalid-feedback">
                                        Mashina raqami to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mashina Rangi</label>
                                    <input name="color" class="form-control @error('color') is-invalid @enderror" value="{{ old('color') }}" required>
                                    @error('color')
                                    <div class="invalid-feedback">
                                        Mashina rangi to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Filiallar</label>
                                    <select name="region_id" class="form-control">
                                        <option value="">Tanlang..</option>
                                        @foreach($regionList as $key => $value)
                                            <option value="{{ $key }}" @if($key == old('region_id')) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Xaydovchilar</label>
                                    <select name="user_id[]" class="form-control select2" multiple>
                                        @foreach($userList as $id => $name)
                                            <option value="{{ $id }}" @if($id == old('user_id')) selected @endif>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">Saqlash</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/bundles/select2/dist/js/select2.full.min.js"></script>
@endsection
