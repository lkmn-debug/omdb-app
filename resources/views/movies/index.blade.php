@extends('layouts.app')

@section('title', __('messages.browse_movies'))

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="d-flex align-items-center" style="color: var(--gold); font-family: 'Playfair Display', serif; font-weight: 700;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-3">
                <rect x="2" y="3" width="20" height="18" rx="2" stroke="#d4af37" stroke-width="2" />
                <circle cx="8" cy="8" r="1.5" fill="#d4af37" />
                <path d="M2 17L8 11L12 15L22 5" stroke="#d4af37" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <line x1="2" y1="8" x2="22" y2="8" stroke="#d4af37" stroke-width="1.5" />
            </svg>
            {{ __('messages.browse_movies') }}
        </h2>
    </div>

    <!-- Search Form -->
    <div class="search-form">
        <h4 class="mb-3 d-flex align-items-center">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2" />
                <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
            {{ __('messages.search_movies') }}
        </h4>
        <form id="searchForm">
            <div class="row g-3">
                <div class="col-md-5">
                    <input type="text"
                        class="form-control"
                        id="searchQuery"
                        name="s"
                        placeholder="{{ __('messages.movie_title') }}"
                        required>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="searchType" name="type">
                        <option value="">{{ __('messages.all_types') }}</option>
                        <option value="movie">{{ __('messages.movie') }}</option>
                        <option value="series">{{ __('messages.series') }}</option>
                        <option value="episode">{{ __('messages.episode') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number"
                        class="form-control"
                        id="searchYear"
                        name="y"
                        placeholder="{{ __('messages.year') }}"
                        min="1900"
                        max="{{ date('Y') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                            <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2.5" />
                            <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" />
                        </svg>
                        {{ __('messages.search') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Movies Grid -->
    <div id="moviesGrid" class="row g-4">
        <!-- Movies will be loaded here -->
    </div>

    <!-- Loading Spinner -->
    <div class="loading-spinner">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">{{ __('messages.loading') }}</span>
        </div>
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="empty-state" style="display: none;">
        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="2" y="3" width="20" height="18" rx="2" stroke="#d4af37" stroke-width="1.5" />
            <line x1="2" y1="8" x2="22" y2="8" stroke="#d4af37" stroke-width="1.5" />
            <circle cx="7" cy="14" r="2" fill="#d4af37" opacity="0.3" />
            <circle cx="17" cy="14" r="2" fill="#d4af37" opacity="0.3" />
            <path d="M3 21L21 3" stroke="#d4af37" stroke-width="2" stroke-linecap="round" />
        </svg>
        <h3>{{ __('messages.no_movies_found') }}</h3>
        <p>{{ __('messages.try_different_search') }}</p>
    </div>

    <!-- Initial State -->
    <div id="initialState" class="empty-state">
        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="11" cy="11" r="8" stroke="#d4af37" stroke-width="1.5" />
            <path d="M21 21L16.65 16.65" stroke="#d4af37" stroke-width="2" stroke-linecap="round" />
            <circle cx="11" cy="11" r="5" stroke="#d4af37" stroke-width="1" opacity="0.3" />
        </svg>
        <h3>{{ __('messages.search_movies') }}</h3>
        <p>{{ __('messages.enter_keyword_to_search') }}</p>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentPage = 1;
    let isLoading = false;
    let hasMorePages = true;
    let currentSearchParams = {};
    let displayedMovies = new Set(); // Track displayed movie IDs to prevent duplicates

    $(document).ready(function() {
        // Search form submit
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();

            // Check if search query is not empty
            const searchQuery = $('#searchQuery').val().trim();
            if (!searchQuery) {
                showToast('{{ __("messages.please_enter_search_keyword") }}', 'error');
                return;
            }

            // Hide initial state and reset
            $('#initialState').hide();
            currentPage = 1;
            hasMorePages = true;
            displayedMovies.clear(); // Clear duplicate tracking on new search
            $('#moviesGrid').empty();
            loadMovies();
        });

        // Infinite scroll
        $(window).on('scroll', function() {
            if ($(window).scrollTop() + $(window).height() > $(document).height() - 500) {
                if (!isLoading && hasMorePages) {
                    currentPage++;
                    loadMovies();
                }
            }
        });
    });

    function loadMovies() {
        if (isLoading) return;

        // Check if search query is not empty
        const searchQuery = $('#searchQuery').val().trim();
        if (!searchQuery) {
            return;
        }

        isLoading = true;
        $('.loading-spinner').show();

        currentSearchParams = {
            s: searchQuery,
            type: $('#searchType').val(),
            y: $('#searchYear').val(),
            page: currentPage
        };

        $.ajax({
            url: '{{ route("movies.index") }}',
            method: 'GET',
            data: currentSearchParams,
            success: function(response) {
                $('.loading-spinner').hide();

                if (response.Response === 'True') {
                    $('#emptyState').hide();
                    renderMovies(response.Search);

                    // Check if there are more pages
                    const totalResults = parseInt(response.totalResults);
                    const loadedResults = currentPage * 10;
                    hasMorePages = loadedResults < totalResults;
                } else {
                    if (currentPage === 1) {
                        $('#emptyState').show();
                        // Show error if there's one
                        if (response.Error) {
                            showToast(response.Error, 'error');
                        }
                    }
                    hasMorePages = false;
                }

                isLoading = false;

                // Initialize lazy load for new images
                lazyLoadImages();
            },
            error: function(xhr, status, error) {
                $('.loading-spinner').hide();
                let errorMsg = '{{ __("messages.error_loading_movies") }}';

                // Try to get error message from response
                if (xhr.responseJSON && xhr.responseJSON.Error) {
                    errorMsg = xhr.responseJSON.Error;
                } else if (status === 'timeout') {
                    errorMsg = 'Request timeout. Please check your internet connection.';
                } else if (status === 'error') {
                    errorMsg = 'Failed to connect to server. Please try again.';
                }

                showToast(errorMsg, 'error');
                isLoading = false;

                if (currentPage === 1) {
                    $('#emptyState').show();
                }
            }
        });
    }

    function renderMovies(movies) {
        movies.forEach(movie => {
            // Skip if movie is already displayed (prevent duplicates)
            if (displayedMovies.has(movie.imdbID)) {
                return;
            }
            displayedMovies.add(movie.imdbID);

            // Generate poster - use placeholder if N/A
            let posterImg;
            if (movie.Poster !== 'N/A') {
                // Escape title for use in JavaScript function call
                const escapedTitle = movie.Title.replace(/'/g, "\\'").replace(/"/g, "&quot;");
                posterImg = `<img data-src="${movie.Poster}" 
                                 src="https://via.placeholder.com/300x450/1a1a1a/d4af37?text=Loading..." 
                                 class="movie-poster lazy" 
                                 alt="${movie.Title}"
                                 onerror="handlePosterError(this, '${escapedTitle}')">`;
            } else {
                posterImg = `
                    <div class="movie-poster">
                        <div class="poster-placeholder">
                            <div class="poster-placeholder-content">
                                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin: 0 auto 15px; opacity: 0.4;">
                                    <rect x="2" y="3" width="20" height="18" rx="2" stroke="#d4af37" stroke-width="1.5"/>
                                    <circle cx="8.5" cy="8.5" r="1.5" fill="#d4af37" opacity="0.5"/>
                                    <path d="M2 17L8 11L12 15L22 5" stroke="#d4af37" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" opacity="0.5"/>
                                </svg>
                                <div>${movie.Title}</div>
                            </div>
                        </div>
                    </div>`;
            }

            const favoriteClass = movie.is_favorite ? 'active' : '';
            const heartFill = movie.is_favorite ? '#d4af37' : 'none';

            const movieCard = `
            <div class="col-md-3 col-sm-6">
                <div class="card h-100">
                    <div class="position-relative">
                        ${posterImg}
                        <button class="btn-favorite ${favoriteClass}" 
                                data-favorite="${movie.is_favorite}"
                                onclick="toggleFavorite('${movie.imdbID}', '${movie.Title.replace(/'/g, "\\'")}', '${movie.Poster}', this)">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="${heartFill}" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 21.35L10.55 20.03C5.4 15.36 2 12.27 2 8.5C2 5.41 4.42 3 7.5 3C9.24 3 10.91 3.81 12 5.08C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.41 22 8.5C22 12.27 18.6 15.36 13.45 20.03L12 21.35Z" stroke="#d4af37" stroke-width="2"/>
                            </svg>
                        </button>
                    </div>
                    <div class="card-body">
                        <h6 class="card-title mb-2">${movie.Title}</h6>
                        <p class="card-text mb-3">
                            <small class="d-flex align-items-center mb-1">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-1">
                                    <rect x="3" y="4" width="18" height="18" rx="2" stroke="#d4af37" stroke-width="2"/>
                                    <line x1="9" y1="2" x2="9" y2="6" stroke="#d4af37" stroke-width="2" stroke-linecap="round"/>
                                    <line x1="15" y1="2" x2="15" y2="6" stroke="#d4af37" stroke-width="2" stroke-linecap="round"/>
                                    <line x1="3" y1="10" x2="21" y2="10" stroke="#d4af37" stroke-width="2"/>
                                </svg>
                                <span style="color: rgba(224, 224, 224, 0.7);">${movie.Year}</span>
                            </small>
                            <small class="d-flex align-items-center">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-1">
                                    <path d="M20 7L9 18L4 13" stroke="#d4af37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span style="color: rgba(224, 224, 224, 0.7); text-transform: capitalize;">${movie.Type}</span>
                            </small>
                        </p>
                        <a href="/movies/${movie.imdbID}" class="btn btn-sm btn-primary w-100 d-flex align-items-center justify-content-center">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                <path d="M12 16V12M12 8H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            {{ __('messages.view_details') }}
                        </a>
                    </div>
                </div>
            </div>
        `;

            $('#moviesGrid').append(movieCard);
        });
    } {
        {
            __('messages.view_details')
        }
    };

    function toggleFavorite(imdbId, title, poster, button) {
        const $button = $(button);
        const svg = $button.find('svg path');
        const isFavorite = $button.data('favorite');

        if (isFavorite) {
            // Remove from favorites
            $.ajax({
                url: `/favorites/${imdbId}`,
                method: 'DELETE',
                success: function(response) {
                    $button.removeClass('active').data('favorite', false);
                    svg.attr('fill', 'none');
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
                    $button.addClass('active').data('favorite', true);
                    svg.attr('fill', '#d4af37');
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