<x-church::layouts.app pageName="People">
    <p class="pb-4">Our calling as a church is to make disciples of Jesus, which means helping our members to grow in maturity and Christlikeness. We have adopted four practices that we believe will help us to do this: We <b>CONNECT</b> meaningfully, <b>WORSHIP</b> intentionally, <b>GIVE</b> generously and <b>SERVE</b> passionately. This section of the app helps you to track your growth and points out next steps if you want to explore new areas of commitment.</p> 
    <ul style="display: flex; justify-content: center;" class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-connect-tab" data-bs-toggle="pill" data-bs-target="#pills-connect" type="button" role="tab" aria-controls="pills-connect" aria-selected="false">
                <i class="bi bi-people"> </i>Connect
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-worship-tab" data-bs-toggle="pill" data-bs-target="#pills-worship" type="button" role="tab" aria-controls="pills-worship" aria-selected="false">
                <i class="bi bi-person-arms-up"> </i>Worship
            </button>
        </li>            
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-give-tab" data-bs-toggle="pill" data-bs-target="#pills-give" type="button" role="tab" aria-controls="pills-give" aria-selected="false">
                <i class="bi bi-wallet2"> </i>Give
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-serve-tab" data-bs-toggle="pill" data-bs-target="#pills-serve" type="button" role="tab" aria-controls="pills-serve" aria-selected="true">
                <i class="bi bi-hourglass-split"> </i>Serve
            </button>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="pills-connect" role="tabpanel" aria-labelledby="pills-connect-tab">
            <div class="card p-3">
                @if(count($fellowship))
                    These are the fellowship groups that you belong to:
                    <ul>
                        @foreach ($fellowship as $fgroup)
                            <li>{{$fgroup->groupname}}</li>
                        @endforeach
                    </ul>
                    <a class="btn btn-secondary" href="{{url('/contact')}}">Let the office know if these details need to be updated</a> 
                @else
                    <a class="btn btn-secondary" href="{{url('/contact')}}">To find out about joining a group, send us a message</a> 
                @endif
            </div>
        </div>
        <div class="tab-pane fade show" id="pills-worship" role="tabpanel" aria-labelledby="pills-worship-tab">
            <div class="card p-3">
                Based on the use of your nametag, you have participated in services as follows during the last year:
                @foreach ($worship as $service=>$stat)
                    <div><b>{{$service}}  </b>{{$stat}}</div>
                @endforeach
            </div>
        </div>        
        <div class="tab-pane fade show" id="pills-give" role="tabpanel" aria-labelledby="pills-give-tab">
            <div class="card p-3">
                @if($indiv->giving)
                    Giving records for PG {{$indiv->giving}} for the last 12 months:
                    <table class="table">
                        <tr><th>Date</th><th>Amount</th></tr>
                        @foreach ($giving as $gift)
                            <tr><td>{{$gift->paymentdate}}</td><td>R {{number_format($gift->amount,0)}}</td></tr>
                        @endforeach
                    </table>
                @else
                    <a class="btn btn-secondary" href="{{url('/contact')}}">To sign up as a planned giver, send us a message</a> 
                @endif
            </div>
        </div>
        <div class="tab-pane fade show" id="pills-serve" role="tabpanel" aria-labelledby="pills-serve-tab">
            <div class="card p-3">
                @if(count($servicegroups))
                    You are a member of the following service teams:
                    <ul>
                        @foreach ($servicegroups as $sgroup)
                            <li>{{$sgroup->groupname}}</li>
                        @endforeach
                    </ul>
                    <h4>Upcoming roster dates</h4>
                    @forelse ($roster as $kk=>$rosterdate)
                        <div><b>{{$kk}}&nbsp;</b>{{implode(', ',$rosterdate)}}</div>
                    @empty
                        You have no upcoming roster duties
                    @endforelse
                @else
                    <a class="btn btn-secondary" href="{{url('/contact')}}">To join a service team, send us a message</a> 
                @endif
            </div>
        </div>
    </div>
</x-church::layout>                
