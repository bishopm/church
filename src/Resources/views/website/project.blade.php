<x-church::website.layout pageName="Blog post">
<div class="container mb-5">
    <h3 class="text-uppercase">{{$project->title}}</h3>
    <div class="pb-3">
        <div>
            <img style="float:left; height:200px; padding-right:15px;" src="{{$project->image}}">
        </div>
        {{$project->description}}<br>
    </div>
</div>

</x-church::layout>                
