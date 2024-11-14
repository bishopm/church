<x-church::website.applayout pageName="Books">    
    <h1>Books</h1>
    @if(count($member))
        @livewire('barcodescanner')
    @endif
    @foreach ($books as $book)
        <div><a href="{{url('/')}}/book/{{$book->id}}/app">{{$book->title}}</a> {{$book->allauthors}} </div>
    @endforeach
    {{$books->links()}}
</x-church::layout>                
