@props(['photo'])

<div class="card shadow-sm border-0 overflow-hidden mb-4 rounded-3" 
     x-data="{ 
        liked: {{ $photo->isLikedBy(auth()->user()) ? 'true' : 'false' }}, 
        count: {{ $photo->likes->count() }},
        isOwnPhoto: {{ auth()->id() === $photo->user_id ? 'true' : 'false' }},
        newComment: '',
        parentId: null,
        replyToUser: '',
        comments: {{ $photo->comments->toJson() }}
     }">

    <!-- Photo Image -->
    <img src="{{ asset('storage/' . $photo->image_path) }}" class="card-img-top" alt="Photo by {{ $photo->user->username }}">

    <!-- Post Body -->
    <div class="card-body p-4">
        <!-- Action Row: SVG Heart & Pure Numeric Count -->
        <div class="d-flex align-items-center mb-3 gap-3">
            <button 
                @click="
                    if (isOwnPhoto) return;
                    liked = !liked;
                    liked ? count++ : count--;
                    fetch('{{ route('photos.like', $photo) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                        }
                    }).then(res => { if (!res.ok) { liked = !liked; liked ? count++ : count--; } });
                "
                :disabled="isOwnPhoto"
                :class="isOwnPhoto ? 'opacity-50 cursor-not-allowed' : ''"
                class="btn btn-link p-0 text-decoration-none border-0 background-none d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" 
                    :fill="liked ? '#ed4956' : 'none'" 
                    :stroke="liked ? '#ed4956' : '#262626'" 
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                </svg>
                <span class="fw-bold text-dark small" x-text="count"></span>
            </button>
        </div>

        @if($photo->caption)
            <p class="card-text fs-5 text-secondary mb-3">{{ $photo->caption }}</p>
        @endif

        <hr class="text-muted">

        <!-- Main comments list -->
        <div class="comments-list mb-3">
            <template x-for="comment in comments" :key="comment.id">
                <div class="mb-3" x-data="{ 
                    showReplies: false, 
                    replies: [], 
                    repliesCount: comment.replies_count || 0,
                    loadingReplies: false,
                    fetchReplies() {
                        if (this.replies.length > 0) {
                            this.showReplies = !this.showReplies;
                            return;
                        }
                        this.loadingReplies = true;
                        fetch(`/comments/${comment.id}/replies`)
                            .then(res => res.json())
                            .then(data => {
                                this.replies = data;
                                this.showReplies = true;
                                this.loadingReplies = false;
                            });
                    }
                }">
                    <!-- Main comment -->
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="fw-bold me-1" x-text="'@' + (comment.user ? comment.user.username : 'user')"></span>
                            <span x-text="comment.body"></span>
                        </div>
                        <button 
                            @click="parentId = comment.id; replyToUser = comment.user ? comment.user.username : 'user'; $refs.commentInput.focus()" 
                            class="btn btn-link btn-sm text-muted p-0 text-decoration-none ms-2">
                            <small>Reply</small>
                        </button>
                    </div>

                    <!-- Replies Button -->
                    <template x-if="repliesCount > 0">
                        <button 
                            @click="fetchReplies()" 
                            class="btn btn-link btn-sm text-primary p-0 text-decoration-none ms-3 mt-1 fw-bold">
                            <small x-text="loadingReplies ? 'Loading replies...' : (showReplies ? 'Hide replies' : '—— View ' + repliesCount + ' replies')"></small>
                        </button>
                    </template>

                    <!-- Replies List -->
                    <div x-show="showReplies" class="ms-4 mt-2 border-start ps-3" x-collapse>
                        <template x-for="reply in replies" :key="reply.id">
                            <div class="mb-1">
                                <span class="fw-bold me-1" x-text="'@' + (reply.user ? reply.user.username : 'user')"></span>
                                <span x-text="reply.body"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        <!-- Active Reply Banner -->
        <template x-if="parentId !== null">
            <div class="alert alert-secondary border-0 d-flex justify-content-between align-items-center p-2 mb-2 rounded-3">
                <small class="text-dark">
                    💬 Reply to <strong x-text="'@' + replyToUser"></strong>
                </small>
                <button @click="parentId = null; replyToUser = ''" type="button" class="btn-close btn-sm" aria-label="Cancel"></button>
            </div>
        </template>

        <!-- Comment Form -->
        <form @submit.prevent="
            if (!newComment.trim()) return;
            fetch('{{ route('comments.store', $photo) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    body: newComment,
                    parent_id: parentId
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.comment) {
                    if (parentId) {
                        location.reload(); 
                    } else {
                        comments.unshift(data.comment);
                    }
                    newComment = '';
                    parentId = null;
                    replyToUser = '';
                }
            });
        ">
            <div class="input-group input-group-sm mt-2">
                <input 
                    x-ref="commentInput"
                    x-model="newComment" 
                    type="text" 
                    class="form-control" 
                    placeholder="Add a comment..." 
                    required>
                <button class="btn btn-outline-secondary" type="submit">Publish</button>
            </div>
        </form>
    </div>
</div>