@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="/assets/bundles/datatables/datatables.min.css">
    <link rel="stylesheet" href="/assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Barcha O'chirilgan ticketlar</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-right mb-3">
                            <a href="{{ route('ticket.create') }}" class="btn btn-success">Ticket ochish</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-md" id="table-1">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Yetkazib beruvchi</th>
                                <th>Mashina</th>
                                <th>Mijoz ismi</th>
                                <th>Mijoz telefon raqami</th>
                                <th>Holati</th>
                                <th>Turi</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($tickets as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{!! $item->user->name !!}</td>
                                    <td>{!! $item->car->name ?? '' !!}</td>
                                    <td>{!! $item->client_name !!}</td>
                                    <td>{!! $item->phone !!}</td>
                                    <td>{{ \App\Models\Ticket::getStatus()[$item->status] }}</td>
                                    <td>{{ \App\Models\Ticket::getTypes()[$item->type] }}</td>
                                    <td class="d-flex" style="gap: 5px;">
                                        <a href="{{ route('ticket.show', $item->id) }}" class="btn btn-info"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('ticket.edit', $item->id) }}" class="btn btn-primary"><i class="fas fa-pencil-alt"></i></a>
                                        <form action="{{ route('ticket.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-success" onClick="return confirm('Ishonchingiz komilmi?')"><i class="fas fa-undo"></i></button>
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

            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="/assets/bundles/datatables/datatables.min.js"></script>
    <script src="/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/js/page/datatables.js"></script>
@endsection
