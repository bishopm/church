<x-church::layouts.app pageName="{{$page->title}}">
    <h1>{{$page->title}}</h1>
    <div style="min-height: 270px;">
        @if ($page->image)
            <img style="float:right; padding-left:10px;" height="250px" src="{{url('/storage/' . $page->image)}}" alt="Image" class="rounded">
        @endif
        {!!$page->content!!}<br><br>
    </div>
</x-church::layout>                
