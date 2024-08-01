<section id="navbar">
    <nav class="navbar navbar-expand-lg bg-transparent">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="{{ asset('images/logo-navbar.png') }}" class="navbar_logo" alt="navbar-logo">
                <div class="lines"></div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item {{ (Route::currentRouteName() == 'home') ? 'active' : '' }}">
                        <a class="nav-link fw-bolder navbar-items"
                            aria-current=" page" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item {{ (Route::currentRouteName() == 'manga') ? 'active' : '' }}" style="margin: 0 25px;">
                        <a class="nav-link fw-bolder navbar-items"
                            href="{{ route('manga') }}">Manga</a>
                    </li>
                    <li class="nav-item {{ (Route::currentRouteName() == 'randomManga') ? 'active' : '' }}" style="margin-right: 25px;">
                        <a class="nav-link fw-bolder navbar-items"
                            href="{{ route('randomManga') }}">Random</a>
                    </li>
                    <li class="nav-item {{ (Route::currentRouteName() == 'community') ? 'active' : '' }}">
                        <a class="nav-link fw-bolder navbar-items"
                            href="{{ route('community') }}">Community</a>
                    </li>
                </ul>
            </div>
            <form class="d-flex align-items-center search-box" role="search">
                <div class="lines"></div>
                <a href="{{ route('profile.show') }}" class="profile d-flex justify-content-center align-items-center {{ (Route::currentRouteName() == 'profile.show') ? 'active' : '' }}">
                    <div class="circle"></div>
                </a>
                <div class="container-button">
                    <input id="search-input" class="form-control me-2 rounded-0" type="search" placeholder="Search Manga..." aria-label="Search">
                    <div class="icon-search"></div>
                </div>
                <div id="search-results" class="result d-none" class="result d-none">
                </div>
            </form>
        </div>
    </nav>
</section>
