<x-church::layouts.app pageName="Event">
    <h1>{{$event->event}}</h1>
    <p>{{$event->description}}</p>
    <p class="my-2"><b>{{date('j F Y (H:i',strtotime($event->eventdate))}} - {{date('H:i)',strtotime($event->endtime))}}</b></p>
    @if(!empty($event->image))
        <img src="{{url('/storage/' . $event->image)}}" alt="Image" class="img-fluid rounded">
    @endif
</x-church::layout>                
