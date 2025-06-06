<x-church::layouts.app pageName="Groups">
    <h2 class="text-center">Groups at {{setting('general.church_abbreviation')}}</h2>
    <ul class="list-unstyled">
        @foreach ($groups as $day=>$groupdays)
            <h4 class="bg-black text-white text-center">
                @if ($day<>'No day')
                    {{$days[$day]}}
                @endif
            </h4>
            @foreach ($groupdays as $key=>$group)
                <li>
                    <p>
                        @if ($group->meetingtime)
                            <b><span class="p-1 m-1 bg-black text-white">{{substr($group->meetingtime,0,5)}}</span></b> 
                        @endif
                    </p>
                    <a href="{{url('/')}}/groups/{{$group->id}}">{{$group->groupname}}</a>
                    </p>
                    <div>{{$group->description}}</div>
                </li>
            @endforeach
        @endforeach
    </ul>
</x-church::layout>                
