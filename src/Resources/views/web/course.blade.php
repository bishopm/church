<x-church::layouts.web pageName="Course">
    <h1>{{$course->course}}</h1>
    <p>{{$course->description}}</p>
    <p class="my-2"><b>{{date('j F Y (H:i)',strtotime($course->coursedate))}}</b></p>
    @if(!empty($course->image))
        <img src="{{url('/storage/' . $course->image)}}" alt="Image" class="img-fluid rounded">
    @endif
</x-church::layout>                
