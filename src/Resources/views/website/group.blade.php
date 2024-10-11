<x-church::website.layout pageName="Groups">
    <h1>{{$group->groupname}}</h1>
    <div class="my-3">{{$group->description}}</div>
    <div>Contact {{$group->individual->fullname}} for more details</div>
</x-church::layout>                
