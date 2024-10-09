<x-church::website.layout pageName="Projects">
<div class="container my-3">
    <h1>Mission projects</h1>
    @foreach ($projects as $project)
        <div><a href="{{url('/')}}/projects/{{$project->id}}">{{$project->project}}</a> </div>
    @endforeach
    {{$projects->links()}}
</div>

</x-church::layout>                
