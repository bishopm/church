<x-church::website.layout pageName="Sermon series: {{$series->series}}">
    <div style="min-height: 240px;">
        <div><img style="float: left; padding-right:10px;" width="400px" src="{{url('/storage/app/media/images/sermon/' . $series->image)}}" alt="Image" class="img-fluid rounded"></div>
        <h3>{{$series->series}}</h3>
        {{$series->description}}
        <ul class="mt-3 list-unstyled">
            @foreach ($series->sermons as $sermon)
                <li>
                    {{$sermon->servicedate}}&nbsp;&nbsp;
                    <a href="{{url('/')}}/sermon/{{date('Y',strtotime($sermon->servicedate))}}/{{$sermon->series->slug}}/{{$sermon->id}}">{{$sermon->title}}</a> 
                    &nbsp;<span class="bi bi-book"></span>&nbsp;<a title="Click to open Bible reading" target="_blank" href="http://biblegateway.com/passage/?search={{urlencode($sermon->readings)}}&version=GNT";">{{$sermon->readings}} </a>
                    <span class="bi bi-person-circle"></span>&nbsp;<a title="Preacher" href="{{url('/')}}/people/{{$sermon->person->slug}}">{{$sermon->person->fullname}}</a>
                </li>
            @endforeach
        </ul>
    </div>
</x-church::layout>                
