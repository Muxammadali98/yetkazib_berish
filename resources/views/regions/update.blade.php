@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Salomlashuv xabarini o'zgartirish</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('region.index') }}" class="btn btn-primary">Ortga</a>
                        </div>
                    </div>
                    <form action="{{ route('region.update', $model->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Filial nomi (O'zbek tilida)</label>
                                    <input type="text" name="name_uz" class="form-control @error('name_uz') is-invalid @enderror" value="{{ $model->name_uz }}" required>
                                    @error('name_uz')
                                    <div class="invalid-feedback">
                                        Filial nomi (O'zbek tilida) to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Filial nomi (O'zbek tilida)</label>
                                    <input type="text" name="name_ru" class="form-control @error('name_ru') is-invalid @enderror" value="{{ $model->name_ru }}" required>
                                    @error('name_ru')
                                    <div class="invalid-feedback">
                                        Filial nomi (O'zbek tilida) to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Xabar matni (O'zbek tilida)</label>
                                    <textarea class="ckeditor form-control @error('body_uz') is-invalid @enderror" name="body_uz" required>{{ $model->body_uz }}</textarea>
                                    @error('body_uz')
                                    <div class="invalid-feedback">
                                        Xabar matni (O'zbek tilida) to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Xabar matni (Rus tilida)</label>
                                    <textarea class="ckeditor form-control @error('body_ru') is-invalid @enderror" name="body_ru" required>{{ $model->body_ru }}</textarea>
                                    @error('body_ru')
                                    <div class="invalid-feedback">
                                        Xabar matni (Rus tilida) to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Filial manzili linki</label>
                                    <input type="text" name="address_link" class="form-control @error('address_link') is-invalid @enderror" value="{{ $model->address_link }}" required>
                                    @error('address_link')
                                    <div class="invalid-feedback">
                                        Filial manzili linki to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group d-flex justify-content-start flex-column">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-success d-inline-block" style="width: fit-content">Saqlash</button>
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
    @include('layouts.ckeditor')
@endsection
