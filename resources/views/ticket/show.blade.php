@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $model->client_name }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end" style="gap: 5px;">
                            <a href="{{ route('ticket.index') }}" class="btn btn-primary">Ortga</a>
                            <a href="{{ route('ticket.edit', $model->id) }}" class="btn btn-info">O'zgartirish</a>
                            <form action="{{ route('ticket.destroy', $model->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">{{ !$model->trashed() ? 'O\'chirish' : 'Qayta tiklash' }}</button>
                            </form>
                        </div>
                    </div>
                    <table class="table table-bordered table-md">
                        <tbody>
                        <tr>
                            <th>Shartnoma raqami</th>
                            <td>
                                {{ $model->contract_id }}
                            </td>
                        </tr>
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
                            <th>Mijoz</th>
                            <td>
                                {{ $model->client_name }}
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
                            <th>Izoh</th>
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
                            <th>Status</th>
                            <td>
                                {{ $model::getTypes()[$model->type] }}
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

                    <table class="table table-bordered table-md">
                        <thead>
                            <tr>
                                <th class="text-center bg-amber" colspan="4">
                                    Mahsulotlar
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    Mahsulot nomi
                                </th>
                                <th>
                                    Modeli
                                </th>
                                <th>
                                    Artikuli
                                </th>
                                <th>
                                    Miqdori
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($model->products as $product)
                                <tr>
                                    <td>
                                        {{ $product->product_name }}
                                    </td>
                                    <td>
                                        {{ $product->model }}
                                    </td>
                                    <td>
                                        {{ $product->article }}
                                    </td>
                                    <td>
                                        {{ $product->quantity }}
                                    </td>
                                </tr>
                            @endforeach
                    </table>

                    @isset($model->supplierAction)
                        <table class="table table-bordered table-md">
                            <thead>
                                <tr>
                                    <th class="text-center bg-amber" colspan="4">
                                        Yetkazib beruvchi tomonidan amalga oshirilgan harakat
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        Izoh
                                    </th>
                                    <th>
                                        Manzil
                                    </th>
                                    <th>
                                        Yetkazib berilgan sana
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        {{ $model->supplierAction->comment }}
                                    </td>
                                    <td>
                                        {{ \App\Services\GoogleMapService::getAddress($model->supplierAction->lat, $model->supplierAction->lng) }}
                                    </td>
                                    <td>
                                        {{ $model->supplierAction->created_at }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @endisset

                    @if(isset($model->supplierAction->supplierFiles) && !empty($model->supplierAction->supplierFiles))
                        <div class="row">
                            <div class="col-md-12">
                                <div>
                                    <div class="text-center bg-amber">
                                        <p>Yetkazib beruvchi tomonidan amalga oshirilgan harakatga qo'shilgan fayllar</p>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach($model->supplierAction->supplierFiles as $file)
                                                <div class="col-md-3">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <img src="{{ asset('uploads/images/' . $file->file) }}" alt="" class="img-fluid">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
