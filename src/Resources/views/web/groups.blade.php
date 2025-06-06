<x-church::layouts.web pageName="Groups">
    <h1>Small groups</h1>
    <ul class="list-unstyled">
        @foreach ($groups as $day=>$daygroups)
            <h4>
                @if ($day<>'No day')
                    {{$days[$day]}}
                @endif
            </h4>
            @foreach ($daygroups as $group)
                <li>
                    <a href="{{url('/')}}/groups/{{$group->id}}">{{$group->groupname}}</a>
                    <div>{{$group->description}}</div>
                </li>
            @endforeach
        @endforeach
    </ul>
</x-church::layout>                
