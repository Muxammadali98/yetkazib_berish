@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Salomlashuv xabari</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end" style="gap: 5px;">
                            <a href="{{ route('start.index') }}" class="btn btn-primary">Ortga</a>
                            <a href="{{ route('start.edit', $model->id) }}" class="btn btn-info">O'zgartirish</a>
                            <form action="{{ route('start.destroy', $model->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">{{ $model->status == \App\Models\Start::STATUS_ACTIVE ? 'O\'chirish' : 'Qayta tiklash' }}</button>
                            </form>
                        </div>
                    </div>
                    <table class="table table-bordered table-md">
                        <tbody>
                        <tr>
                            <th>Rasm</th>
                            <td>
                                @if($model->type == \App\Models\Start::TYPE_PHOTO)
                                    <img src="{{ '/uploads/images/' . $model->image }}" style="width: 100px;">
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Xabar turi</th>
                            <td>
                                {{ $model::getType()[$model->type] }}
                            </td>
                        </tr>
                        <tr>
                            <th>Xabar matni (O'zbek tilida)</th>
                            <td>
                                {!! $model->message_uz !!}
                            </td>
                        </tr>
                        <tr>
                            <th>Xabar matni (Rus tilida)</th>
                            <td>
                                {!! $model->message_ru !!}
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
