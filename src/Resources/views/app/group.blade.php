<x-church::layouts.app pageName="Groups">
    <h4>{{$group->groupname}}</h4>
    @if ($group->meetingday)
        <p><b>{{date('l',strtotime("Sunday " . $group->meetingday . "days" ))}} {{substr($group->meetingtime,0,5)}}</b></p>
    @endif
    <p>{{$group->description}}</p>
    <p>Contact {{$group->individual->fullname}} for more details</p>
    <div>
        @if(!empty($group->image))
            <img src="{{url('/storage/' . $group->image)}}" alt="Image" class="img-fluid rounded">
        @endif
    </div>
</x-church::layout>                
