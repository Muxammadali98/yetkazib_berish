@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Amallar tarixi</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Arizachining ismi</th>
                                <th>Xodim</th>
                                <th>Lavozim</th>
                                <th>Holat</th>
                                <th>Vaqt</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($logs as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('answer.show', $item->application_id) }}">
                                            {{ $item->application->client->full_name }}
                                        </a>
                                    </td>
                                    <td>{{ $item->user->name }}</td>
                                    <td>
                                        @foreach($item->user->roles as $role)
                                            <span class="badge badge-info">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>{!! \App\Models\Log::getStatus()[$item->status] !!}</td>
                                    <td>{{ $item->created_at }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <p class="text-center">Hechnima topilmadi</p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@endsection
