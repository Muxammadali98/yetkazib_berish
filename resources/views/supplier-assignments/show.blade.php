@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            @if(session()->has('warning'))
                <div class="alert alert-warning alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>×</span>
                        </button>
                        {{ session()->get('warning') }}
                    </div>
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4>{{ $model->user->name }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end" style="gap: 5px;">
                            <a href="{{ route('supplier-assignment.index') }}" class="btn btn-primary">Ortga</a>
                            @if(!$model->isDone() && !$model->trashed())
                                <a href="{{ route('supplier-assignment.edit', $model->id) }}" class="btn btn-info">O'zgartirish</a>
                                <form action="{{ route('supplier-assignment.destroy', $model->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">{{ !$model->trashed() ? 'O\'chirish' : 'Qayta tiklash' }}</button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <table class="table table-bordered table-md">
                        <tbody>
                        <tr>
                            <th>Yetkazib beruvchi</th>
                            <td>
                                {{ $model->user->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>Mashina</th>
                            <td>
                                {{ $model->car->name ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Topshiriq</th>
                            <td>
                                {!! $model->description !!}
                            </td>
                        </tr>
                        <tr>
                            <th>Telefon raqam</th>
                            <td>
                                {!! $model->phone !!}
                            </td>
                        </tr>
                        <tr>
                            <th>Qo'shimcha telefon raqam</th>
                            <td>
                                {!! $model->additional_phone !!}
                            </td>
                        </tr>
                        <tr>
                            <th>Manzil</th>
                            <td>
                                {!! $model->address !!}
                            </td>
                        </tr>
                        <tr>
                            <th>Yetkazib beruvchining izohi</th>
                            <td>
                                {!! $model->comment !!}
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
