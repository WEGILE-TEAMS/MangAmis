<!DOCTYPE html>
<html>

<head>
    <link rel="icon" href="{{ asset('images/icon-mangamis.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('Style/css/main.css') }}">
    @stack('styles')
</head>

<body>

    @yield('content')
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#search-input').on('keyup', function() {
                var query = $(this).val();
                if(query.length > 2) {
                    $.ajax({
                        url: "{{ route('manga.search') }}",
                        type: "GET",
                        data: {'query': query},
                        success: function(data) {
                            $('#search-results').html(data.html).removeClass('d-none');
                        },
                        error: function() {
                            $('#search-results').addClass('d-none');
                        }
                    });
                } else {
                    $('#search-results').addClass('d-none');
                }
            });
        });
        </script>
    @stack('scripts')
</body>

</html>