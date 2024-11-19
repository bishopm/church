<x-church::website.applayout pageName="Blog post">
    <h1>{{$post->title}}</h1>
    <div class="meta mb-3">
        <small><span class="date">{{date('j M Y',strtotime($post->published_at))}}</span> •<a href="{{url('/people') . '/' . $post->person->slug}}" class="cat">{{$post->person->fullname}}</a></small>
        <div>
            @foreach ($post->tags as $tag)
                <span class="badge text-uppercase"><a href="{{url('/')}}/subject/{{$tag->slug}}" class="badge">{{$tag->name}}</a></span>
            @endforeach
        </div>
    </div>
    <div class="row gy-2">
        <div class="col-md-8 col-sm-12">
            <div class="post-entry" data-aos="fade-up" data-aos-delay="100">
                @if ($post->image)
                    <img src="{{url('/storage/' . $post->image)}}" alt="Image" class="img-fluid rounded">
                @endif
                <div class="post-content">
                    {!!  nl2br($post->content) !!}
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            @if (count($related))
                <h3>Related posts</h3>
                @foreach ($related['blogs'] as $y=>$year)
                    <h5>{{$y}}</h5>
                    @foreach ($year as $blog)
                        <div>
                            <a href="{{url('/blog') . '/' . date('Y',strtotime($blog['published_at'])) . '/' . date('m',strtotime($blog['published_at'])) . '/' . $blog['slug']}}">{{$blog['title']}}</a>
                        </div>
                    @endforeach
                @endforeach
            @endif
        </div>
    </div>
</x-church::layout>                
