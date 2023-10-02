@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Kategoriya: {{$model->title_uz}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end" style="gap: 5px;">
                            <a href="{{ route('category.index') }}" class="btn btn-primary">Ortga</a>
                            <a href="{{ route('category.edit', $model->id) }}" class="btn btn-info">O'zgartirish</a>
                            <form action="{{ route('category.destroy', $model->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">{{ $model->status == \App\Models\Category::STATUS_ACTIVE ? 'O\'chirish' : 'Qayta tiklash' }}</button>
                            </form>
                        </div>
                    </div>
                    <table class="table table-bordered table-md">
                        <tbody>
                        <tr>
                            <th>Kategotiya turi</th>
                            <td>
                                {{ $model::getStep()[$model->step] }}
                            </td>
                        </tr>
                        <tr>
                            <th>Asosiy kategoriyasi</th>
                            <td>
                                {{ $model->parent->title_uz ?? 'Yo\'q' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Nomi (O'zbek tilida)</th>
                            <td>
                                {!! $model->title_uz !!}
                            </td>
                        </tr>
                        <tr>
                            <th>Nomi (Rus tilida)</th>
                            <td>
                                {!! $model->title_ru !!}
                            </td>
                        </tr>
                        <tr>
                            <th>PDF</th>
                            <td>
                                <a href="{{ asset('uploads/documents/' . $model->file) }}" target="_blank">Ko'rish</a>
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                {{ $model::getStatus()[$model->status] }}
                            </td>
                        </tr>
                        <tr>
                            <th>Yaratilgan vaqt</th>
                            <td>
                                {{$model->created_at}}
                            </td>
                        </tr>
                        <tr>
                            <th>O'zgartirilgan vaqt</th>
                            <td>
                                {{$model->updated_at}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
