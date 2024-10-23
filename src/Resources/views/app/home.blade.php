<x-church::website.applayout pageName="App home">
@forelse ($content['blogs'] as $blog)
    <div class="lead">{{$blog->title}} <small class="text-muted">{{\Carbon\Carbon::parse($blog['published_at'])->diffForHumans()}}</small></div>
    <div class="text-white">{{$blog->excerpt}}</div>
@empty
    No blog posts have been published yet.
@endforelse
</x-church::website.applayout>