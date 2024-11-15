<x-church::website.applayout pageName="Song">
    @if (count($member))
        <h4>Songs</h4>
        @forelse ($songs as $song)
            <ul class="list-unstyled">
                <li>
                    <a href="{{url('/')}}/app/songs/{{$song->id}}">{{$song->title}}</a> 
                    @if ($song->author)
                        &nbsp;<small>({{$song->author}})</small>
                    @endif
                </li>
            </ul>
        @empty
            No songs have been added yet
        @endforelse
    @else
        Sorry! You need to be logged in to see this page
    @endif
</x-church::layout>                
