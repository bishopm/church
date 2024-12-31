<x-church::website.layout pageName="Blog">
    <h1>Blog</h1>
    @forelse ($posts as $post)
        <div class="mb-4 row">
            <div class="col-12">
                <div><a href="{{url('/blog') . '/' . date('Y',strtotime($post->published_at)) . '/' . date('m',strtotime($post->published_at)) . '/' . $post->slug}}"><img style="float:left; padding-right:10px;" width="200px" src="{{url('/storage/app/media/images/' . $post->image)}}" alt="Image" class="img-fluid rounded"></a></div>
                <a href="{{url('/blog') . '/' . date('Y',strtotime($post->published_at)) . '/' . date('m',strtotime($post->published_at)) . '/' . $post->slug}}">{{$post->title}}</a> <span style="color:grey;">{{date('j M \'y',strtotime($post->published_at))}}  • {{$post->person->fullname}}</span><br>
                {{$post->excerpt}}
            </div>
        </div>
    @empty
        No blog posts have been published yet
    @endforelse
    {{$posts->links()}}
</x-church::layout>                
