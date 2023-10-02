@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Topshiriqni o'zgartirish</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('supplier-assignment.index') }}" class="btn btn-primary">Ortga</a>
                        </div>
                    </div>
                    <form action="{{ route('supplier-assignment.update', $model->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Yetkazib beruvchi</label>
                                    <select name="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                        <option value="">Tanlang</option>
                                        @foreach($delivery as $key => $value)
                                            <option value="{{ $key }}" {{ $model->user_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                    <div class="invalid-feedback">
                                        Yetkazib beruvchi to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Mashina</label>
                                    <select name="car_id" id="car_id" class="form-control @error('car_id') is-invalid @enderror" required>
                                        <option value="">Tanlang</option>
                                        @foreach($cars as $key => $value)
                                            <option value="{{ $key }}" {{ $model->car_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('car_id')
                                    <div class="invalid-feedback">
                                        Mashina to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Topshiriq nomi</label>
                                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ $model->title }}" required>
                                    @error('title')
                                    <div class="invalid-feedback">
                                        Topshiriq nomi to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Telefon raqam</label>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ $model->phone }}" required>
                                    @error('phone')
                                    <div class="invalid-feedback">
                                        Telefon raqam to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Qo'shimcha telefon raqam</label>
                                    <input type="text" name="additional_phone" class="form-control @error('additional_phone') is-invalid @enderror" value="{{ $model->additional_phone }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Manzil</label>
                                    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ $model->address }}" required>
                                    @error('address')
                                    <div class="invalid-feedback">
                                        Manzil to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Topshiriq</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description">{{ $model->description }}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback">
                                        Topshiriq 255 ta belgidan oshmasligi kerak
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
