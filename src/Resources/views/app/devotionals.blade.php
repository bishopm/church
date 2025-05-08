<x-church::layouts.app pageName="Devotionals">
    <ul style="display: flex; justify-content: center;" class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        @if ($settings['Bible in a year'])
        <li class="nav-item" role="presentation">
            <button class="nav-link @if ($active=='Bible in a year') active @endif" id="pills-biay-tab" data-bs-toggle="pill" data-bs-target="#pills-biay" type="button" role="tab" aria-controls="pills-biay" aria-selected="false">
                <i class="bi bi-book"> </i>Bible in a Year
            </button>
        </li>
        @endif   
        @if ($settings['Faith for daily living'])
        <li class="nav-item" role="presentation">
            <button class="nav-link @if ($active=='Faith for daily living') active @endif" id="pills-ffdl-tab" data-bs-toggle="pill" data-bs-target="#pills-ffdl" type="button" role="tab" aria-controls="pills-ffdl" aria-selected="false">
                <i class="bi bi-people"> </i>FFDL
            </button>
        </li>
        @endif
        @if ($settings['Methodist prayer'])
        <li class="nav-item" role="presentation">
            <button class="nav-link @if ($active=='Methodist prayer') active @endif" id="pills-meth-tab" data-bs-toggle="pill" data-bs-target="#pills-meth" type="button" role="tab" aria-controls="pills-meth" aria-selected="false">
                <i class="bi bi-fire"> </i>Methodist prayer
            </button>
        </li>
        @endif
        @if ($settings['Quiet moments'])
        <li class="nav-item" role="presentation">
            <button class="nav-link @if ($active=='Quiet moments') active @endif" id="pills-quiet-tab" data-bs-toggle="pill" data-bs-target="#pills-quiet" type="button" role="tab" aria-controls="pills-quiet" aria-selected="false">
                <i class="bi bi-person-arms-up"> </i>Quiet Moments
            </button>
        </li>
        @endif
    </ul>
    <div class="tab-content">
        @if ($settings['Bible in a year'])
        <div class="tab-pane fade show @if ($active=='Bible in a year') active @endif" id="pills-biay" role="tabpanel" aria-labelledby="pills-biay-tab">
            <div class="card p-3">
                <iframe src="https://bible.alpha.org/en/express/{{1+date('z')}}/index.html" frameborder="0" height="700px" allowfullscreen></iframe>
            </div>
        </div>
        @endif
        @if ($settings['Faith for daily living'])
        <div class="tab-pane fade show @if ($active=='Faith for daily living') active @endif" id="pills-ffdl" role="tabpanel" aria-labelledby="pills-ffdl-tab">
            <div class="card p-3">
                <h4>{!!$ffdl_title!!}</h4>
                {!!$ffdl!!}
            </div>
        </div>
        @endif
        @if ($settings['Methodist prayer'])
        <div class="tab-pane fade show @if ($active=='Methodist prayer') active @endif" id="pills-meth" role="tabpanel" aria-labelledby="pills-meth-tab">
            <div class="card p-3">
                <iframe src="https://methodistprayer.org/morning" frameborder="0" height="700px" allowfullscreen></iframe>
            </div>
        </div>
        @endif
        @if ($settings['Quiet moments'])
        <div class="tab-pane fade show @if ($active=='Quiet moments') active @endif" id="pills-quiet" role="tabpanel" aria-labelledby="pills-quiet-tab">
            <h4>Quiet moments</h4>
            @foreach ($quiets as $quiet)
                <p><a href="{{url('/storage/' . $quiet->filename)}}">{{$quiet->document}}</a></p>
            @endforeach
            {{ $quiets->links() }}
        </div>        
        @endif
    </div>
    @if ($active=="")
        <p>You have no devotional content selected in your app settings.</p><p>Click <a href="{{route('app.settings')}}">here</a> to change your settings.</p>
    @endif
</x-church::layout>                
