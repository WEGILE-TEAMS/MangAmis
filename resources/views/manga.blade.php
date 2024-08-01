@extends('template.master')

@section('title', 'Home Page')

@section('content')
@include('template.navbar')
@push('styles')
    <style>
        .nav-tabs {
        list-style-type: none;
        padding: 0;
        display: flex;
        justify-content: center;
        margin-bottom: 0;
        width: 100%;
      }

      .nav-tabs .tab {
        padding: 10px 20px;
        cursor: pointer;
        color: black;
        text-decoration: none;
        flex: 1;
        text-align: center;
      }

      .nav-tabs .tab.active {
        border-bottom: 2px solid red;
      }

      .manga-card {
        display: none;
        flex-direction: column;
        margin: 10px;
      }

      .manga-card.active {
        display: flex;
      }

      .img {
        width: 100px;
        height: 150px;
        background-size: cover;
        background-position: center;
      }

      .title {
        font-weight: bold;
        margin-top: 10px;
      }

      .chp-title {
        color: grey;
      }
    </style>
@endpush
<section id="manga">
    <div id="top-manga">
        <div class="bg-line d-flex justify-content-center align-items-center">
            <div class="bg"></div>
        </div>
        <div class="container">
            <h5>LASTEST UPDATE</h5>
            <div class="lines"></div>
            <div class="row d-flex justify-content-between align-items-center">
                @foreach ($temp as $manga)
                    @php
                        $genresString = implode(',', $manga['genre']);
                    @endphp
                    <a href="{{ route('detailManga', [
                        'id' => $manga['id'],
                        'title' => $manga['title'],
                        'author' => $manga['author_name'],
                        'desc' => $manga['desc'],
                        'genres' => $genresString,
                        'cover_url' => $manga['cover_url']
                    ]) }}" class="manga-card d-flex flex-column" style="text-decoration: none; color: black;">
                        <div class="img" style="background-image: url('{{ $manga['image'] }}');"></div>
                        <div class="title text-white">
                            {{ $manga['title'] }}
                        </div>
                        <div class="chp-title">
                            Chapter {{ $manga['chapter_number'] }} : {{ $manga['chapter_title'] }}
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    
    <div id="new-update">
        <div class="container">
            <h5>BY GENRE</h5>
            <div class="lines"></div>
            <nav class="navbar">
                <ul class="nav-tabs">
                  <li
                    class="tab active nav-link fw-bolder navbar-items"
                    data-genre="all"
                  >
                    All
                  </li>
                  <li
                    class="tab nav-link fw-bolder navbar-items"
                    data-genre="Action"
                  >
                    Action
                  </li>
                  <li
                    class="tab nav-link fw-bolder navbar-items"
                    data-genre="Romance"
                  >
                    Romance
                  </li>
                  <li
                    class="tab nav-link fw-bolder navbar-items"
                    data-genre="Horror"
                  >
                    Horror
                  </li>
                  <li
                    class="tab nav-link fw-bolder navbar-items"
                    data-genre="Comedy"
                  >
                    Comedy
                  </li>
                  <li
                    class="tab nav-link fw-bolder navbar-items"
                    data-genre="Others"
                  >
                    Others
                  </li>
                </ul>
            </nav>
            <div class="row d-flex justify-content-between align-items-center" id="manga-cards">
                <div class="manga-card d-flex flex-column" data-genre="action">
                    <div class="img" style="background-image: url('images/dandan-book.jpg')"></div>
                    <div class="title">Dandandan</div>
                    <div class="chp-title">
                    Chapter 110 : Beginning after the en...
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@push('scripts')
<script>
    const detailMangaRoute = `{{ route('detailManga.search', ['mangaTitle' => '']) }}`;

    document.addEventListener("DOMContentLoaded", () => {
    const tabs = document.querySelectorAll(".tab");
    const mangaContainer = document.getElementById("manga-cards");

    tabs.forEach((tab) => {
        tab.addEventListener("click", () => {
            // Remove active class from all tabs
            tabs.forEach((t) => t.classList.remove("active"));
            // Add active class to the clicked tab
            tab.classList.add("active");

            // Get the genre from the clicked tab
            const genre = tab.getAttribute("data-genre");

            // Fetch manga data from the server based on selected genre
            fetch(`/filter-manga/${genre}`)
                .then((response) => response.json())
                .then((data) => {
                    // Clear current manga cards
                    mangaContainer.innerHTML = "";

                    // Add filtered manga cards to the container
                    data.forEach((manga) => {
                        const mangaCard = document.createElement("a");
                        mangaCard.className = "manga-card d-flex flex-column";
                        mangaCard.style = "text-decoration: none";
                        mangaCard.href = detailMangaRoute + encodeURIComponent(manga.title);
                        mangaCard.innerHTML = `
                            <div class="img" style="background-image: url('${manga.image}')"></div>
                            <div class="title" style="text-decoration: none; color: black;">${manga.title}</div>
                            <div class="chp-title" style="text-decoration: none; color: black;">${manga.desc}</div>
                        `;
                        mangaContainer.appendChild(mangaCard);
                    });
                })
                .catch((error) => console.error("Error fetching manga:", error));
        });
    });

    // Trigger click on the first tab to show all cards initially
    tabs[0].click();
});

  </script>
@endpush
@include('template.footer')
@endsection
