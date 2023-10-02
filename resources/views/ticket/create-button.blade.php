<div class="row col-12">
    <div class="col-md-3">
        <div class="form-group">
            <label>Mahsulot nomi</label>
            <input type="text" class="form-control" name="product_name[{{ $i ?? 0 }}]" required  value="{{ old('product_name') }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Model</label>
            <input type="text" class="form-control" name="model[{{ $i ?? 0 }}]" required  value="{{ old('model') }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Artikul</label>
            <input type="text" class="form-control" name="article[{{ $i ?? 0 }}]" required  value="{{ old('article') }}">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label>Miqdori</label>
            <input type="number" class="form-control" name="quantity[{{ $i ?? 0 }}]" required  value="{{ old('quantity') }}">
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group d-flex justify-content-start flex-column">
            <label>&nbsp</label>
            <div class="d-flex" style="margin-top: 5px; gap: 10px">
                <button type="button" class="btn btn-success d-inline-block plus" style="width: fit-content"><i class="fas fa-plus"></i></button>
                <button type="button" class="btn btn-danger d-inline-block minus" style="width: fit-content"><i class="fas fa-minus"></i></button>
            </div>
        </div>
    </div>
</div>
