<x-church::website.applayout pageName="Song">
    @if (count($member))
        <h4>{{$song->title}}</h4>
        <small>{{$song->author}}</small>
        @if ($song->video)
            <div class="ratio ratio-16x9">
                <iframe src='{{$song->video}}' frameborder='0' allowfullscreen></iframe>
            </div>
        @endif
        <div>{!! nl2br(preg_replace('/\[[^\]]*\]/', '', $song->lyrics)) !!}</div>
    @else
        Sorry! You need to be logged in to see this page
    @endif
</x-church::layout>                
