<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('Style/register.css')}}">
    <link rel="stylesheet" href="{{asset('Style/bootstrap.css')}}">
</head>
<body>
<main class="form-signin w-100 m-auto">
    @if(session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success')}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
  <form action="/register" method="post">
    @csrf
    <h1 class="h3 mb-3 fw-normal">Please Register</h1>

    <div class="form-floating">
      <input type="username" class="form-control @error('username') is-invalid @enderror" id="username" name="username" required value="{{old('username') }}">
      <label for="floatingInput">Username</label>
      @error('username')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
      @enderror
    </div>

    <div class="form-floating">
      <input type="password" class="form-control @error('user_password') is-invalid @enderror" id="user_password" name="user_password" placeholder="Password" required>
      <label for="floatingPassword">Password</label>
      @error('user_password')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
      @enderror
    </div>

    <div class="form-check text-start my-3">
      <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
      <label class="form-check-label" for="flexCheckDefault">
        Remember me
      </label>
    </div>
    <button class="btn btn-primary w-100 py-2" type="submit">Sign in</button>
    <p class="mt-5 mb-3 text-body-secondary">&copy; 2017–2024</p>
  </form>
</main>
</body>
</html>