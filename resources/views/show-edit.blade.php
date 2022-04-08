<form action="{{ route('edit', ['event_id'=>$event[0]['id']]) }}" method="POST">
    @method('PATCH')
    @csrf
    <input type="text" name="title" value="{{ $event[0]['title'] }}">
    <input type="text" name="description" value="{{ $event[0]['description'] }}">
    <input type="file" name="images[]" multiple>
    <input type="submit">
</form>

