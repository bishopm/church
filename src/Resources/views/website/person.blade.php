<x-church::website.layout pageName="People">
<div class="container">
    <h1>{{$person->fullname}}</h1>
    <div style="min-height: 270px;">
        <img style="float:left; padding-right:10px;" height="250px" src="{{url('/public/storage/' . $person->image)}}" alt="Image" class="rounded">
        {{$person->bio}}<br><br>
        <a href="{{url('/')}}/sermons/{{$person->slug}}">Sermons: {{count($person->sermons)}}</a><br>
        <a href="{{url('/')}}/blog/{{$person->slug}}">Blog posts: {{count($person->posts)}}</a>
    </div>
</div>
</x-church::layout>                
