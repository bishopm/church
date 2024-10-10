<x-church::website.layout pageName="Blogger">
    <h1>Blog</h1>
    @foreach ($posts as $post)
        <div class="mb-4 row">
            <div class="col-12">
                <div><a href="{{url('/blog') . '/' . date('Y',strtotime($post->published_at)) . '/' . date('m',strtotime($post->published_at)) . '/' . $post->slug}}"><img style="float:left; padding-right:10px;" width="200px" src="{{url('/public/storage/' . $post->image)}}" alt="Image" class="img-fluid rounded"></a></div>
                <a href="{{url('/blog') . '/' . date('Y',strtotime($post->published_at)) . '/' . date('m',strtotime($post->published_at)) . '/' . $post->slug}}">{{$post->title}}</a> <span style="color:grey;">{{date('j M \'y',strtotime($post->published_at))}}  • {{$post->person->fullname}}</span><br>
                {{$post->excerpt}}
            </div>
        </div>
    @endforeach
    {{$posts->links()}}
</x-church::layout>                
