<x-church::layouts.app pageName="Service teams">
    <h3>Serve on a team at {{setting('general.church_abbreviation')}}</h3>
    @foreach ($teams as $name=>$team)
        @if (isset($team['teams']))
            <p><a href="{{url('/')}}/teams/{{$team['teams']['id']}}">{{$name}}</a></p>
        @else 
            <p>{{$name}} 
            @foreach ($team['services'] as $time=>$service)
                <a href="{{url('/')}}/teams/{{$team['services'][$time]['id']}}">{{$time}}</a>
            @endforeach
            </p>
        @endif
    @endforeach
</x-church::layout>                
