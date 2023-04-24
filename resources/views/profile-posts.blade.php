<x-profile :sharedData="$sharedData" title="{{$sharedData['username']}}'s Profile">
    <div class="list-group">
        @foreach ($blogs as $blog)
            <a href="/post/{{ $blog->id }}" class="list-group-item list-group-item-action">
                <img class="avatar-tiny" src="{{ $blog->cletrangere->avatar }}" />
                <strong> {{ $blog->title }} </strong> on {{ $blog->created_at->format('n/j/Y') }}
            </a>
        @endforeach
    </div>
</x-profile>