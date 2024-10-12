<x-church::website.layout pageName="Groups">
    <h1>{{$group->groupname}}</h1>
    <div class="my-3 row">
        <div class="col-lg-4">
            <p>{{$group->description}}</p>
            <p>Contact {{$group->individual->fullname}} for more details</p>
        </div>
        <div class="col-lg-4">
            @if(!empty($group->image))
                <img src="{{url('/public/storage/' . $group->image)}}" alt="Image" class="img-fluid rounded">
            @endif
        </div>
        <div class="col-lg-4">
            <h4>Resources</h4>
        </div>
    </div>
</x-church::layout>                
