@extends('layouts.app')

@php
    $language = \App\Models\Answer::LANGUAGE_UZ;
    if (!empty($answers)) {
        $language = $answers[0]->language;
    }
@endphp

@section('styles')
    <style>
        * {
            font-family: 'DejaVu Sans', sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 80%;
        }

        tr {
            border: 1px solid;
        }

        td {
            border: 1px solid;
        }

        th {
            border: 1px solid;
        }
    </style>
    <link href="/assets/bundles/lightgallery/dist/css/lightgallery.css" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">

            @if(count($comments) > 0)
                <div class="card">
                <div class="cart-body">
                    <div class="row">
                        <div class="col-md-6 d-flex justify-content-start">
                            <h2 class="m-3">Izohlar</h2>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            <a href="{{ url()->previous() }}" class="btn btn-primary m-3" >Ortga</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-11" style="margin: auto; width: 90%">
                            <table class="table table-bordered mt-2">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Foydalanuchi</th>
                                    <th>Izoh</th>
                                    <th>Holati</th>
                                    <th>Vaqt</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($comments as $comment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $comment->user->name }}</td>
                                        <td>{{ $comment->comment }}</td>
                                        <td>{!! $comment->getStatus() !!}</td>
                                        <td>{{ $comment->created_at }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">
                                            <p class="text-center">Hechnima topilmadi</p>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="card">
                <div class="cart-body">
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end">

                            <a href="#" class="btn btn-primary update-app m-3" data-toggle="modal" title="O'zgartirish" data-id="{{ $answers[0]->application_id }}" data-url="{{ route('answer.managerUpdate', $answers[0]->application_id) }}" data-name="{{ $answers[0]->client->full_name }}" data-target="#exampleModalCenter"><i class="fas fa-pencil-alt"></i></a>
                            <a href="#" class="btn btn-success accept-app m-3" data-toggle="modal" title="Qabul qilish" data-id="{{ $answers[0]->application_id }}" data-url="{{ route('answer.managerAccepted', $answers[0]->application_id) }}" data-name="{{ $answers[0]->client->full_name }}" data-target="#exampleModalCenter"><i class="fas fa-check"></i></a>
                            <a href="#" class="btn btn-danger close-app m-3" data-toggle="modal" title="Bekor qilish" data-id="{{ $answers[0]->application_id }}" data-url="{{ route('answer.managerCancel', $answers[0]->application_id) }}" data-name="{{ $answers[0]->client->full_name }}" data-target="#exampleModalCenter"><i class="fas fa-times"></i></a>
                            <a href="{{ route('answer.pdf', $answers[0]->application_id) }}" class="btn btn-primary m-3" data-toggle="tooltip" title="PDF yuklash">PDF <i class="fas fa-download"></i></a>
                        </div>
                    </div>
                    <div class="container-fluid  p-5">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="text-center" style="margin-bottom: 40px">
                                    @if($language == \App\Models\Answer::LANGUAGE_UZ)
                                        BUYURTMA MA'LUMOTLARI
                                    @endif
                                    @if($language == \App\Models\Answer::LANGUAGE_RU)
                                        ЗАПРОСИТЬ ИНФОРМАЦИЮ
                                    @endif
                                </h4>
                            </div>
                        </div>
                        <div id="aniimated-thumbnials" class="list-unstyled row clearfix">
                            @foreach($images_src as $image_src)
                                <div class="col-md-3 ">
                                    <a href="{{ $image_src }}">
                                        <img class="img-responsive thumbnail " src="{{ $image_src }}" alt="" style="width: 320px; height: 300px; object-fit: contain">
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <table class="table">
                            <tr>
                                <th style="width: 10%; padding: 5px 10px;">№</th>
                                <th style="width: 45%; padding: 5px 10px;">{{ $language == \App\Models\Answer::LANGUAGE_RU ? 'Вопрос' : 'Savol' }}</th>
                                <th style="width: 45%; padding: 5px 10px;">{{ $language == \App\Models\Answer::LANGUAGE_RU ? 'Ответ' : 'Javob' }}</th>
                            </tr>
                            @php $i = 2; @endphp
                            @foreach($answers as $answer)
                                @if($loop->iteration == 1)
                                    <tr>
                                        <th style="padding: 5px 10px;">{{ 1 }}</th>
                                        <td style="padding: 5px 10px;">
                                            @if($language == \App\Models\Answer::LANGUAGE_UZ)
                                                Filial:
                                            @endif
                                            @if($language == \App\Models\Answer::LANGUAGE_RU)
                                                Филиал:
                                            @endif
                                        </td>
                                        <td style="padding: 5px 10px;">
                                            {{ $language == \App\Models\Answer::LANGUAGE_RU ? $answer->region->name_ru : $answer->region->name_uz }}
                                        </td>
                                    </tr>
                                @endif
                                @if($answer->question->type != \App\Models\Question::TYPE_PHOTO)
                                    <tr>
                                        <th style="padding: 5px 10px;">{{ $i++ }}</th>
                                        <td style="padding: 5px 10px;">{{ $language == \App\Models\Answer::LANGUAGE_RU ? $answer->question->pdf_title_ru : $answer->question->pdf_title_uz }}</td>
                                        <td style="padding: 5px 10px;">{{ $answer->text }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end">
                            <a href="#" class="btn btn-primary update-app m-3" data-toggle="modal" title="O'zgartirish" data-id="{{ $answers[0]->application_id }}" data-url="{{ route('answer.managerUpdate', $answers[0]->application_id) }}" data-name="{{ $answers[0]->client->full_name }}" data-target="#exampleModalCenter"><i class="fas fa-pencil-alt"></i></a>
                            <a href="#" class="btn btn-success accept-app m-3" data-toggle="modal" title="Qabul qilish" data-id="{{ $answers[0]->application_id }}" data-url="{{ route('answer.managerAccepted', $answers[0]->application_id) }}" data-name="{{ $answers[0]->client->full_name }}" data-target="#exampleModalCenter"><i class="fas fa-check"></i></a>
                            <a href="#" class="btn btn-danger close-app m-3" data-toggle="modal" title="Bekor qilish" data-id="{{ $answers[0]->application_id }}" data-url="{{ route('answer.managerCancel', $answers[0]->application_id) }}" data-name="{{ $answers[0]->client->full_name }}" data-target="#exampleModalCenter"><i class="fas fa-times"></i></a>
                            <a href="{{ route('answer.pdf', $answers[0]->application_id) }}" class="btn btn-primary m-3" data-toggle="tooltip" title="PDF yuklash">PDF <i class="fas fa-download"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="/assets/bundles/lightgallery/dist/js/lightgallery-all.js"></script>
    <script src="/assets/js/page/light-gallery.js"></script>
    <script>
        $(document).ready(function () {
            $('.accept-app').click(function () {
                let url = $(this).data('url');
                let name = $(this).data('name');
                let modal = $('#exampleModalCenter');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (response) {
                        if (response.status === 'success') {
                            modal.find('.modal-title').text(name);
                            modal.find('.btn-primary').addClass('accept-app-btn').attr('data-url', url);
                            modal.find('.modal-body').html(response.content);
                        }
                    },
                });
            });

            $(document).on('click', '.accept-app-btn', function () {
                let url = $(this).data('url');
                let modal = $('#exampleModalCenter');
                let comment = modal.find('#comment').val();
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        comment: comment,
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            modal.modal('hide');
                            location.reload();
                        }
                    },
                });
            });
            $('.update-app').click(function () {
                let url = $(this).data('url');
                let name = $(this).data('name');
                let modal = $('#exampleModalCenter');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (response) {
                        if (response.status === 'success') {
                            modal.find('.modal-title').text(name);
                            modal.find('.btn-primary').addClass('accept-app-btn').attr('data-url', url);
                            modal.find('.modal-body').html(response.content);
                        }
                    },
                });
            });

            $(document).on('click', '.accept-app-btn', function () {
                let url = $(this).data('url');
                let modal = $('#exampleModalCenter');
                let comment = modal.find('#comment').val();
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        comment: comment,
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            modal.modal('hide');
                            location.reload();
                        }
                    },
                });
            });

            $('.close-app').click(function () {
                let url = $(this).data('url');
                let name = $(this).data('name');
                let modal = $('#exampleModalCenter');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (response) {
                        if (response.status === 'success') {
                            modal.find('.modal-title').text(name);
                            modal.find('.btn-primary').addClass('close-app-btn').attr('data-url', url);
                            modal.find('.modal-body').html(response.content);
                        }
                    },
                });
            });

            $(document).on('click', '.close-app-btn', function () {
                let url = $(this).data('url');
                let modal = $('#exampleModalCenter');
                let comment = modal.find('#comment').val();
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        comment: comment,
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            modal.modal('hide');
                            location.reload();
                        }
                    },
                });
            });
        });
    </script>
@endsection
