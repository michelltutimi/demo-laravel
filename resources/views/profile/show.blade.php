<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@ {{ $user->username }} • Profile</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .profile-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 4px;
        }

        @media (min-width: 768px) {
            .profile-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 12px;
            }
        }

        @media (min-width: 1200px) {
            .profile-grid {
                grid-template-columns: repeat(6, 1fr);
                gap: 16px;
            }
        }

        .photo-grid-item {
            position: relative;
            width: 100%;
            aspect-ratio: 1 / 1;
            overflow: hidden;
            background-color: #f0f0f0;
            border-radius: 4px;
        }

        .photo-grid-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            opacity: 0;
            transition: opacity 0.2s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .photo-grid-item:hover .photo-overlay {
            opacity: 1;
        }

        .avatar-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #212529;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-light">

    <div class="container py-4" 
         style="max-width: 1100px;"
         x-data="{ 
            isFollowing: {{ $user->isFollowing(auth()->user()) ? 'true' : 'false' }},
            followersCount: {{ $user->followers_count }}
         }">

        <!-- Profile Header Section -->
        <div class="row align-items-center mb-5 pb-3 border-bottom">
            <!-- Avatar Placeholder -->
            <div class="col-12 col-md-4 d-flex justify-content-center mb-3 mb-md-0">
                <div class="avatar-placeholder shadow-sm">
                    {{ strtoupper(substr($user->username, 0, 1)) }}
                </div>
            </div>

            <!-- User Info and Controls -->
            <div class="col-12 col-md-8">
                <!-- Flash Status Message (Shows after profile update) -->
                @if(session('status'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="d-flex align-items-center flex-wrap gap-3 mb-3">
                    <h2 class="h4 mb-0 fw-bold">@ {{ $user->username }}</h2>

                    @if(auth()->id() === $user->id)
                        <!-- Own Profile Controls -->
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-sm fw-bold px-3">Edit profile</a>
                    @else
                        <!-- Other User Profile Follow Button (Alpine.js Interactive) -->
                        <button 
                            @click="
                                isFollowing = !isFollowing;
                                isFollowing ? followersCount++ : followersCount--;
                                fetch('{{ route('users.follow', $user) }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                    }
                                }).then(res => {
                                    if (!res.ok) {
                                        isFollowing = !isFollowing;
                                        isFollowing ? followersCount++ : followersCount--;
                                    }
                                });
                            "
                            :class="isFollowing ? 'btn-secondary' : 'btn-primary'"
                            class="btn btn-sm fw-bold px-4">
                            <span x-text="isFollowing ? 'Following' : 'Follow'"></span>
                        </button>
                    @endif
                </div>

                <!-- Account Metrics -->
                <div class="d-flex gap-4 mb-3 fs-6">
                    <div><strong class="text-dark">{{ $user->photos->count() }}</strong> posts</div>
                    <div><strong class="text-dark" x-text="followersCount"></strong> followers</div>
                    <div><strong class="text-dark">{{ $user->following_count }}</strong> following</div>
                </div>

                <!-- User Display Name & Bio -->
                <div>
                    <div class="fw-bold">{{ $user->name }}</div>
                    @if($user->bio)
                        <p class="text-secondary mb-0">{{ $user->bio }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Photo Grid Section -->
        @if($user->photos->count() > 0)
        <div class="profile-grid">
            @foreach($user->photos as $photo)
                <div class="photo-grid-item">
                    <img src="{{ asset('storage/' . $photo->image_path) }}" alt="Photo by {{ $user->username }}">
                    
                    <!-- Hover Overlay with SVG Icons -->
                    <a href="{{ route('photos.show', $photo) }}" class="photo-overlay">
                        <!-- Likes Count with SVG -->
                        <div class="d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#ffffff" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                            <span>{{ $photo->likes_count }}</span>
                        </div>

                        <!-- Comments Count with SVG -->
                        <div class="d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#ffffff" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                            </svg>
                            <span>{{ $photo->comments_count }}</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <div class="fs-1 mb-2">📷</div>
            <h5>No posts yet</h5>
            <p class="small">When photos are uploaded, they will appear on this grid.</p>
        </div>
    @endif

    </div>

</body>
</html>