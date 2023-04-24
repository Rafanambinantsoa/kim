<x-layout :title="$title">
    <div class="container py-md-5 container--narrow">
        <h2>
            <img class="avatar-small" src="{{ $sharedData['avatar'] }}" /> {{ $sharedData['username'] }}

            @auth
                @if (!$sharedData['currentFollowings'] AND $sharedData['username'] != auth()->user()->username)
                    <form class="ml-2 d-inline" action="/create-follow/{{ $sharedData['username'] }}" method="POST">
                        @csrf
                        <button class="btn btn-primary btn-sm">Follow <i class="fas fa-user-plus"></i></button>
                    </form>
                @endif

                @if ($sharedData['currentFollowings'])
                <form class="ml-2 d-inline" action="/remove-follow/{{ $sharedData['username'] }}" method="POST">
                    @csrf
                    <button class="btn btn-danger btn-sm">Stop Following <i class="fas fa-user-times"></i></button>
                </form>
                @endif


                @if (auth()->user()->username == $sharedData['username'])
                    <a href="/manage-avatar" class="btn btn-secondary btn-sm">Manage-avatar</a>
                @endif
            @endauth
        </h2>

        <div class="profile-nav nav nav-tabs pt-2 mb-4">
            <a href="/profile/{{$sharedData['username']}}" class="profile-nav-link nav-item nav-link {{ Request::segment(3) == "" ? "active" : ""}} ">Posts: {{ $sharedData['blogsCount'] }} </a>
            <a href="/profile/{{$sharedData['username']}}/followers" class="profile-nav-link nav-item nav-link {{ Request::segment(3) == "followers" ? "active" : ""}}">Followers: {{$sharedData['followersCount']}} </a>
            <a href="/profile/{{$sharedData['username']}}/followings" class="profile-nav-link nav-item nav-link {{ Request::segment(3) == "followings" ? "active" : ""}}">Following: {{$sharedData['followingsCount']}} </a>
        </div>        

        <div class="profile-slot-content">
            {{$slot}}
        </div>
    </div>
</x-layout>
