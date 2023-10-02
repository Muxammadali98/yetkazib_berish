@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Yangi savol qo'shish</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('question.index') }}" class="btn btn-primary">Ortga</a>
                        </div>
                    </div>
                    <form action="{{ route('question.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Savol (O'zbek tilida)</label>
                                    <textarea class="ckeditor form-control @error('title_uz') is-invalid @enderror" name="title_uz" required>{{ old('title_uz') }}</textarea>
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
                                    <textarea class="ckeditor form-control @error('title_ru') is-invalid @enderror" name="title_ru" required>{{ old('title_ru') }}</textarea>
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
                                    <input type="text" class="form-control @error('pdf_title_uz') is-invalid @enderror" name="pdf_title_uz" value="{{ old('pdf_title_uz') }}">
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
                                    <input type="text" class="form-control @error('pdf_title_ru') is-invalid @enderror" name="pdf_title_ru"  value="{{ old('pdf_title_ru') }}">
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
                                            <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>{{ $value }}</option>
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
                        <div class="row" id="btn-row"></div>
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
            if ($(this).val() == <?= \App\Models\Question::TYPE_WITH_A_BUTTON ?>) {
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
                $('#question-type option:selected').prop('selected', false)
                $("#btn-row").removeAttr('data-count')
            }
        })
    </script>
@endsection
