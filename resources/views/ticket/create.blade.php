@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Yangi ticket ochish</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('ticket.index') }}" class="btn btn-primary">Ortga</a>
                        </div>
                    </div>
                    <form action="{{ route('ticket.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Yetkazib beruvchi</label>
                                    <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                        <option value="">Tanlang</option>
                                        @foreach($delivery as $key => $value)
                                            <option value="{{ $key }}" {{ old('user_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
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
                                    <label>Ticket turi</label>
                                    <select name="type" class="form-control @error('type') is-invalid @enderror" required>
                                        @foreach(\App\Models\Ticket::getTypes() as $key => $value)
                                            <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                    <div class="invalid-feedback">
                                        Ticket turi to'ldirilishi shart
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
                                            <option value="{{ $key }}" {{ old('car_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                    <div class="invalid-feedback">
                                        Mashina to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Mijoz ismi</label>
                                    <input type="text" name="client_name" class="form-control @error('client_name') is-invalid @enderror" value="{{ old('client_name') }}" required>
                                    @error('client_name')
                                    <div class="invalid-feedback">
                                        Mijoz ismi to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Manzil</label>
                                    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}" required>
                                    @error('address')
                                    <div class="invalid-feedback">
                                        Manzil to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Telefon raqam</label>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                                    @error('phone')
                                    <div class="invalid-feedback">
                                        Telefon raqam to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Shartnoma raqami</label>
                                    <input type="text" name="contract_id" class="form-control @error('contract_id') is-invalid @enderror" value="{{ old('contract_id') }}" required>
                                    @error('contract_id')
                                    <div class="invalid-feedback">
                                        Shartnoma raqami to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Qo'shimcha Telefon raqam</label>
                                    <input type="text" name="additional_phone" class="form-control @error('additional_phone') is-invalid @enderror" value="{{ old('additional_phone') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Kommentariya</label>
                                    <textarea class="form-control @error('comment') is-invalid @enderror" name="comment">{{ old('comment') }}</textarea>
                                    @error('comment')
                                    <div class="invalid-feedback">
                                        Kommentariya 255 ta belgidan oshmasligi kerak
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row" id="btn-row" data-count="1">
                            <div class="row col-12">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Mahsulot nomi</label>
                                        <input type="text" class="form-control" name="product_name[{{ $i ?? 0 }}]" required  value="{{ old('product_name.0') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Model</label>
                                        <input type="text" class="form-control" name="model[{{ $i ?? 0 }}]" required  value="{{ old('model.0') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Artikul</label>
                                        <input type="text" class="form-control" name="article[{{ $i ?? 0 }}]" required  value="{{ old('article.0') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Miqdori</label>
                                        <input type="number" class="form-control" name="quantity[{{ $i ?? 0 }}]" required  value="{{ old('quantity.0') }}">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group d-flex justify-content-start flex-column">
                                        <label>&nbsp</label>
                                        <div class="d-flex" style="margin-top: 5px; gap: 10px">
                                            <button type="button" class="btn btn-success d-inline-block plus" style="width: fit-content"><i class="fas fa-plus"></i></button>
                                        </div>
                                    </div>
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
@section('js')
    <script>
        $(document).on('click', '.plus', function () {
            let count = parseInt($("#btn-row").attr('data-count'));
            $.ajax({
                url: '/ticket/add-product',
                dataType: 'json',
                type: 'POST',
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                data: {item: ++count},
                success: function (response) {
                    if (response.status === 'success') {
                        $("#btn-row").attr('data-count', count).append(response.content)
                    }
                }
            })
        })
        $(document).on('click', '.minus', function () {
            let count = parseInt($("#btn-row").attr('data-count'));
            $(this).parent().parent().parent().parent().remove()
            $("#btn-row").attr('data-count', --count)
        })

        $(document).on('change', '#user_id', function () {
            let user_id = $(this).val();
            $.ajax({
                url: '/ticket/' + user_id + '/car',
                dataType: 'json',
                type: 'GET',
                success: function (response) {
                    $('#car_id').html(response.content)
                }
            })
        })
    </script>
@endsection

