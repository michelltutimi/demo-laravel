<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Feed • Be nice</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .action-btn {
            background: none;
            border: none;
            padding: 0;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #262626;
            cursor: pointer;
            transition: transform 0.1s ease;
        }
        .action-btn:active {
            transform: scale(1.15);
        }
        .action-btn:focus {
            outline: none;
            box-shadow: none;
        }
    </style>
</head>
<body class="bg-light">

    <div class="container py-4" style="max-width: 600px;" 
         x-data="{
            photos: {{ json_encode($photos->items()) }},
            nextCursor: '{{ $photos->nextCursor()?->encode() }}',
            hasMore: {{ $photos->hasMorePages() ? 'true' : 'false' }},
            loading: false,
            loadMore() {
                if (!this.hasMore || this.loading) return;
                this.loading = true;
                
                fetch(`{{ route('feed') }}?cursor=${this.nextCursor}`, {
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(data => {
                    this.photos.push(...data.data);
                    this.nextCursor = data.next_cursor;
                    this.hasMore = data.has_more;
                    this.loading = false;
                });
            }
         }"
         @scroll.window="
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 500) {
                loadMore();
            }
         ">

        <!-- Feed List -->
        <template x-for="photo in photos" :key="photo.id">
            <div class="card shadow-sm border-0 mb-4 rounded-3 overflow-hidden" 
                 x-data="{ 
                    liked: Boolean(photo.is_liked), 
                    count: photo.likes_count || 0 
                 }">
                
                <!-- Card Header: User Info -->
                <div class="card-header bg-white border-0 d-flex align-items-center py-3">
                    <a :href="`/profile/${photo.user.username}`" class="text-decoration-none d-flex align-items-center text-dark">
                        <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center fw-bold me-2" style="width: 38px; height: 38px;">
                            <span x-text="photo.user.username.charAt(0).toUpperCase()"></span>
                        </div>
                        <span class="fw-bold" x-text="'@' + photo.user.username"></span>
                    </a>
                </div>

                <!-- Photo Image (Click redirects to show page) -->
                <a :href="`/photos/${photo.id}`">
                    <img :src="`/storage/${photo.image_path}`" class="card-img-top" :alt="photo.caption" style="object-fit: cover; max-height: 600px;">
                </a>

                <!-- Card Body: Actions & Caption -->
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2 gap-4">
                        <!-- Like Button with SVG Icon & Count -->
                         <button 
                            @click="
                                if (photo.user_id === {{ auth()->id() }}) return;
                                liked = !liked;
                                liked ? count++ : count--;
                                fetch(`/photos/${photo.id}/like`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                    }
                                }).then(res => { if (!res.ok) { liked = !liked; liked ? count++ : count--; } });
                            "
                            :disabled="photo.user_id === {{ auth()->id() }}"
                            :class="photo.user_id === {{ auth()->id() }} ? 'opacity-50 cursor-not-allowed' : ''"
                            class="action-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" 
                                 :fill="liked ? '#ed4956' : 'none'" 
                                 :stroke="liked ? '#ed4956' : 'currentColor'" 
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                            <span class="fw-bold small" x-text="count"></span>
                        </button>

                        <!-- Comment Button with SVG Icon & Count (Redirects to photo page) -->
                        <a :href="`/photos/${photo.id}`" class="action-btn text-decoration-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" 
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                            </svg>
                            <span class="fw-bold small" x-text="photo.comments_count || 0"></span>
                        </a>
                    </div>

                    <!-- Caption (Author + Caption text) -->
                    <template x-if="photo.caption">
                        <p class="card-text mb-0">
                            <strong class="me-1" x-text="'@' + photo.user.username"></strong>
                            <span x-text="photo.caption"></span>
                        </p>
                    </template>
                </div>
            </div>
        </template>

        <!-- Loading Indicator -->
        <div x-show="loading" class="text-center py-3">
            <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
            <span class="ms-2 small text-muted">Loading more posts...</span>
        </div>

        <!-- End of Feed State -->
        <div x-show="!hasMore && photos.length > 0" class="text-center py-4 text-muted small">
            🎉 You're all caught up!
        </div>

    </div>

</body>
</html>