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
                <div class="d-flex align-items-center flex-wrap gap-3 mb-3">
                    <h2 class="h4 mb-0 fw-bold">@ {{ $user->username }}</h2>

                    @if(auth()->id() === $user->id)
                        <!-- Own Profile Controls -->
                        <button class="btn btn-outline-secondary btn-sm fw-bold px-3" disabled>Edit profile</button>
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

                <!-- User Bio Placeholder -->
                <div>
                    <div class="fw-bold">{{ strtoupper($user->name) }}</div>
                </div>
            </div>
        </div>

        <!-- Photo Grid Section -->
        @if($user->photos->count() > 0)
            <div class="profile-grid">
                @foreach($user->photos as $photo)
                    <div class="photo-grid-item">
                        <img src="{{ asset('storage/' . $photo->image_path) }}" alt="Photo by {{ $user->username }}">
                        
                        <a href="{{ route('photos.show', $photo) }}" class="photo-overlay">
                            <div>❤️ {{ $photo->likes_count }}</div>
                            <div>💬 {{ $photo->comments_count }}</div>
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