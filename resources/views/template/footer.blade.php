@extends('template/master')


@section('styles')
<link rel="stylesheet" href="{{ asset('Style/css/main.css') }}">
@endsection

@section('footer')
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
@endsection