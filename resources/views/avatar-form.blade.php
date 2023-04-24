<x-layout title="Manage Avatar">
    <div class="container py-md-5 container--narrow">
        <h2 class="text-center mb-3">Upload an new avatar</h2>
        <form action="/manage-avatar" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <input type="file" name="avatar">
            </div>
            @error('avatar')
                <p class="alert alert-danger shadow-sm"> {{$message}} </p>
            @enderror
            <button class="btn btn-primary">Save</button>
        </form>
    </div>
</x-layout>
