<x-church::layouts.web pageName="Course">
    <h1>{{$course->course}}</h1>
    <p>{{$course->description}}</p>
    @if(!empty($course->image))
        <img src="{{url('/storage/' . $course->image)}}" alt="Image" class="img-fluid rounded">
    @endif
    <table class="table table-condensed">
        @foreach ($course->coursesessions as $session)
            <tr>
                <td>
                    @if ($session->sessiondate > now())
                        {{date('d M H:i', strtotime($session->sessiondate))}}
                    @else
                        Week {{$session->order+1}}
                    @endif
                </td>
                <td>{{$session->session}}</td>
            </tr>
        @endforeach
    </table>
</x-church::layout>                
