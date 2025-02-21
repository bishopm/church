<x-church::layouts.app pageName="Course session">
    <div>
        <h3>{{$session->course->course}}</h3>
        <h5>{{$session->order}}. {{$session->session}}</h5>
        @if ($session->video)
            <div class="ratio ratio-16x9">
                <iframe src='{{$session->video}}' frameborder='0' allowfullscreen></iframe>
            </div>
        @endif
        @if ($session->notes)
            <div>
                <h3>Notes</h3>
                {!! $session->notes !!}
            </div>
        @endif
        @if ($session->file)
            <div>
                <h3>Session handout</h3>
                <a href="{{Storage::disk('google')->url($session->file)}}">Download</a>
            </div>
        @endif
        @if ($session->leadernotes)
            <div>
                <h3>Leader notes</h3>
                <a href="{{Storage::disk('google')->url($session->leadernotes)}}">Download</a>
            </div>
        @endif
    </div>
</x-church::layout>                
