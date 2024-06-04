@extends('./master')

@section('title')
Sign Up Page
@endsection

@section('styles')
<link rel="stylesheet" href="{{asset('Style/css/register.css')}}" />
<link rel="stylesheet" href="{{asset('Style/css/main.css')}}" />
@endsection

@section('content')
<section id="login">
    <div class="container">
        <h2>SIGN <span>UP</span></h2>
        @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        <form action="/register" method="post">
            @csrf
            <div class="form-group">
                <label for="">Username</label>
                <input type="text" class="form-control @error('username') is invalid @enderror" id="username"
                    name="username" required-value="{{old('username')}}" placeholder="Your username" />
                @error('username')
                <div class="invalid-feedback">
                    {{ $message }}
                    @enderror
                </div>
                <div class="form-group">
                    <label for="">Email</label>
                    <input type="email" class="form-control @error('user_email') is-invalid @enderror" id="user_email"
                        required value="{{old('user_email')}}" placeholder="Your email address" />
                </div>
                @error('user_email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
                <div class="form-group">
                    <label for="">Password</label>
                    <div>
                        <input type="password" class="form-control @error('user_password') is-invalid @enderror"
                            id="user_password" name="user_password" placeholder="Your password" required />
                        @error('user_password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                        <!-- <div class="eye"></div> -->
                    </div>
                </div>
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <button class="btn btn-primary" type="submit">SIGN UP</button>
                    <!-- <a href="#" class="btn btn-primary" type="submit">SIGN UP</a> -->
                    <a href="/login" class="btn btn-secondary">SIGN IN</a>
                </div>
        </form>
    </div>

    <div class="logo d-flex aling-items-center justify-content-center">
        <img src="/images/MangaMis.png" alt="" />
    </div>
</section>
@endsection