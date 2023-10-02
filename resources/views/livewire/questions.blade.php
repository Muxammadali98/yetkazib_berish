<table class="table table-bordered table-md">
    <thead>
    <tr>
        <th>#</th>
        <th>Savol (uz)</th>
        <th>Savol (ru)</th>
        <th></th>
    </tr>
    </thead>
    <tbody wire:sortable="updateQuestionStep">
    @forelse($questions as $item)
        <tr draggable="true" wire:sortable.item="{{ $item->id }}" wire:key="question-{{ $item->id }}">
            <td wire:sortable.handle><i class="fa fa-arrows-alt text-muted"></i> </td>
            <td>{!! $item->title_uz !!}</td>
            <td>{!! $item->title_ru !!}</td>
            <td class="d-flex" style="gap: 5px;">
                <div class="checkbox-custom checkbox-primary d-flex align-content-center">
                    <input type="checkbox" name="status" {{ $item->status == \App\Models\Question::STATUS_ACTIVE ? 'checked' : '' }} style="width: 34px;" id="modelStatusId-{{ $item->id }}"  wire:change="changeStatus({{ $item->id }})">
                </div>
                <a href="{{ route('question.show', $item->id) }}" class="btn btn-info"><i
                        class="fas fa-folder-open"></i></a>
                <a href="{{ route('question.edit', $item->id) }}" class="btn btn-primary"><i
                        class="fas fa-pencil-alt"></i></a>
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
{{ $questions->links() }}

