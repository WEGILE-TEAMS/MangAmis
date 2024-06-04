@extends('master')

@section('styles')
<link rel="stylesheet" href="{{ asset('Style/footer.css') }}">
@endsection

@section('footer')
<div class="background-container">
    <div class="footer-container">

        <div class="container text-center">
            <div class="row row-cols-4 text-light">
                <div class="col">HOME</div>
                <div class="col">CONTACT US</div>
                <div class="col">TERMS AND LICENCES</div>
                <div class="col">FAQS</div>
            </div>
        </div>
    </div>
</div>
@endsection