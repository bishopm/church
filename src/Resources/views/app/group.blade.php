<x-church::layouts.app pageName="Groups">
    <h4>{{$group->groupname}}</h4>
    <p>{{$group->description}}</p>
    <p>Contact {{$group->individual->fullname}} for more details</p>
    <div>
        @if(!empty($group->image))
            <img src="{{url('/storage/' . $group->image)}}" alt="Image" class="img-fluid rounded">
        @endif
    </div>
</x-church::layout>                
