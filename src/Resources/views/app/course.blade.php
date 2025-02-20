<x-church::layouts.app pageName="Course">
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
                        Week {{$session->order}}
                    @endif
                </td>
                <td><a href="{{url('/courses') . '/' .  $session->course_id . '/' . $session->id}}">{{$session->session}}</a></td>
            </tr>
        @endforeach
    </table>
    @if ($course->leadernotes)
        <small><a href="{{url('/storage/' . $course->leadernotes)}}" target="_blank">Leader notes</a></small>
    @endif
</x-church::layout>                
