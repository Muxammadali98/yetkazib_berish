@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Barcha Mashinalar</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-right mb-3">
                            <a href="{{ route('car.create') }}" class="btn btn-success">Mashina qo'shish</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nomi</th>
                                <th>Mashina raqami</th>
                                <th>Mashina rangi</th>
                                <th>Filial</th>
                                <th>Haydovchilar</th>
                                <th>Holati</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($cars as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->number }}</td>
                                    <td>{{ $item->color }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $item->region->name_uz }}</span>
                                    </td>
                                    <td>
                                        @foreach($item->users as $driver)
                                            <span class="badge badge-primary">{{ $driver->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <form action="{{ route('car.hybrid-sleep', $item->id) }}" method="post">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn" type="submit" onclick="return confirm('Ishonchingiz komilmi?')">{!! $item->getStatus() !!}</button>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{ route('car.edit', $item->id) }}" class="btn btn-primary"><i class="fas fa-pencil-alt"></i> </a>
                                        <form action="{{ route('car.destroy', $item->id) }}" method="post" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Ishonchingiz komilmi?')" class="btn btn-danger"><i class="fas fa-trash-alt"></i> </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <p class="text-center">Hechnima topilmadi</p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $cars->links() }}
            </div>
        </div>
    </div>
@endsection
