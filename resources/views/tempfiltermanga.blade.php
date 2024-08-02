<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
      crossorigin="anonymous"
    />
    <!-- Local CSS -->
    <link rel="stylesheet" href="/css/main.css" />
    <title>MangaMis - Manga</title>
    <style>
      body {
        font-family: Arial, sans-serif;
      }

      .container {
        padding: 20px;
      }

      .navbar {
        margin-bottom: 20px;
      }

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
        color: white;
        text-decoration: none;
        flex: 1;
        text-align: center;
      }

      .nav-tabs .tab.active {
        /* color: white; */
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
  </head>
  <body>
    <section id="navbar">
      <nav class="navbar navbar-expand-lg bg-transparent">
        <div class="container-fluid">
          <a class="navbar-brand d-flex align-items-center" href="#">
            <img
              src="images/logo-navbar.png"
              class="navbar_logo"
              alt="navbar-logo"
            />
            <div class="lines"></div>
          </a>
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <div
            class="collapse navbar-collapse justify-content-center"
            id="navbarNav"
          >
            <ul class="navbar-nav">
              <li class="nav-item active">
                <a
                  class="nav-link fw-bolder navbar-items"
                  aria-current=" page"
                  href="#"
                  >Home</a
                >
              </li>
              <li class="nav-item" style="margin: 0 25px">
                <a class="nav-link fw-bolder navbar-items" href="#">Manga</a>
              </li>
              <li class="nav-item" style="margin-right: 25px">
                <a class="nav-link fw-bolder navbar-items" href="#">Random</a>
              </li>
              <li class="nav-item">
                <a class="nav-link fw-bolder navbar-items" href="#"
                  >Community</a
                >
              </li>
            </ul>
          </div>
          <form class="d-flex align-items-center search-box" role="search">
            <div class="lines"></div>
            <div
              class="profile d-flex justify-content-center align-items-center"
            >
              <div class="circle"></div>
            </div>
            <div class="container-button">
              <input
                class="form-control me-2 rounded-0"
                type="search"
                placeholder="Search Manga..."
                aria-label="Search"
              />
              <div class="icon-search"></div>
            </div>
          </form>
        </div>
      </nav>
    </section>
    <section id="manga">
      <div id="top-manga">
        <div class="bg-line d-flex justify-content-center align-items-center">
          <div class="bg"></div>
        </div>
        <div class="container">
          <h5>TOP WEEKLY MANGA</h5>
          {{-- HARUSNYA DIPAKE DARI SINI BE --}}
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
                data-genre="action"
              >
                Action
              </li>
              <li
                class="tab nav-link fw-bolder navbar-items"
                data-genre="romance"
              >
                Romance
              </li>
              <li
                class="tab nav-link fw-bolder navbar-items"
                data-genre="horror"
              >
                Horror
              </li>
              <li
                class="tab nav-link fw-bolder navbar-items"
                data-genre="comedy"
              >
                Comedy
              </li>
              <li
                class="tab nav-link fw-bolder navbar-items"
                data-genre="others"
              >
                Others
              </li>
            </ul>
          </nav>
          <div
            class="row d-flex justify-content-between align-items-center"
            id="manga-cards"
          >
            <div class="manga-card d-flex flex-column" data-genre="action">
              <div
                class="img"
                style="background-image: url('images/dandan-book.jpg')"
              ></div>
              <div class="title">Dandandan</div>
              <div class="chp-title">
                Chapter 110 : Beginning after the en...
              </div>
            </div>
            <div class="manga-card d-flex flex-column" data-genre="action">
              <div
                class="img"
                style="background-image: url('images/kaijuu-cover.jpg')"
              ></div>
              <div class="title">Kaijuu No.8</div>
              <div class="chp-title">
                Chapter 110 : Beginning after the en...
              </div>
            </div>
            <div class="manga-card d-flex flex-column" data-genre="romance">
              <div
                class="img"
                style="background-image: url('images/86-books.jpg')"
              ></div>
              <div class="title">86</div>
              <div class="chp-title">
                Chapter 110 : Beginning after the en...
              </div>
            </div>
            <div class="manga-card d-flex flex-column" data-genre="others">
              <div
                class="img"
                style="background-image: url('images/twaf.jpg')"
              ></div>
              <div class="title">The World After The Fall</div>
              <div class="chp-title">
                Chapter 110 : Beginning after the en...
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section id="footer">
      <div class="logo">
        <img src="/images/MangaMis.png" alt="" />
      </div>
      <div class="container links text-center">
        <div class="row row-cols-5 ehe">
          <div class="col">
            <a class="footer-content" href="">
              <h6>HOME</h6>
            </a>
          </div>
          <div class="col">
            <a class="footer-content" href="">
              <h6>CONTACT <span>US</span></h6>
            </a>
          </div>
          <div class="col">
            <a class="footer-content" href="">
              <h6>TERMS <span>AND</span> LICENCES</h6>
            </a>
          </div>
          <div class="col">
            <a class="footer-content" href="">
              <h6>WHAT <span>WE</span> DO</h6>
            </a>
          </div>
          <div class="col">
            <a class="footer-content" href="">
              <h6>FAQS</h6>
            </a>
          </div>
        </div>
      </div>
      <div class="copyright-container text-center">
        <h6>Copyright Ⓒ <span>WEGILE</span>. All Rights Reserved.</h6>
      </div>
    </section>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
      crossorigin="anonymous"
    ></script>
    <script>
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
                  const mangaCard = document.createElement("div");
                  mangaCard.className = "manga-card d-flex flex-column";
                  mangaCard.innerHTML = `
                                  <div class="img" style="background-image: url('${manga.image}')"></div>
                                  <div class="title">${manga.title}</div>
                                  <div class="chp-title">${manga.desc}</div>
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
  </body>
</html>
