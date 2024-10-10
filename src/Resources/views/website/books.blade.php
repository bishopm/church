<x-church::website.layout pageName="Books">
    <h1>Books</h1>
    @foreach ($books as $book)
        <div><a href="{{url('/')}}/books/{{$book->id}}">{{$book->title}}</a> {{$book->allauthors}} </div>
    @endforeach
    {{$books->links()}}
</x-church::layout>                
