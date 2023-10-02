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
                            <a href="{{ route('start.index') }}" class="btn btn-primary">Ortga</a>
                        </div>
                    </div>
                    <form action="{{ route('start.update', $model->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Xabar matni (O'zbek tilida)</label>
                                    <textarea class="ckeditor form-control @error('message_uz') is-invalid @enderror"
                                              name="message_uz" required>{{ $model->message_uz }}</textarea>
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
                                    <textarea class="ckeditor form-control @error('message_ru') is-invalid @enderror"
                                              name="message_ru" required>{{ $model->message_ru }}</textarea>
                                    @error('message_ru')
                                    <div class="invalid-feedback">
                                        Xabar matni (Rus tilida) to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @if($model->type == \App\Models\Start::TYPE_PHOTO)
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Mavjud rasm o'zgarmasin</label>
                                        <input type="checkbox" class="form-control" name="img-check" checked>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group d-flex justify-content-start flex-column">
                                        <label>&nbsp;</label>
                                        <img src="/uploads/images/{{ $model->image }}" style="width: 100px;">
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Xabar rasmi (Ixtiyoriy)</label>
                                    <input type="file" class="form-control @error('img') is-invalid @enderror"
                                           name="img">
                                    @error('img')
                                    <div class="invalid-feedback">
                                        Rasm jpg, jpeg, png bo'lishi va 20 mb dan oshmasligi kerak
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group d-flex justify-content-start flex-column">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-success d-inline-block"
                                            style="width: fit-content">Saqlash
                                    </button>
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
