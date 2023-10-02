@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Kategoriyani o'zgartirish</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('category.index') }}" class="btn btn-primary">Ortga</a>
                        </div>
                    </div>
                    <form action="{{ route('category.update', $model->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomi (O'zbek tilida)</label>
                                    <input name="title_uz" class="form-control @error('title_uz') is-invalid @enderror" value="{{ $model->title_uz }}" required>
                                    @error('title_uz')
                                    <div class="invalid-feedback">
                                        Nomi (O'zbek tilida) to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomi (Rus tilida)</label>
                                    <input name="title_ru" class="form-control @error('title_ru') is-invalid @enderror" value="{{ $model->title_ru }}" required>
                                    @error('title_ru')
                                    <div class="invalid-feedback">
                                        Nomi (Rus tilida) to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Asosiy kategoriya (Ixtiyoriy)</label>
                                    <select name="parent_id" class="form-control">
                                        <option value="">Asosiy kategoriya</option>
                                        @foreach($categoryList as $key => $value)
                                            <option value="{{ $key }}" @if($key == $model->parent_id) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kanal linki</label>
                                    <input name="channel_link" class="form-control @error('channel_link') is-invalid @enderror" value="{{ $model->channel_link }}" required>
                                    @error('channel_link')
                                    <div class="invalid-feedback">
                                        Kanal linki noto'g'ri
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fayl yuklang (PDF)</label>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror" name="file">
                                    @error('file')
                                    <div class="invalid-feedback">
                                        File pdf bo'lishi va 50 mb dan oshmasligi kerak
                                    </div>
                                    @enderror
                                </div>
                            </div>
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
