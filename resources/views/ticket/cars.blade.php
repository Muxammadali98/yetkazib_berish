<option value="">Tanlang</option>
@empty(!$cars)
    @foreach($cars as $key => $car)
        <option value="{{ $key }}">{{ $car }}</option>
    @endforeach
@endempty
