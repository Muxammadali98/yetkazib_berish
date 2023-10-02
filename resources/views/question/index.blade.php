@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Barcha savolliklar</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-right mb-3">
                            <a href="{{ route('question.create') }}" class="btn btn-success">Savol qo'shish</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <livewire:questions />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/sortable-master/dist/livewire-sortable.js"></script>
@endsection
