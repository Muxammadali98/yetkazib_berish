@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Javob xabari</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end" style="gap: 5px;">
                            <a href="{{ route('message.index') }}" class="btn btn-primary">Ortga</a>
                            <a href="{{ route('message.edit', $model->id) }}" class="btn btn-info">O'zgartirish</a>
                            <form action="{{ route('message.destroy', $model->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">{{ $model->status == \App\Models\Message::STATUS_ACTIVE ? 'O\'chirish' : 'Qayta tiklash' }}</button>
                            </form>
                        </div>
                    </div>
                    <table class="table table-bordered table-md">
                        <tbody>
                        <tr>
                            <th>Xabar nomi</th>
                            <td>
                                {{ $model->title }}
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
                                {!! $model->body_uz !!}
                            </td>
                        </tr>
                        <tr>
                            <th>Xabar matni (Rus tilida)</th>
                            <td>
                                {!! $model->body_ru !!}
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
