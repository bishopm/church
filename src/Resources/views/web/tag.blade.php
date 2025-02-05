<x-church::website.layout pageName="Blog post">
    <h1 class="text-uppercase">{{$tag->name}}</h1>
    @if (count($posts))
        <h4>Blog posts</h4>
        @foreach ($posts as $post)
            <a href="{{url('/blog') . '/' . date('Y',strtotime($post['published_at'])) . '/' . date('m',strtotime($post['published_at'])) . '/' . $post['slug']}}">{{$post['title']}}</a><br>
        @endforeach
    @endif
    @if (count($sermons))
        <hr><h4>Sermons</h4>
        @foreach ($sermons as $sermon)
            <a href="{{url('/')}}/sermon/{{date('Y',strtotime($sermon->servicedate))}}/{{$sermon->series->slug}}/{{$sermon->id}}">{{$sermon->sermon_title}}</a><br>
        @endforeach
    @endif
    @if (count($books))
        <hr><h4>Books</h4>
        @foreach ($books as $book)
            <a href="{{url('/')}}/books/{{$book->id}}">{{$book->title}}</a> ({{$book->allauthors}})<br>
        @endforeach
    @endif
</x-church::layout>                
