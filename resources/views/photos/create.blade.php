<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Nueva Foto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h1 class="h4 font-weight-bold mb-4 text-center">Publicar una Foto</h1>

                        <!-- Mensaje de éxito relámpago -->
                        @if (session('status'))
                            <div class="alert alert-success mb-4 text-center">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form action="{{ route('photos.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Campo de Selección de Imagen -->
                            <div class="mb-3">
                                <label for="image" class="form-label font-weight-bold">Selecciona una imagen</label>
                                <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                                @error('image')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Campo de Pie de Foto (Caption) -->
                            <div class="mb-3">
                                <label for="caption" class="form-label font-weight-bold">Pie de foto (Caption)</label>
                                <textarea name="caption" id="caption" class="form-control" rows="3" placeholder="¿Qué estás pensando? Usa hashtags si quieres..."></textarea>
                                @error('caption')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Botón de Envío -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-dark btn-lg">Publicar Foto</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>