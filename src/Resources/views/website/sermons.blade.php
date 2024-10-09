<x-church::website.layout pageName="Blog post">
<div class="container">
    @foreach ($series as $serie)
        <div class="row pb-3">
            <div class="col-12">
                <a href="{{url('/')}}/sermons/{{date('Y',strtotime($serie->startingdate))}}/{{$serie->slug}}">
                    <img style="float: left; padding-right:10px;" width="400px" src="{{url('/public/storage/' . $serie->image)}}" alt="Image" class="img-fluid rounded">
                    <h4>{{$serie->series}} <small style="color:grey;">{{date('F Y',strtotime($serie->startingdate))}}</small></h4>
                </a>
                {{$serie->description}}<br>
                Sermons: {{count($serie->sermons)}}
            </div>
        </div>
    @endforeach
    <div>
        {{$series->links()}}
    </div>
</div>

</x-church::layout>                
