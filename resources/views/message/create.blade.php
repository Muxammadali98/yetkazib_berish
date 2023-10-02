@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Javob xabarini qo'shish</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('message.index') }}" class="btn btn-primary">Ortga</a>
                        </div>
                    </div>
                    <form action="{{ route('message.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Xabar nomi</label>
                                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                                    @error('title')
                                    <div class="invalid-feedback">
                                        Xabar nomi to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Xabar turi</label>
                                    <select name="type" class="form-control @error('type') is-invalid @enderror" value="{{ old('type') }}" required>
                                        @foreach(\App\Models\Message::getType() as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                    <div class="invalid-feedback">
                                        Xabar turi to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Xabar matni (O'zbek tilida)</label>
                                    <textarea class="ckeditor form-control @error('body_uz') is-invalid @enderror" name="body_uz" required>{{ old('body_uz') }}</textarea>
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
                                    <textarea class="ckeditor form-control @error('body_ru') is-invalid @enderror" name="body_ru" required>{{ old('body_ru') }}</textarea>
                                    @error('body_ru')
                                    <div class="invalid-feedback">
                                        Xabar matni (Rus tilida) to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
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
