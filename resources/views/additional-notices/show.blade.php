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
                            <a href="{{ route('additional-notice.index') }}" class="btn btn-primary">Ortga</a>
                            <a href="{{ route('additional-notice.edit', $model->id) }}" class="btn btn-info">O'zgartirish</a>
                            <form action="{{ route('additional-notice.destroy', $model->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">{{ !$model->trashed() ? 'O\'chirish' : 'Qayta tiklash' }}</button>
                            </form>
                        </div>
                    </div>
                    <table class="table table-bordered table-md">
                        <tbody>
                        <tr>
                            <th>Rasm</th>
                            <td>
                                @if($model->type == \App\Models\AdditionalNotice::TYPE_WITH_IMAGE)
                                    <img src="{{ '/uploads/images/' . $model->image }}" style="width: 100px;">
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Xabar turi</th>
                            <td>
                                {{ $model::getTypes()[$model->type] }}
                            </td>
                        </tr>
                        <tr>
                            <th>Filiallar</th>
                            <td>
                                @forelse(\App\Repositories\AdditionalNoticeRepository::getRegions($model->id) as $value)
                                    <span class="badge badge-primary">{{ $value }}</span>
                                @empty
                                    <span>Filiallar yo'q</span>
                                @endforelse
                            </td>
                        </tr>
                        <tr>
                            <th>Xabar nomi (O'zbek tilida)</th>
                            <td>
                                {!! $model->title_uz !!}
                            </td>
                        </tr>
                        <tr>
                            <th>Xabar nomi (Rus tilida)</th>
                            <td>
                                {!! $model->title_ru !!}
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
                            <th>Holati</th>
                            <td>
                                {{ $model->getStatus() }}
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
