<x-church::layouts.app pageName="Books">    
    <h1>Books</h1>
    <div class="pb-3">
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h3 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#borrow">
                        Borrow a book
                    </button>
                </h3>
                <div id="borrow" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        @if(count($member))
                            @livewire('barcodescanner')
                        @else
                            You need to be logged in to borrow books
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form method="get">
        <div class="input-group">
            <input class="form-control" name="search" placeholder="Search by title or author" value="{{$search}}">
            <div class="input-group-append">
                &nbsp;<button class="btn btn-secondary" icon="bi bi-search"><i class="bi bi-search"></i></button>
            </div>
        </div>
    </form>
    <table class="table-responsive table-sm mt-2 mb-2">
        <tr><th>Title</th><th>Authors</th></tr>
        @forelse ($books as $book)
            <tr>
                @if ($book->allauthors)
                    <td><a href="{{url('/')}}/books/{{$book->id}}">{{$book->title}}</a></td>
                    <td><small>{{$book->allauthors}}</small></td>
                @else 
                    <td colspan="2"><a href="{{url('/')}}/books/{{$song->id}}">{{$book->title}}</a></td>
                @endif
                </li>
            </tr>
        @empty
            No books have been added yet
        @endforelse
    </table>
    <small>{{$books->links()}}</small>
</x-church::layout>                
