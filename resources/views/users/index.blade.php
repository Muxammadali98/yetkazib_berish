@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Barcha Foydalanuvchilar</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-right mb-3">
                            <a href="{{ route('user.create') }}" class="btn btn-success">Foydalanuvchi qo'shish</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Ism</th>
                                <th>Lavozim</th>
                                <th>Filiallar</th>
                                <th>Email</th>
                                <th>Telefon raqam</th>
                                <th>Holati</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($users as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{!! $item->name !!}</td>
                                    <td>
                                        @foreach($item->roles as $role)
                                            <span class="badge badge-primary mt-1">{{ \App\Models\User::roleAlias()[$role->name] }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($item->regions as $region)
                                            <span class="badge badge-info mt-1">{{ $region->name_uz }}</span>
                                        @endforeach
                                    </td>
                                    <td>{!! $item->email !!}</td>
                                    <td>{!! $item->phone_number !!}</td>
                                    <td>{{ \App\Models\User::getStatus()[$item->status] }}</td>
                                    <td class="d-flex" style="gap: 5px;">
                                        <a href="{{ route('user.edit', $item->id) }}" class="btn btn-primary"><i class="fas fa-pencil-alt"></i></a>
                                        <form action="{{ route('user.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            {!! $item->status == \App\Models\User::STATUS_ACTIVE
                                                ? '<button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>'
                                                : '<button type="submit" class="btn btn-success"><i class="fas fa-check"></i></button>'
                                            !!}
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
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
