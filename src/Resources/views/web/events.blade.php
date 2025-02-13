<x-church::layouts.web pageName="Events">
    <h1>Coming up at {{setting('general.church_abbreviation')}}</h1>
    <ul class="list-unstyled">
        @foreach ($events as $event)
            <li>
            {{date('j M',strtotime($event->eventdate))}} <a href="{{url('/')}}/events/{{$event->id}}">{{$event->event}}</a>
            </li>
        @endforeach
    </ul>
</x-church::layout>                
