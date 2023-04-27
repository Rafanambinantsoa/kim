<x-profile :sharedData="$sharedData" title="who {{$sharedData['username']}} follow">
    @include('profile-followings-only')
</x-profile>