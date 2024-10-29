<x-church::website.applayout pageName="Sermon archives">
    <h3>Sermons</h3>
    @forelse ($series as $serie)
        <div class="row pb-3">
            <div class="col-12">
                <a href="{{url('/')}}/sermon/{{date('Y',strtotime($serie->startingdate))}}/{{$serie->slug}}">
                    <img style="float: left; padding-right:10px;" width="400px" src="{{url('/storage/' . $serie->image)}}" alt="Image" class="img-fluid rounded">
                    <h4>{{$serie->series}} <small style="color:grey;">{{date('F Y',strtotime($serie->startingdate))}}</small></h4>
                </a>
                {{$serie->description}}<br>
                Sermons: {{count($serie->sermons)}}
            </div>
        </div>
    @empty
        No sermons have been uploaded yet.
    @endforelse
    <div>
        {{$series->links()}}
    </div>
</x-church::layout>                
