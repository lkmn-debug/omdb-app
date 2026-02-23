@extends('layouts.app')

@section('title', __('messages.my_favorites'))

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="d-flex align-items-center" style="color: var(--gold); font-family: 'Playfair Display', serif; font-weight: 700;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="#d4af37" xmlns="http://www.w3.org/2000/svg" class="me-3">
                <path d="M12 21.35L10.55 20.03C5.4 15.36 2 12.27 2 8.5C2 5.41 4.42 3 7.5 3C9.24 3 10.91 3.81 12 5.08C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.41 22 8.5C22 12.27 18.6 15.36 13.45 20.03L12 21.35Z" stroke="#d4af37" stroke-width="1.5" />
            </svg>
            {{ __('messages.my_favorites') }}
            <span class="badge ms-3" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.3), rgba(212, 175, 55, 0.2)); border: 1px solid rgba(212, 175, 55, 0.5); color: var(--gold-light); font-size: 1rem;">{{ $favorites->count() }}</span>
        </h2>
        <a href="{{ route('movies.index') }}" class="btn btn-primary d-flex align-items-center">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2" />
                <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
            {{ __('messages.browse_movies') }}
        </a>
    </div>

    @if($favorites->count() > 0)
    <div class="row g-4">
        @foreach($favorites as $favorite)
        <div class="col-md-3 col-sm-6">
            <div class="card h-100" id="favorite-{{ $favorite->imdb_id }}">
                <div class="position-relative">
                    @if($favorite->poster && $favorite->poster !== 'N/A')
                    <img src="{{ $favorite->poster }}"
                        class="movie-poster"
                        alt="{{ $favorite->title }}"
                        onerror="handlePosterError(this, '{{ addslashes($favorite->title) }}')">
                    @else
                    <div class="movie-poster">
                        <div class="poster-placeholder">
                            <div class="poster-placeholder-content">
                                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin: 0 auto 15px; opacity: 0.4;">
                                    <rect x="2" y="3" width="20" height="18" rx="2" stroke="#d4af37" stroke-width="1.5" />
                                    <circle cx="8.5" cy="8.5" r="1.5" fill="#d4af37" opacity="0.5" />
                                    <path d="M2 17L8 11L12 15L22 5" stroke="#d4af37" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" opacity="0.5" />
                                </svg>
                                <div>{{ $favorite->title }}</div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <button class="btn-favorite active"
                        onclick="removeFavorite('{{ $favorite->imdb_id }}')">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 6H21M19 6L18.5 20.5C18.5 21.3284 17.8284 22 17 22H7C6.17157 22 5.5 21.3284 5.5 20.5L5 6M8 6V4C8 3.44772 8.44772 3 9 3H15C15.5523 3 16 3.44772 16 4V6" stroke="#d4af37" stroke-width="2" stroke-linecap="round" />
                            <path d="M10 11V17M14 11V17" stroke="#d4af37" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <div class="card-body">
                    <h6 class="card-title mb-3">{{ $favorite->title }}</h6>
                    <p class="card-text mb-3">
                        <small class="d-flex align-items-center" style="color: rgba(224, 224, 224, 0.7);">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-1">
                                <circle cx="12" cy="12" r="10" stroke="#d4af37" stroke-width="2" />
                                <path d="M12 6V12L16 14" stroke="#d4af37" stroke-width="2" stroke-linecap="round" />
                            </svg>
                            {{ __('messages.added') }}: {{ $favorite->created_at->format('M d, Y') }}
                        </small>
                    </p>
                    <a href="{{ route('movies.show', $favorite->imdb_id) }}" class="btn btn-sm btn-primary w-100 d-flex align-items-center justify-content-center">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" />
                            <path d="M12 16V12M12 8H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                        {{ __('messages.view_details') }}
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state">
        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 21.35L10.55 20.03C5.4 15.36 2 12.27 2 8.5C2 5.41 4.42 3 7.5 3C9.24 3 10.91 3.81 12 5.08C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.41 22 8.5C22 12.27 18.6 15.36 13.45 20.03L12 21.35Z" stroke="#d4af37" stroke-width="1.5" />
            <path d="M8 12L16 12M12 8L12 16" stroke="#d4af37" stroke-width="2" stroke-linecap="round" opacity="0.3" />
        </svg>
        <h3>{{ __('messages.no_favorites_yet') }}</h3>
        <p>{{ __('messages.start_adding_favorites') }}</p>
        <a href="{{ route('movies.index') }}" class="btn btn-primary btn-lg mt-3 d-flex align-items-center justify-content-center" style="max-width: 300px; margin: 1.5rem auto 0;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2" />
                <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
            {{ __('messages.browse_movies') }}
        </a>
    </div>
    @endif
</div>

<script>
    function removeFavorite(imdbId) {
        showConfirm(
            '{{ __("messages.remove_from_favorites") }}',
            '{{ __("messages.confirm_remove_favorite") }}',
            function() {
                // User confirmed - proceed with deletion
                $.ajax({
                    url: `/favorites/${imdbId}`,
                    method: 'DELETE',
                    success: function(response) {
                        const $card = $(`#favorite-${imdbId}`);
                        const $col = $card.closest('.col-md-3');

                        // Fade out and remove the entire column
                        $col.fadeOut(300, function() {
                            $(this).remove();

                            // Check if no more favorites
                            if ($('.row.g-4 .col-md-3').length === 0) {
                                location.reload();
                            }
                        });
                        showToast(response.message);
                    },
                    error: function() {
                        showToast('{{ __("messages.error_occurred") }}', 'error');
                    }
                });
            },
            null // onCancel - do nothing
        );
    }

    // Handle poster image error - replace with placeholder
    function handlePosterError(img, title) {
        img.onerror = null; // Prevent infinite loop
        const placeholder = `
            <div class="movie-poster">
                <div class="poster-placeholder">
                    <div class="poster-placeholder-content">
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin: 0 auto 15px; opacity: 0.4;">
                            <rect x="2" y="3" width="20" height="18" rx="2" stroke="#d4af37" stroke-width="1.5"/>
                            <circle cx="8.5" cy="8.5" r="1.5" fill="#d4af37" opacity="0.5"/>
                            <path d="M2 17L8 11L12 15L22 5" stroke="#d4af37" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" opacity="0.5"/>
                        </svg>
                        <div>${title}</div>
                    </div>
                </div>
            </div>
        `;
        $(img).parent().html(placeholder);
    }
</script>
@endsection