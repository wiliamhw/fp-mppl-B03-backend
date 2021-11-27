<div class="form-group">
    <label for="preview">{{ $title }}</label>
    @if($imageUrl)
        <div>
            <img src="{{ $imageUrl }}" style="border: 1px solid #333;" />
        </div>
    @else
        <p class="text-muted">No image available</p>
    @endif
</div>
