<div>
    <div data-mdb-input-init class="form-outline mb-4">
        <input wire:model.live="course" class="form-control my-2" placeholder="Search by course name or subject" />
    </div>
    <table class="table table-condensed">
        <tr><th>Course</th><th>Wks</th><th>Subject</th></tr>
    @foreach ($courses as $course)
        <tr wire:key="{{time().$course['id']}}">
            <td><a href="{{url('/')}}/courses/{{$course['id']}}">{{$course['course']}}</a></td>
            <td>{{count($course['coursesessions'])}}</td>
            <td>
                @foreach ($course['tags'] as $tag)
                    <span class="badge badge-primary">{{$tag['name']}}</span>
                @endforeach
            </td>
        </tr>
    @endforeach
    </table>
</div>