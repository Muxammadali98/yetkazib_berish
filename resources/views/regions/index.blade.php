@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Barcha Filiallar</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-right mb-3">
                            <a href="{{ route('region.create') }}" class="btn btn-success">Filial qo'shish</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Filial nomi (O'zbek tilida)</th>
                                <th>Filial nomi (Rus tilida)</th>
                                <th>Holati</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($regions as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{!! $item->name_uz !!}</td>
                                    <td>{!! $item->name_ru !!}</td>
                                    <td>{{ \App\Models\Start::getStatus()[$item->status] }}</td>
                                    <td class="d-flex" style="gap: 5px;">
                                        <a href="{{ route('region.show', $item->id) }}" class="btn btn-info"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('region.edit', $item->id) }}" class="btn btn-primary"><i class="fas fa-pencil-alt"></i></a>
                                        <form action="{{ route('region.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onClick="return confirm('Ishonchingiz komilmi?')"><i class="fas fa-{{ $item->status == \App\Models\Start::STATUS_ACTIVE ? 'trash-alt' : 'undo' }}"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <p class="text-center">Hechnima topilmadi</p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $regions->links() }}
            </div>
        </div>
    </div>
@endsection
