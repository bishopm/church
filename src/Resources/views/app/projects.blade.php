<x-church::layouts.app pageName="Projects">
    <h1>Mission projects</h1>
    @foreach ($projects as $project)
        <div><a href="{{url('/')}}/projects/{{$project->id}}">{{$project->project}}</a> </div>
    @endforeach
    {{$projects->links()}}
</x-church::layout>                
