<x-church::layouts.web pageName="Blog post">
    <h3 class="text-uppercase">{{$project->title}}</h3>
    <div class="pb-3">
        @if ($project->image)
            <div>
                <img style="float:left; height:200px; padding-right:15px;" src="{{url('/storage/' . $project->image)}}">
            </div>
        @endif
        {!!$project->description!!}<br>
    </div>
</x-church::layout>                
