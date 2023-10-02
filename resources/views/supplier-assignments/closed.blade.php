@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="/assets/bundles/datatables/datatables.min.css">
    <link rel="stylesheet" href="/assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
@endsection


@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            @if(session()->has('warning'))
                <div class="alert alert-warning alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>×</span>
                        </button>
                        {{ session()->get('warning') }}
                    </div>
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4>Tugallangan Qo'shimcha topshiriqlar</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-right mb-3">
                            <a href="{{ route('supplier-assignment.create') }}" class="btn btn-success">Topshiriq qo'shish</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-md" id="table-1">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Yetkazib beruvchi</th>
                                <th>Mashina</th>
                                <th>Topshiriq</th>
                                <th>Holati</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($supplier_assignments as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{!! $item->user->name !!}</td>
                                    <td>{!! $item->car->name ?? '' !!}</td>
                                    <td>{!! $item->title !!}</td>
                                    <td>{{ \App\Models\SupplierAssignment::getStatus()[$item->status] }}</td>
                                    <td class="d-flex" style="gap: 5px;">
                                        <a href="{{ route('supplier-assignment.show', $item->id) }}" class="btn btn-info"><i class="fas fa-eye"></i></a>
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
