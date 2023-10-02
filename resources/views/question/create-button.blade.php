<div class="row col-12">
    <div class="col-md-5">
        <div class="form-group">
            <label>Tugma (O'zbek tilida)</label>
            <input type="text" class="form-control @error('text_uz') is-invalid @enderror" name="text_uz[{{ $i ?? 0 }}]" required  value="{{ old('text_uz') }}">
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-group">
            <label>Tugma (Rus tilida)</label>
            <input type="text" class="form-control @error('text_ru') is-invalid @enderror" name="text_ru[{{ $i ?? 0 }}]" required  value="{{ old('text_ru') }}">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group d-flex justify-content-start flex-column">
            <label>&nbsp</label>
            <div class="d-flex" style="margin-top: 5px; gap: 10px">
                <button type="button" class="btn btn-success d-inline-block plus" style="width: fit-content"><i class="fas fa-plus"></i></button>
                <button type="button" class="btn btn-danger d-inline-block minus" style="width: fit-content"><i class="fas fa-minus"></i></button>
            </div>
        </div>
    </div>
</div>
