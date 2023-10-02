@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Barcha bekor qilingan buyurtmalar</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Mijoz ismi</th>
                                <th>Telefon raqam</th>
                                <th>Vaqt</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($answers as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{!! $item->client->full_name !!}</td>
                                    <td>{!! $item->client->phone_number !!}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td class="d-flex" style="gap: 5px;">
                                        <a href="#" class="btn btn-success accept-app" data-toggle="modal" title="Qabul qilish" data-id="{{ $item->application_id }}" data-url="{{ route('answer.managerAccepted', $item->application_id) }}" data-name="{{ $item->client->full_name }}" data-target="#exampleModalCenter"><i class="fas fa-check"></i></a>
                                        <a href="{{ route('answer.show', $item->application_id) }}" class="btn btn-info"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('answer.pdf', $item->application_id) }}" class="btn btn-primary" data-toggle="tooltip" title="PDF yuklash"><i class="fas fa-download"></i></a>
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
                <div class="d-flex justify-content-center">
                    {{ $answers->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function () {
            $('.accept-app').click(function () {
                let url = $(this).data('url');
                let name = $(this).data('name');
                let modal = $('#exampleModalCenter');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (response) {
                        if (response.status === 'success') {
                            modal.find('.modal-title').text(name);
                            modal.find('.btn-primary').addClass('accept-app-btn').attr('data-url', url);
                            modal.find('.modal-body').html(response.content);
                        }
                    },
                });
            });

            $(document).on('click', '.accept-app-btn', function () {
                let url = $(this).data('url');
                let modal = $('#exampleModalCenter');
                let comment = modal.find('#comment').val();
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        comment: comment,
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            modal.modal('hide');
                            location.reload();
                        }
                    },
                });
            });
        });
    </script>
@endsection
