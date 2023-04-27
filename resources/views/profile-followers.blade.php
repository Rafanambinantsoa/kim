<x-profile :sharedData="$sharedData" title="{{$sharedData['username']}}'s Followers">
    @include('profile-followers-only')
</x-profile>