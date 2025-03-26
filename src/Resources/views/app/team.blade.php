<x-church::layouts.app pageName="Service team">
    <h1>{{$team->groupname}}</h1>
    <p>{{$team->description}}</p>
    <small><a href="{{route('app.contact')}}">Contact us</a> if you'd be interested in serving in this team.</small>
</x-church::layout>                
