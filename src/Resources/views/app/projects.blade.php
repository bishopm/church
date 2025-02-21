<x-church::layouts.app pageName="Projects">
    <h1>Mission projects</h1>
    @forelse ($projects as $project)
        <div><a href="{{url('/')}}/projects/{{$project->id}}">{{$project->project}}</a> </div>
    @empty
        <p>No mission project details have been added yet</p>
    @endforelse
    {{$projects->links()}}
</x-church::layout>                
