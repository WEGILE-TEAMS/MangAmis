<!-- resources/views/partials/search_results.blade.php -->
@if(count($combinedList) > 0)
    @foreach($combinedList as $manga)
        <a href="{{ route('detailManga.search', [
            "mangaTitle" => $manga['title']
        ]) }}" style="text-decoration: none;" class="manga-item d-flex justify-content-between align-items-center">
            <img src="{{ $manga['image'] }}" alt="{{ $manga['title'] }}">
            <div class="text">
                <div class="names">{{ $manga['title'] }}</div>
                <div class="author">{{ $manga['author'] }}</div>
            </div>
        </a>
    @endforeach
@else
    <div class="manga-item d-flex justify-content-between align-items-center">
        <div class="text">
            <div class="names">No results found.</div>
        </div>
    </div>
@endif