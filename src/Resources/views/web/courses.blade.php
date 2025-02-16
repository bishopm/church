<x-church::layouts.web pageName="Courses">
    <h1>Courses at {{setting('general.church_abbreviation')}}</h1>
    <ul class="list-unstyled">
        @foreach ($courses as $course)
            <li>
                <a href="{{url('/')}}/courses/{{$course->id}}">{{$course->course}}</a>
            </li>
        @endforeach
    </ul>
</x-church::layout>                
