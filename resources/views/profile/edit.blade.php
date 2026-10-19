@extends('layouts.app')

@section('title', 'Edit Profile • @ ' . $user->username)

@section('content')
<div class="container py-5" style="max-width: 600px;">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h4 mb-0 fw-bold">Edit Profile</h1>
                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary btn-sm fw-bold">Cancel</a>
            </div>

            <!-- Validation Errors Display -->
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Profile Edit Form -->
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Name Field -->
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Display Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        class="form-control" 
                        value="{{ old('name', $user->name) }}" 
                        required>
                    <div class="form-text">Your public name displayed on your profile.</div>
                </div>

                <!-- Username Field -->
                <div class="mb-3">
                    <label for="username" class="form-label fw-bold">Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted">@</span>
                        <input 
                            type="text" 
                            name="username" 
                            id="username" 
                            class="form-control" 
                            value="{{ old('username', $user->username) }}" 
                            required>
                    </div>
                    <div class="form-text">Unique username used for profile URL and mentions.</div>
                </div>

                <!-- Bio Field -->
                <div class="mb-4">
                    <label for="bio" class="form-label fw-bold">Bio</label>
                    <textarea 
                        name="bio" 
                        id="bio" 
                        rows="3" 
                        class="form-control" 
                        placeholder="Write a short bio...">{{ old('bio', $user->bio) }}</textarea>
                    <div class="form-text">Brief description of yourself.</div>
                </div>

                <!-- Form Actions -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary fw-bold">Save Changes</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection