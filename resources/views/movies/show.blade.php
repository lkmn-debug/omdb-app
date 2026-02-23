@extends('layouts.app')

@section('title', $movie['Title'] ?? __('messages.movie_details'))

@section('styles')
<style>
    .detail-card {
        background: linear-gradient(145deg, rgba(26, 26, 26, 0.95) 0%, rgba(15, 15, 15, 0.98) 100%);
        border: 1px solid rgba(212, 175, 55, 0.3);
        border-radius: 25px;
        overflow: hidden;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.6), 0 0 30px rgba(212, 175, 55, 0.15);
    }

    .poster-container {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.8), 0 0 20px rgba(212, 175, 55, 0.2);
    }

    .poster-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, var(--gold), transparent);
    }

    .detail-title {
        font-family: 'Playfair Display', serif;
        color: var(--gold);
        font-weight: 700;
        text-shadow: 0 0 20px rgba(212, 175, 55, 0.3);
        letter-spacing: 0.5px;
    }

    .badge {
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.2) 0%, rgba(212, 175, 55, 0.1) 100%);
        border: 1px solid rgba(212, 175, 55, 0.4);
        color: var(--gold-light);
        padding: 8px 16px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .detail-label {
        color: var(--gold);
        font-weight: 600;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
    }

    .detail-value {
        color: rgba(224, 224, 224, 0.9);
        font-weight: 400;
    }

    .rating-star {
        color: var(--gold);
        font-size: 1.8rem;
        text-shadow: 0 0 15px rgba(212, 175, 55, 0.6);
    }

    .btn-favorite-large {
        background: linear-gradient(135deg, var(--gold-dark) 0%, var(--gold) 100%);
        border: none;
        color: #0a0a0a;
        font-weight: 700;
        padding: 15px;
        border-radius: 15px;
        transition: all 0.4s ease;
        box-shadow: 0 5px 25px rgba(212, 175, 55, 0.4);
        letter-spacing: 0.5px;
    }

    .btn-favorite-large:hover {
        background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
        transform: translateY(-3px);
        box-shadow: 0 10px 35px rgba(212, 175, 55, 0.6);
        color: #0a0a0a;
    }

    .btn-favorite-large.active {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }

    .btn-back {
        background: rgba(26, 26, 26, 0.8);
        border: 2px solid rgba(212, 175, 55, 0.3);
        color: #e0e0e0;
        font-weight: 600;
        padding: 12px;
        border-radius: 15px;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        border-color: var(--gold);
        color: var(--gold);
        background: rgba(26, 26, 26, 0.95);
    }

    .plot-text {
        background: rgba(10, 10, 10, 0.5);
        padding: 20px;
        border-radius: 15px;
        border-left: 4px solid var(--gold);
        color: rgba(224, 224, 224, 0.9);
        line-height: 1.8;
    }

    .rating-item {
        background: rgba(10, 10, 10, 0.5);
        padding: 12px 20px;
        border-radius: 10px;
        border: 1px solid rgba(212, 175, 55, 0.2);
        margin-bottom: 10px;
    }
</style>
@endsection

@section('content')
@php
// Helper function to display N/A values professionally
function displayValue($value, $default = '—') {
if (!isset($value) || $value === 'N/A' || $value === '' || $value === null) {
return '<span style="color: rgba(212, 175, 55, 0.4); font-style: italic;">' . $default . '</span>';
}
return htmlspecialchars($value);
}
@endphp

<div class="container">
    <div class="detail-card">
        <div class="card-body p-4 p-md-5">
            <div class="row g-4">
                <!-- Movie Poster -->
                <div class="col-md-4">
                    <div class="poster-container">
                        @if($movie['Poster'] !== 'N/A')
                        <img src="{{ $movie['Poster'] }}"
                            class="img-fluid w-100"
                            alt="{{ $movie['Title'] }}"
                            onerror="this.onerror=null; this.parentElement.innerHTML='<div style=\'width: 100%; aspect-ratio: 2/3; background: linear-gradient(135deg, #0a0a0a 0%, #000000 100%); display: flex; align-items: center; justify-content: center; border-radius: 20px; padding: 40px; position: relative; overflow: hidden;\'><div style=\'position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: radial-gradient(circle at center, rgba(212, 175, 55, 0.05) 0%, transparent 70%); pointer-events: none;\'></div><div style=\'text-align: center; color: rgba(212, 175, 55, 0.8); position: relative; z-index: 1;\'><svg width=\'80\' height=\'80\' viewBox=\'0 0 24 24\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\' style=\'margin: 0 auto 25px; opacity: 0.4;\'><rect x=\'2\' y=\'3\' width=\'20\' height=\'18\' rx=\'2\' stroke=\'#d4af37\' stroke-width=\'1.5\' /><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\' fill=\'#d4af37\' opacity=\'0.5\' /><path d=\'M2 17L8 11L12 15L22 5\' stroke=\'#d4af37\' stroke-width=\'1.5\' stroke-linecap=\'round\' stroke-linejoin=\'round\' opacity=\'0.5\' /></svg><div style=\'font-family: \'Playfair Display\', serif; font-weight: 700; font-size: 1.5rem; line-height: 1.5; letter-spacing: 0.5px; text-shadow: 0 2px 10px rgba(212, 175, 55, 0.3);\'>' + this.alt + '</div></div></div>';">
                        @else
                        <div style="width: 100%; aspect-ratio: 2/3; background: linear-gradient(135deg, #0a0a0a 0%, #000000 100%); display: flex; align-items: center; justify-content: center; border-radius: 20px; padding: 40px; position: relative; overflow: hidden;">
                            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: radial-gradient(circle at center, rgba(212, 175, 55, 0.05) 0%, transparent 70%); pointer-events: none;"></div>
                            <div style="text-align: center; color: rgba(212, 175, 55, 0.8); position: relative; z-index: 1;">
                                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin: 0 auto 25px; opacity: 0.4;">
                                    <rect x="2" y="3" width="20" height="18" rx="2" stroke="#d4af37" stroke-width="1.5" />
                                    <circle cx="8.5" cy="8.5" r="1.5" fill="#d4af37" opacity="0.5" />
                                    <path d="M2 17L8 11L12 15L22 5" stroke="#d4af37" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" opacity="0.5" />
                                </svg>
                                <div style="font-family: 'Playfair Display', serif; font-weight: 700; font-size: 1.5rem; line-height: 1.5; letter-spacing: 0.5px; text-shadow: 0 2px 10px rgba(212, 175, 55, 0.3);">{{ $movie['Title'] }}</div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Favorite Button -->
                    <button class="btn btn-favorite-large w-100 mt-4 d-flex align-items-center justify-content-center {{ $isFavorite ? 'active' : '' }}"
                        id="favoriteBtn"
                        onclick="toggleFavorite()">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="{{ $isFavorite ? 'currentColor' : 'none' }}" xmlns="http://www.w3.org/2000/svg" class="me-2" id="heartIcon">
                            <path d="M12 21.35L10.55 20.03C5.4 15.36 2 12.27 2 8.5C2 5.41 4.42 3 7.5 3C9.24 3 10.91 3.81 12 5.08C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.41 22 8.5C22 12.27 18.6 15.36 13.45 20.03L12 21.35Z" stroke="currentColor" stroke-width="2" />
                        </svg>
                        <span id="favoriteBtnText">
                            {{ $isFavorite ? __('messages.remove_from_favorites') : __('messages.add_to_favorites') }}
                        </span>
                    </button>

                    <a href="{{ route('movies.index') }}" class="btn btn-back w-100 mt-3 d-flex align-items-center justify-content-center">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                            <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        {{ __('messages.back_to_list') }}
                    </a>
                </div>

                <!-- Movie Details -->
                <div class="col-md-8">
                    <h2 class="detail-title mb-4">{{ $movie['Title'] }}</h2>

                    <div class="mb-4 d-flex flex-wrap gap-2">
                        <span class="badge">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-1">
                                <rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2" />
                                <line x1="9" y1="2" x2="9" y2="6" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                <line x1="15" y1="2" x2="15" y2="6" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                            {!! displayValue($movie['Year']) !!}
                        </span>
                        <span class="badge">{!! displayValue($movie['Rated']) !!}</span>
                        <span class="badge">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-1">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" />
                                <path d="M12 6V12L16 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                            {!! displayValue($movie['Runtime']) !!}
                        </span>
                    </div>

                    <div class="mb-4 d-flex align-items-center">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="#d4af37" stroke="#d4af37" stroke-width="2" />
                        </svg>
                        <span class="detail-label me-2">{{ __('messages.imdb_rating') }}:</span>
                        <span class="rating-star">{!! displayValue($movie['imdbRating'] ?? null) !!}</span>
                        <span class="detail-value ms-2">/10</span>
                        @if(isset($movie['imdbVotes']) && $movie['imdbVotes'] !== 'N/A')
                        <small class="ms-2" style="color: rgba(224, 224, 224, 0.6);">({!! displayValue($movie['imdbVotes']) !!} {{ __('messages.votes') }})</small>
                        @endif
                    </div>

                    <hr style="border-color: rgba(212, 175, 55, 0.2); margin: 2rem 0;">

                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2 mt-1">
                                    <path d="M7 7H17M7 12H17M7 17H13" stroke="#d4af37" stroke-width="2" stroke-linecap="round" />
                                </svg>
                                <div>
                                    <div class="detail-label">{{ __('messages.genre') }}</div>
                                    <div class="detail-value">{!! displayValue($movie['Genre']) !!}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2 mt-1">
                                    <path d="M20 7L5 7M20 12L5 12M20 17L5 17" stroke="#d4af37" stroke-width="2" stroke-linecap="round" />
                                    <circle cx="3" cy="7" r="1" fill="#d4af37" />
                                    <circle cx="3" cy="12" r="1" fill="#d4af37" />
                                    <circle cx="3" cy="17" r="1" fill="#d4af37" />
                                </svg>
                                <div>
                                    <div class="detail-label">{{ __('messages.director') }}</div>
                                    <div class="detail-value">{!! displayValue($movie['Director']) !!}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2 mt-1">
                                    <path d="M17 21V19C17 16.7909 15.2091 15 13 15H5C2.79086 15 1 16.7909 1 19V21" stroke="#d4af37" stroke-width="2" stroke-linecap="round" />
                                    <circle cx="9" cy="7" r="4" stroke="#d4af37" stroke-width="2" />
                                    <path d="M23 21V19C23 17.1362 21.7252 15.5701 20 15.126" stroke="#d4af37" stroke-width="2" stroke-linecap="round" />
                                </svg>
                                <div>
                                    <div class="detail-label">{{ __('messages.actors') }}</div>
                                    <div class="detail-value">{!! displayValue($movie['Actors']) !!}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2 mt-1">
                                    <path d="M12 20H21M3 20H6M6 20V4M6 4H18C18.5304 4 19.0391 4.21071 19.4142 4.58579C19.7893 4.96086 20 5.46957 20 6V13" stroke="#d4af37" stroke-width="2" stroke-linecap="round" />
                                </svg>
                                <div>
                                    <div class="detail-label">{{ __('messages.writer') }}</div>
                                    <div class="detail-value">{!! displayValue($movie['Writer']) !!}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2 mt-1">
                                    <circle cx="12" cy="12" r="10" stroke="#d4af37" stroke-width="2" />
                                    <path d="M2 12H22M12 2C14.5013 4.73835 15.9228 8.29203 16 12C15.9228 15.708 14.5013 19.2616 12 22C9.49872 19.2616 8.07725 15.708 8 12C8.07725 8.29203 9.49872 4.73835 12 2Z" stroke="#d4af37" stroke-width="2" />
                                </svg>
                                <div>
                                    <div class="detail-label">{{ __('messages.language') }}</div>
                                    <div class="detail-value">{!! displayValue($movie['Language']) !!}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2 mt-1">
                                    <path d="M4 15V9C4 8.44772 4.44772 8 5 8H7L12 3L17 8H19C19.5523 8 20 8.44772 20 9V15C20 15.5523 19.5523 16 19 16H5C4.44772 16 4 15.5523 4 15Z" stroke="#d4af37" stroke-width="2" />
                                    <path d="M8 21H16" stroke="#d4af37" stroke-width="2" stroke-linecap="round" />
                                </svg>
                                <div>
                                    <div class="detail-label">{{ __('messages.country') }}</div>
                                    <div class="detail-value">{!! displayValue($movie['Country']) !!}</div>
                                </div>
                            </div>
                        </div>

                        @if(isset($movie['Awards']) && $movie['Awards'] !== 'N/A')
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2 mt-1">
                                    <path d="M12 2L14.5 9H22L16 14L18.5 21L12 16.5L5.5 21L8 14L2 9H9.5L12 2Z" fill="#d4af37" stroke="#d4af37" stroke-width="1.5" />
                                </svg>
                                <div>
                                    <div class="detail-label">{{ __('messages.awards') }}</div>
                                    <div class="detail-value">{!! displayValue($movie['Awards']) !!}</div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2 mt-1">
                                    <rect x="2" y="5" width="20" height="14" rx="2" stroke="#d4af37" stroke-width="2" />
                                    <path d="M2 10H22" stroke="#d4af37" stroke-width="2" />
                                </svg>
                                <div>
                                    <div class="detail-label">{{ __('messages.box_office') }}</div>
                                    <div class="detail-value">{!! displayValue($movie['BoxOffice'] ?? null) !!}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr style="border-color: rgba(212, 175, 55, 0.2); margin: 2rem 0;">

                    <div class="mb-4">
                        <div class="detail-label mb-3 d-flex align-items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                                <path d="M4 6H20M4 12H20M4 18H14" stroke="#d4af37" stroke-width="2" stroke-linecap="round" />
                            </svg>
                            {{ __('messages.plot') }}
                        </div>
                        <div class="plot-text">{{ $movie['Plot'] }}</div>
                    </div>

                    @if(isset($movie['Ratings']) && count($movie['Ratings']) > 0)
                    <div class="mb-3">
                        <div class="detail-label mb-3 d-flex align-items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                                <path d="M3 3V21H21" stroke="#d4af37" stroke-width="2" stroke-linecap="round" />
                                <path d="M7 14L12 9L16 13L21 8" stroke="#d4af37" stroke-width="2" stroke-linecap="round" />
                            </svg>
                            {{ __('messages.ratings') }}
                        </div>
                        @foreach($movie['Ratings'] as $rating)
                        <div class="rating-item d-flex justify-content-between align-items-center">
                            <span class="badge mb-0">{{ $rating['Source'] }}</span>
                            <span class="detail-value">{{ $rating['Value'] }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let isFavorite = @json($isFavorite);
    const imdbId = @json($movie["imdbID"]);
    const title = @json($movie["Title"]);
    const poster = @json($movie["Poster"] !== "N/A" ? $movie["Poster"] : "https://via.placeholder.com/300x450?text=No+Poster");

    function toggleFavorite() {
        const btn = $('#favoriteBtn');
        const heartIcon = $('#heartIcon');
        const text = $('#favoriteBtnText');

        if (isFavorite) {
            // Remove from favorites
            $.ajax({
                url: `/favorites/${imdbId}`,
                method: 'DELETE',
                success: function(response) {
                    isFavorite = false;
                    btn.removeClass('active');
                    heartIcon.attr('fill', 'none');
                    text.text('{{ __("messages.add_to_favorites") }}');
                    showToast(response.message);
                },
                error: function() {
                    showToast('{{ __("messages.error_occurred") }}', 'error');
                }
            });
        } else {
            // Add to favorites
            $.ajax({
                url: '{{ route("favorites.store") }}',
                method: 'POST',
                data: {
                    imdb_id: imdbId,
                    title: title,
                    poster: poster
                },
                success: function(response) {
                    isFavorite = true;
                    btn.addClass('active');
                    heartIcon.attr('fill', 'currentColor');
                    text.text('{{ __("messages.remove_from_favorites") }}');
                    showToast(response.message);
                },
                error: function(xhr) {
                    if (xhr.status === 409) {
                        showToast('{{ __("messages.already_in_favorites") }}', 'error');
                    } else {
                        showToast('{{ __("messages.error_occurred") }}', 'error');
                    }
                }
            });
        }
    }
</script>
@endsection