<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Photo of {{ $photo->user->name }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-light">

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

</body>
</html>