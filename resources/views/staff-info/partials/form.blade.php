<form method="POST" action="{{ $action }}" enctype="multipart/form-data">
    @csrf
    @if($method !== 'POST') @method($method) @endif
    <input name="title" placeholder="Title" value="{{ old('title', $engagement?->title) }}" required>
    <input name="entity_name" placeholder="Entity Name" value="{{ old('entity_name', $engagement?->entity_name) }}" required>
    <textarea name="activity" placeholder="Audit Activities">{{ old('activity', $engagement?->activity) }}</textarea>
    <textarea name="working_notes" placeholder="Working Notes">{{ old('working_notes', $engagement?->working_notes) }}</textarea>
    <input type="file" name="evidence">
    <select name="status" required>
        @foreach(['open','in-progress','closed'] as $status)
            <option value="{{ $status }}" @selected(old('status', $engagement?->status) === $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>
    <input type="date" name="start_date" value="{{ old('start_date', optional($engagement?->start_date)->format('Y-m-d')) }}" required>
    <input type="date" name="end_date" value="{{ old('end_date', optional($engagement?->end_date)->format('Y-m-d')) }}">
    <button type="submit">Save</button>
</form>
