<x-church::layouts.web pageName="Meeting minutes">
    <h1>{{setting('general.church_abbreviation')}} {{$meeting->groupname}}</h1>
    @foreach ($meeting->meetings as $meeting)
        <div>{{$meeting->meetingdatetime}} <a href="{{route('reports.a4meeting',['id'=>$meeting->id])}}">Agenda</a> | <a href="{{route('reports.minutes',['id'=>$meeting->id])}}">Minutes</a></div>
    @endforeach
</x-church::layout>                
