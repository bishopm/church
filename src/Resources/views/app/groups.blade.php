<x-church::layouts.app pageName="Groups">
    <h1>Small groups</h1>
    <ul class="list-unstyled">
        @foreach ($groups as $group)
            <li>
                <p>{{date('l',strtotime("Sunday " . $group->meetingday . "days" ))}}</p>
                <a href="{{url('/')}}/groups/{{$group->id}}">{{$group->groupname}}</a>
                <div>{{$group->description}}</div>
            </li>
        @endforeach
    </ul>
</x-church::layout>                
