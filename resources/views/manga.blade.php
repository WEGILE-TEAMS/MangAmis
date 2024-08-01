@extends('template.master')

@section('title', 'Home Page')

@section('content')
@include('template.navbar')
<section id="manga">
    <div id="top-manga">
        <div class="bg-line d-flex justify-content-center align-items-center">
            <div class="bg"></div>
        </div>
        <div class="container">
            <h5>TOP WEEKLY MANGA</h5>
            <div class="lines"></div>
            <div class="row d-flex justify-content-between align-items-center">
                <div class="manga-card d-flex flex-column">
                    <div class="img" style="background-image: url('images/dandan-book.jpg');"></div>
                    <div class="title">
                        Dandandan
                    </div>
                    <div class="chp-title">
                        Chapter 110 : Beginning after the en...
                    </div>
                </div>
                <div class="manga-card d-flex flex-column">
                    <div class="img" style="background-image: url('images/kaijuu-cover.jpg');"></div>
                    <div class="title">
                        Kaijuu No.8
                    </div>
                    <div class="chp-title">
                        Chapter 110 : Beginning after the en...
                    </div>
                </div>
                <div class="manga-card d-flex flex-column">
                    <div class="img" style="background-image: url('images/86-books.jpg');"></div>
                    <div class="title">
                        86
                    </div>
                    <div class="chp-title">
                        Chapter 110 : Beginning after the en...
                    </div>
                </div>
                <div class="manga-card d-flex flex-column">
                    <div class="img" style="background-image: url('images/twaf.jpg');"></div>
                    <div class="title">
                        The World After The Fall
                    </div>
                    <div class="chp-title">
                        Chapter 110 : Beginning after the en...
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="new-update">
        <div class="container">
            <h5>LASTEST UPDATED</h5>
            <div class="lines"></div>
            <div class="row d-flex justify-content-between align-items-center">
                <div class="manga-card d-flex flex-column">
                    <div class="img" style="background-image: url('images/dandan-book.jpg');"></div>
                    <div class="title">
                        Dandandan
                    </div>
                    <div class="chp-title">
                        Chapter 110 : Beginning after the en...
                    </div>
                </div>
                <div class="manga-card d-flex flex-column">
                    <div class="img" style="background-image: url('images/kaijuu-cover.jpg');"></div>
                    <div class="title">
                        Kaijuu No.8
                    </div>
                    <div class="chp-title">
                        Chapter 110 : Beginning after the en...
                    </div>
                </div>
                <div class="manga-card d-flex flex-column">
                    <div class="img" style="background-image: url('images/86-books.jpg');"></div>
                    <div class="title">
                        86
                    </div>
                    <div class="chp-title">
                        Chapter 110 : Beginning after the en...
                    </div>
                </div>
                <div class="manga-card d-flex flex-column">
                    <div class="img" style="background-image: url('images/twaf.jpg');"></div>
                    <div class="title">
                        The World After The Fall
                    </div>
                    <div class="chp-title">
                        Chapter 110 : Beginning after the en...
                    </div>
                </div>
                <div class="manga-card d-flex flex-column">
                    <div class="img" style="background-image: url('images/dandan-book.jpg');"></div>
                    <div class="title">
                        Dandandan
                    </div>
                    <div class="chp-title">
                        Chapter 110 : Beginning after the en...
                    </div>
                </div>
                <div class="manga-card d-flex flex-column">
                    <div class="img" style="background-image: url('images/kaijuu-cover.jpg');"></div>
                    <div class="title">
                        Kaijuu No.8
                    </div>
                    <div class="chp-title">
                        Chapter 110 : Beginning after the en...
                    </div>
                </div>
                <div class="manga-card d-flex flex-column">
                    <div class="img" style="background-image: url('images/86-books.jpg');"></div>
                    <div class="title">
                        86
                    </div>
                    <div class="chp-title">
                        Chapter 110 : Beginning after the en...
                    </div>
                </div>
                <div class="manga-card d-flex flex-column">
                    <div class="img" style="background-image: url('images/twaf.jpg');"></div>
                    <div class="title">
                        The World After The Fall
                    </div>
                    <div class="chp-title">
                        Chapter 110 : Beginning after the en...
                    </div>
                </div>
            </div>
            <div class="row" style="margin-bottom: 90px;">
                <div class="text-center">        
                    <div class="container-button">
                        <button class="btn btn-secondary">See More</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('template.footer')
@endsection
