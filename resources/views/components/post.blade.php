<a href="/post/{{ $post->id }}" class="list-group-item list-group-item-action">
    <img class="avatar-tiny" src="{{ $post->cletrangere->avatar }}" />
    <strong> {{ $post->title }} </strong> <span class="texte-muted small"> 
        @if ($hideAuthor != 1)
        by {{ $post->username }} 
        @endif
        on {{ $post->created_at->format('n/j/Y') }} </span>
</a>