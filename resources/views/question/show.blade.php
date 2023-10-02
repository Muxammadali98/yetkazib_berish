@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ 'Savol (O\'zbek tilida): ' . $model->title_uz }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end" style="gap: 5px;">
                            <a href="{{ route('question.index') }}" class="btn btn-primary">Ortga</a>
                            <a href="{{ route('question.edit', $model->id) }}" class="btn btn-info">O'zgartirish</a>
                            <div class="checkbox-custom checkbox-primary d-flex align-content-center">
                                <input type="checkbox" name="status" data-id="{{ $model->id }}" {{ $model->status == \App\Models\Question::STATUS_ACTIVE ? 'checked' : '' }} style="width: 34px;" id="modelStatus">
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-md">
                        <tbody>
                        <tr>
                            <th>Savol turi</th>
                            <td>
                                {{ \App\Models\Question::getType()[$model->type] }}
                            </td>
                        </tr>
                        <tr>
                            <th>Tugmalar (O'zbek tilida)</th>
                            <td>
                                @forelse($model->buttons as $button)
                                    <span class="badge badge-primary">{{ $button->text_uz }}</span>
                                @empty
                                    <span>Tugmalar yo'q</span>
                                @endforelse
                            </td>
                        </tr>
                        <tr>
                            <th>Tugmalar (Rus tilida)</th>
                            <td>
                                @forelse($model->buttons as $button)
                                    <span class="badge badge-primary">{{ $button->text_ru }}</span>
                                @empty
                                    <span>Tugmalar yo'q</span>
                                @endforelse
                            </td>
                        </tr>
                        <tr>
                            <th>Savol (O'zbek tilida)</th>
                            <td>
                                {!! $model->title_uz !!}
                            </td>
                        </tr>
                        <tr>
                            <th>Savol (Rus tilida)</th>
                            <td>
                                {!! $model->title_ru !!}
                            </td>
                        </tr>
                        <tr>
                            <th>Savolning PDF dagi ko'rinishi (O'zbek tilida)</th>
                            <td>
                                {!! $model->pdf_title_uz !!}
                            </td>
                        </tr>
                        <tr>
                            <th>Savolning PDF dagi ko'rinishi (Rus tilida)</th>
                            <td>
                                {!! $model->pdf_title_ru !!}
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

@section('js')
    <script>
        $(document).on('change', '#modelStatus', function () {
            let id = $(this).attr('data-id');
            $.ajax({
                url: '/question/destroy',
                type: 'DELETE',
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                data: {
                    id: id,
                },
                success: function (response) {
                    if (response.status === 'success') {
                        window.location.reload();
                    }
                }
            })
        })
    </script>
@endsection
