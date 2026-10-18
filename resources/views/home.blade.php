<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Auth - Home</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Alpine.js CDN (Light js library) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-light d-flex align-items-center min-vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">

            @auth
                <div class="card p-4 shadow-sm text-center">
                    <h3>Hello, {{ Auth::user()->name }}!</h3>
                    <p class="text-muted">You are logged in correctly in the system.</p>
                    <a href="{{ route('photos.create') }}" class="btn btn-outline-primary mb-3">Upload a Photo</a>
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">Logout</button>
                    </form>
                </div>
            @endauth

            {{-- Errors at validation --}}
            @if ($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Success message --}}
            @if (session('success'))
                <div class="alert alert-success mb-3">
                    {{ session('success') }}
                </div>
            @endif

            @guest
                <!-- Local state of Alpine.js: mode ('login' or 'signup') -->
                <div class="card shadow-sm p-4" x-data="{ mode: '{{ $errors->any() ? 'register' : 'login' }}' }">
                    <h3 class="text-center mb-4">Demo Platform</h3>

                    <!-- Tabs to switch between Login and Signup -->
                    <div class="btn-group w-100 mb-4" role="group">
                        <button 
                            type="button" 
                            class="btn" 
                            :class="mode === 'login' ? 'btn-primary' : 'btn-outline-primary'"
                            @click="mode = 'login'">
                            Login
                        </button>
                        <button 
                            type="button" 
                            class="btn" 
                            :class="mode === 'signup' ? 'btn-primary' : 'btn-outline-primary'"
                            @click="mode = 'signup'">
                            Signup
                        </button>
                    </div>

                    <!-- LOGIN FORM -->
                    <form x-show="mode === 'login'" action="/login" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>

                    <!-- SIGNUP FORM -->
                    <form x-show="mode === 'signup'" action="/register" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Create Account</button>
                    </form>

                    <!-- Google OAuth Button -->
                    <hr class="my-4">
                    <a href="/auth/google/redirect" class="btn btn-outline-dark w-100">
                        Continue with Google
                    </a>

                </div>

            @endguest
            </div>
        </div>
    </div>

</body>
</html>