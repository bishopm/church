<x-church::website.layout pageName="Books">
<div class="container mb-5">
    <h1>Books</h1>
    @foreach ($books as $book)
        <div><a href="{{url('/')}}/books/{{$book->id}}">{{$book->title}}</a> {{$book->allauthors}} </div>
    @endforeach
    {{$books->links()}}
</div>

</x-church::layout>                
