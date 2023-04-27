<x-profile :sharedData="$sharedData" title="{{$sharedData['username']}}'s Profile">
    @include('profile-posts-only')
</x-profile>