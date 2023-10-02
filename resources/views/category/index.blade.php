@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Barcha Kategoriyalar</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-right mb-3">
                            <a href="{{ route('category.create') }}" class="btn btn-success">Ma'lumot qo'shish</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nomi (O'zbek tilida)</th>
                                <th>Nomi (Rus tilida)</th>
                                <th>Holati</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($categories as $item)
                                @php $parent_iteration = $loop->iteration @endphp
                                <tr class="bg-warning">
                                    <td>{{ $parent_iteration }}</td>
                                    <td>{{ $item->title_uz }}</td>
                                    <td>{{ $item->title_ru }}</td>
                                    <td>{{ \App\Models\Category::getStatus()[$item->status] }}</td>
                                    <td class="d-flex" style="gap: 5px;">
                                        <a href="{{ route('category.show', $item->id) }}" class="btn btn-info"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('category.edit', $item->id) }}" class="btn btn-primary"><i class="fas fa-pencil-alt"></i></a>
                                        <form action="{{ route('category.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onClick="return confirm('Ishonchingiz komilmi?')"><i class="fas fa-{{ $item->status == \App\Models\Category::STATUS_ACTIVE ? 'trash-alt' : 'undo' }}"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @foreach($item->children as $item)
                                    <tr>
                                        <td>{{ $parent_iteration . ' - ' . $loop->iteration }}</td>
                                        <td>{{ $item->title_uz }}</td>
                                        <td>{{ $item->title_ru }}</td>
                                        <td>{{ \App\Models\Category::getStatus()[$item->status] }}</td>
                                        <td class="d-flex" style="gap: 5px;">
                                            <a href="{{ route('category.show', $item->id) }}" class="btn btn-info"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('category.edit', $item->id) }}" class="btn btn-primary"><i class="fas fa-pencil-alt"></i></a>
                                            <form action="{{ route('category.destroy', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" onClick="return confirm('Ishonchingiz komilmi?')"><i class="fas fa-{{ $item->status == \App\Models\Category::STATUS_ACTIVE ? 'trash-alt' : 'undo' }}"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
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
                {{ $categories->links() }}
            </div>
        </div>
    </div>
@endsection
