<x-church::layouts.app pageName="Groups">
    <h1>Small groups</h1>
    <ul class="list-unstyled">
        @foreach ($groups as $group)
            <li>
                @if (!is_null($group->meetingday))
                    <p><b>{{date('l',strtotime("Sunday " . $group->meetingday . "days" ))}} {{substr($group->meetingtime,0,5)}}</b></p>
                @endif
                <a href="{{url('/')}}/groups/{{$group->id}}">{{$group->groupname}}</a>
                <div>{{$group->description}}</div>
            </li>
        @endforeach
    </ul>
</x-church::layout>                
