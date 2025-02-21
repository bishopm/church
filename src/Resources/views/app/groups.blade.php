<x-church::layouts.app pageName="Groups">
    <h1>Small groups</h1>
    <ul class="list-unstyled">
        @foreach ($groups as $day=>$days)
            <h4 class="bg-black text-white text-center">{{$day}}</h4>
            @foreach ($days as $key=>$group)
                <li>
                    <p><b><span class="p-1 m-1 bg-black text-white">{{substr($group->meetingtime,0,5)}}</span></b> <a href="{{url('/')}}/groups/{{$group->id}}">{{$group->groupname}}</a></p>
                    <div>{{$group->description}}</div>
                </li>
            @endforeach
        @endforeach
    </ul>
</x-church::layout>                
