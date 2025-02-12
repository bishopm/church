<x-church::layouts.app pageName="Sermon">
    <div>
        <div class="row gy-2">
            <div class="col-md-6 col-sm-12">
                <h3>Sermon audio</h3>
                <img width="400px" src="{{url('/storage/' . $series->image)}}" alt="{{$series->series}}">
                <h5 class="h5 font-weight-bold"><a href="#" target="_blank">{{$sermon->sermon_title}}</a></h5>
                <p class="mb-0">
                    <a title="Click to open Bible reading" target="_blank" href="http://biblegateway.com/passage/?search={{urlencode($sermon->reading)}}&version=GNT";">{{$sermon->reading}} </a>
                    ({{$sermon->person->firstname}} {{$sermon->person->surname}})
                </p>
                <div id="sermon" width="400px">
                    <audio controls>
                        <source src="{{$sermon->audio}}" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                </div>
                @if (count($series->services) > 1)
                    <h3>Other sermons in the series</h3>
                    <ul class="list-unstyled">
                        @foreach ($series->services as $ss)
                            @if ($ss->id <> $sermon->id)
                                <li>{{date('d M',strtotime($ss->servicedate))}}&nbsp;&nbsp;
                                    <a href="{{url('/')}}/sermon/{{date('Y',strtotime($ss->servicedate))}}/{{$ss->series->slug}}/{{$ss->id}}">{{$ss->title}}</a> 
                                    &nbsp;<span class="bi bi-book"></span>&nbsp;<a title="Click to open Bible reading" target="_blank" href="http://biblegateway.com/passage/?search={{urlencode($ss->reading)}}&version=GNT";">{{$ss->reading}} </a>
                                    <span class="bi bi-person-circle"></span>&nbsp;<a title="Preacher" href="{{url('/')}}/people/{{$ss->person->slug}}">{{$ss->person->fullname}}</a>
                                </li>
                            @endif
                        @endforeach    
                    </ul>
                @endif
            </div>
            <div class="col-md-6 col-sm-12">
                <h3>Service video</h3>
                <div class="ratio ratio-16x9">
                    <iframe src="https://youtube.com/embed/{{$sermon->video}}" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</x-church::layout>                
