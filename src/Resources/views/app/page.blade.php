<x-church::layouts.app pageName="{{$page->title}}">
    <h1>{{$page->title}}</h1>
    <div style="min-height: 270px;">
        @if ($page->image)
            <img class="img-fluid" src="{{url('/storage/' . $page->image)}}" alt="Image" class="rounded">
        @endif
        {!!$page->content!!}<br><br>
    </div>
</x-church::layout>                
