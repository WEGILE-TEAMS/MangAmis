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
                    <li class="nav-item active">
                        <a class="nav-link fw-bolder navbar-items"
                            aria-current=" page" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item" style="margin: 0 25px;">
                        <a class="nav-link fw-bolder navbar-items"
                            href="{{ route('home') }}">Manga</a>
                    </li>
                    <li class="nav-item" style="margin-right: 25px;">
                        <a class="nav-link fw-bolder navbar-items"
                            href="{{ route('home') }}">Random</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bolder navbar-items"
                            href="{{ route('home') }}">Community</a>
                    </li>
                </ul>
            </div>
            <form class="d-flex align-items-center search-box" role="search">
                <div class="lines"></div>
                <div class="profile d-flex justify-content-center align-items-center">
                    <div class="circle"></div>
                </div>
                <div class="container-button">
                    <input class="form-control me-2 rounded-0" type="search" placeholder="Search Manga..." aria-label="Search">
                    <div class="icon-search"></div>
                </div>
            </form>
        </div>
    </nav>
</section>