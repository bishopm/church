<x-church::website.layout pageName="People">
    <h1>{{$person->fullname}}</h1>
    <div style="min-height: 270px;">
        <img style="float:left; padding-right:10px;" height="250px" src="{{url('/storage/app/public/media/images/' . $person->image)}}" alt="Image" class="rounded">
        {{$person->bio}}<br><br>
        <a href="{{url('/')}}/sermons/{{$person->slug}}">Sermons: {{count($person->sermons)}}</a><br>
        <a href="{{url('/')}}/blog/{{$person->slug}}">Blog posts: {{count($person->posts)}}</a>
    </div>
</x-church::layout>                
