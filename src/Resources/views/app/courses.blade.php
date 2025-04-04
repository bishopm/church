<x-church::layouts.app pageName="Courses">
    <h1>Courses at {{setting('general.church_abbreviation')}}</h1>
    <ul style="display: flex; justify-content: center;" class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        @if (count($courses['upcoming']))
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-upcoming-tab" data-bs-toggle="pill" data-bs-target="#pills-upcoming" type="button" role="tab" aria-controls="pills-upcoming" aria-selected="false">
                    <i class="bi bi-calendar-check"> </i>Upcoming courses
                </button>
            </li>                
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-library-tab" data-bs-toggle="pill" data-bs-target="#pills-library" type="button" role="tab" aria-controls="pills-library" aria-selected="false">
                    <i class="bi bi-collection"> </i>Course library
                </button>
            </li>
        @else
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-library-tab" data-bs-toggle="pill" data-bs-target="#pills-library" type="button" role="tab" aria-controls="pills-library" aria-selected="false">
                    <i class="bi bi-collection"> </i>Course library
                </button>
            </li>
        @endif
    </ul>
    <div class="tab-content">
        @if (count($courses['upcoming']))
            <div class="tab-pane fade show active" id="pills-upcoming" role="tabpanel" aria-labelledby="pills-upcoming-tab">
                <ul class="list-unstyled">
                    @foreach ($courses['upcoming'] as $key=>$day)
                        @foreach ($day as $course)
                            <li>
                                {{date('j M',$key)}} <a href="{{url('/')}}/courses/{{$course->id}}">{{$course->course}}</a> <small>(Sessions: {{count($course->coursesessions)}})</small>
                            </li>
                        @endforeach
                    @endforeach
                </ul>
            </div>        
            <div class="tab-pane fade" id="pills-library" role="tabpanel" aria-labelledby="pills-library-tab">
                <ul class="list-unstyled">
                    @foreach ($courses['library'] as $course)
                        <li>
                            <a href="{{url('/')}}/courses/{{$course->id}}">{{$course->course}}</a> <small>(Sessions: {{count($course->coursesessions)}})</small>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else 
            <div class="tab-pane fade show active" id="pills-library" role="tabpanel" aria-labelledby="pills-library-tab">
                <ul class="list-unstyled">
                    @foreach ($courses['library'] as $course)
                        <li>
                            <a href="{{url('/')}}/courses/{{$course->id}}">{{$course->course}}</a> <small>(Sessions: {{count($course->coursesessions)}})</small>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>
</x-church::layout>                
