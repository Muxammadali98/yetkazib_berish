@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="/assets/bundles/select2/dist/css/select2.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Bildirishnoma qo'shish</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('additional-notice.index') }}" class="btn btn-primary">Ortga</a>
                        </div>
                    </div>
                    <form action="{{ route('additional-notice.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Xabar mavzusi (O'zbek tilida)</label>
                                    <input type="text" class="form-control @error('title_uz') is-invalid @enderror" name="title_uz" value="{{ old('title_uz') }}" required>
                                    @error('title_uz')
                                    <div class="invalid-feedback">
                                        Xabar mavzusi (O'zbek tilida) to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Xabar mavzusi (Rus tilida)</label>
                                    <input type="text" class="form-control @error('title_ru') is-invalid @enderror" name="title_ru" value="{{ old('title_ru') }}" required>
                                    @error('title_ru')
                                    <div class="invalid-feedback">
                                        Xabar mavzusi (Rus tilida) to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Xabar matni (O'zbek tilida)</label>
                                    <textarea class="ckeditor form-control @error('message_uz') is-invalid @enderror" name="message_uz" required>{{ old('message_uz') }}</textarea>
                                    @error('message_uz')
                                    <div class="invalid-feedback">
                                        Xabar matni (O'zbek tilida) to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Xabar matni (Rus tilida)</label>
                                    <textarea class="ckeditor form-control @error('message_ru') is-invalid @enderror" name="message_ru" required>{{ old('message_ru') }}</textarea>
                                    @error('message_ru')
                                    <div class="invalid-feedback">
                                        Xabar matni (Rus tilida) to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Filiallar</label>
                                    <select name="regions[]" class="form-control select2 @error('regions') is-invalid @enderror"  multiple required>
                                        @foreach($regions as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('regions')
                                    <div class="invalid-feedback">
                                        Filiallar to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Xabar rasmi (Ixtiyoriy)</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" name="image">
                                    @error('image')
                                    <div class="invalid-feedback">
                                        Rasm jpg, jpeg, png bo'lishi va 20 mb dan oshmasligi kerak
                                    </div>
                                    @enderror
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
