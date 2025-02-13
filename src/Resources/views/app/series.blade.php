<x-church::layouts.app pageName="Sermon series: {{$series->series}}">
    <div style="min-height: 240px;">
        <div><img style="float: left; padding-right:10px;" src="{{url('/storage/' . $series->image)}}" alt="Image" class="img-fluid rounded"></div>
        <h3>{{$series->series}}</h3>
        {{$series->description}}
        <ul class="mt-3 list-unstyled">
            @foreach ($series->services as $sermon)
                <li>
                    <small>
                        {{date('d M',strtotime($sermon->servicedate))}}&nbsp;&nbsp;
                        <a href="{{url('/')}}/sermon/{{date('Y',strtotime($sermon->servicedate))}}/{{$sermon->series->slug}}/{{$sermon->id}}">{{$sermon->sermon_title}}</a> 
                        &nbsp;
                        @if (isset($sermon->person))
                            <span class="bi bi-person-circle"></span>&nbsp;<a title="Preacher" href="{{url('/')}}/people/{{$sermon->person->slug}}">{{$sermon->person->fullname}}</a>
                        @endif
                    </small>
                </li>
            @endforeach
        </ul>
    </div>
</x-church::layout>                
