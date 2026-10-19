@extends('layouts.app')

@section('title', 'Photo of ' . $photo->user->name)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <!-- Back Link -->
            <a href="{{ route('photos.create') }}" class="btn btn-outline-secondary btn-sm mb-3">&larr; Share another photo</a>

            <!-- CALL TO BLADE COMPONENT, the file is 'components/photo-card.blade.php' -->
            <x-photo-card :photo="$photo" />

        </div>
    </div>
</div>
@endsection