@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Savolni tahrirlash</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('question.index') }}" class="btn btn-primary">Ortga</a>
                        </div>
                    </div>
                    <form action="{{ route('question.update', $question->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Savol (O'zbek tilida)</label>
                                    <textarea class="ckeditor form-control @error('title_uz') is-invalid @enderror" name="title_uz" required>{{ $question->title_uz }}</textarea>
                                    @error('title_uz')
                                    <div class="invalid-feedback">
                                        Savol (O'zbek tilida) to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Savol (Rus tilida)</label>
                                    <textarea class="ckeditor form-control @error('title_ru') is-invalid @enderror" name="title_ru" required>{{ $question->title_ru }}</textarea>
                                    @error('title_ru')
                                    <div class="invalid-feedback">
                                        Savol (Rus tilida) to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Savolning PDF dagi ko'rinishi (O'zbek tilida)</label>
                                    <input type="text" class="form-control @error('pdf_title_uz') is-invalid @enderror" name="pdf_title_uz" value="{{ $question->pdf_title_uz }}">
                                    @error('pdf_title_uz')
                                    <div class="invalid-feedback">
                                        Savolning PDF dagi ko'rinishi (O'zbek tilida) to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Savolning PDF dagi ko'rinishi (Rus tilida)</label>
                                    <input type="text" class="form-control @error('pdf_title_ru') is-invalid @enderror" name="pdf_title_ru"  value="{{ $question->pdf_title_ru }}">
                                    @error('pdf_title_ru')
                                    <div class="invalid-feedback">
                                        Savolning PDF dagi ko'rinishi (Rus tilida) to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Savol turini tanlang</label>
                                    <select class="form-control @error('type') is-invalid @enderror" id="question-type" name="type" required>
                                        @forelse(\App\Models\Question::getType() as $key => $value)
                                            <option value="{{ $key }}" {{ $question->type == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @empty
                                            <option value="">Savol turi qo'shilmagan</option>
                                        @endforelse
                                    </select>
                                    @error('type')
                                    <div class="invalid-feedback">
                                        Savol turi to'ldirilishi shart
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row" id="btn-row" @if($question->type == \App\Models\Question::TYPE_WITH_A_BUTTON) data-count="{{ count($question->buttons) - 1 }}" @endif>
                            @foreach($question->buttons as $item)
                                <div class="row col-12">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Tugma (O'zbek tilida)</label>
                                            <input type="text" class="form-control @error('text_uz') is-invalid @enderror" name="text_uz[{{ $loop->index }}]" required  value="{{ $item->text_uz }}">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Tugma (Rus tilida)</label>
                                            <input type="text" class="form-control @error('text_ru') is-invalid @enderror" name="text_ru[{{ $loop->index }}]" required  value="{{ $item->text_ru }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group d-flex justify-content-start flex-column">
                                            <label>&nbsp</label>
                                            <div class="d-flex" style="margin-top: 5px; gap: 10px">
                                                <button type="button" class="btn btn-success d-inline-block plus" style="width: fit-content"><i class="fas fa-plus"></i></button>
                                                <button type="button" class="btn btn-danger d-inline-block minus" style="width: fit-content"><i class="fas fa-minus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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
    @include('layouts.ckeditor')
    <script>
        $(document).on('change', '#question-type', function () {
            if ($(this).is(":checked")) {
                $('#question-type-photo').prop('checked', false);
                $.ajax({
                    url: '/question/create-button',
                    dataType: 'json',
                    type: 'POST',
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    data: {item: 0},
                    success: function (response) {
                        if (response.status === 'success') {
                            $("#btn-row").attr('data-count', '0').append(response.content)
                        }
                    }
                })
            } else {
                $("#btn-row").html('').attr('data-count', '0')
            }
        })
        $(document).on('click', '.plus', function () {
            let count = parseInt($("#btn-row").attr('data-count'));
            $.ajax({
                url: '/question/create-button',
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
            if (count < 0) {
                $('#question-type').prop('checked', false)
                $("#btn-row").removeAttr('data-count')
            }
        })
        $(document).on('change', '#question-type-photo', function () {
            if ($(this).is(":checked")) {
                if ($('#question-type').is(":checked")) {
                    $('#question-type').trigger('click')
                }
            }
        })
    </script>
@endsection
