<x-church::layouts.app pageName="Devotionals">
    <ul style="display: flex; justify-content: center;" class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-ffdl-tab" data-bs-toggle="pill" data-bs-target="#pills-ffdl" type="button" role="tab" aria-controls="pills-ffdl" aria-selected="false">
                <i class="bi bi-people"> </i>FFDL
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-biay-tab" data-bs-toggle="pill" data-bs-target="#pills-biay" type="button" role="tab" aria-controls="pills-biay" aria-selected="false">
                <i class="bi bi-book"> </i>Bible in a Year
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-prayers-tab" data-bs-toggle="pill" data-bs-target="#pills-prayers" type="button" role="tab" aria-controls="pills-prayers" aria-selected="false">
                <i class="bi bi-person-arms-up"> </i>Prayers
            </button>
        </li>            
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="pills-ffdl" role="tabpanel" aria-labelledby="pills-ffdl-tab">
            <div class="card p-3">
                <h4>{!!$ffdl_title!!}</h4>
                {!!$ffdl!!}
            </div>
        </div>
        <div class="tab-pane fade show" id="pills-biay" role="tabpanel" aria-labelledby="pills-biay-tab">
            <div class="card p-3">
                <iframe src="https://bible.alpha.org/en/express/{{1+date('z')}}/index.html" frameborder="0" height="700px" allowfullscreen></iframe>
            </div>
        </div>
        <div class="tab-pane fade show" id="pills-prayers" role="tabpanel" aria-labelledby="pills-prayers-tab">
            <div class="card p-3">
            <img class="card-img-top pb-3" src="{{url('/storage/' . $prayers[0]->image)}}">
            <h6 class="text-center">{{$prayers[0]['reading']}}</h6>
            {!!substr(strip_tags($prayers[0]['body']),1,-1)!!}
            </div>
        </div>        
    </div>
</x-church::layout>                
