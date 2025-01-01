<x-church::website.layout pageName="Home">
<!-- About Section -->
<div id="about">
    <div data-aos="fade-up" data-aos-delay="400">
        <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{asset('/church/images/welcomeslide.png')}}" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="{{asset('/church/images/knowslide.png')}}" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="{{asset('/church/images/growslide.png')}}" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="{{asset('/church/images/showslide.png')}}" class="d-block w-100">
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
@if (isset($notification))
    <div class="alert alert-info alert-dismissible fade show text-center" role="alert">
        {{$notification}}
        <button type="button" class="btn close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
<section id="sundays" class="section">
    <div class="container section-title" data-aos="fade-up">
        <h1>Sundays</h1>
        <a href="{{url('/')}}/sermons" class="content-subtitle">See all sermons</a>
    </div>
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-6 order-lg-1">
                <h3 class="mb-4" data-aos="fade-up">
                    Join us on Sunday!
                </h3>
                <p data-aos="fade-up">
                    We have three services every Sunday:
                    <ul>
                        <li>07h30 (traditional, songs from the hymn book)</li>
                        <li>09h00 (family service with children's church and youth)</li>
                        <li>18h30 (informal, contemporary)</li>
                    </ul>

                    Our 09h00 service is streamed live each week on our <a href="{{setting('website.youtube_channel')}}" target="_blank">YouTube channel</a>.
                </p>
            </div>
            @if (isset($sermon))
            <div class="col-lg-6 order-lg-1">                
                <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-upcoming-tab" data-bs-toggle="pill" data-bs-target="#pills-upcoming" type="button" role="tab" aria-controls="pills-upcoming" aria-selected="true">Coming up</button>
                    </li>    
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-audio-tab" data-bs-toggle="pill" data-bs-target="#pills-audio" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Latest sermon audio</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-video-tab" data-bs-toggle="pill" data-bs-target="#pills-video" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Latest service video</button>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-upcoming" role="tabpanel" aria-labelledby="pills-upcoming-tab">
                        <div class="ratio ratio-16x9">
                            <iframe src='https://www.youtube.com/embed/live_stream?autoplay=1&channel={{setting('website.youtube_channel_id')}}' frameborder='0' allowfullscreen></iframe>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-audio" role="tabpanel" aria-labelledby="pills-audio-tab">
                        <div class="card">
                            <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light">
                                <a href="{{url('/')}}/sermons/{{date('Y',strtotime($sermon->series->startingdate))}}/{{$sermon->series->slug}}"><img class="card-img-top" src="{{url('/storage/app/media/images/sermon/' . $sermon->series->image)}}"
                                alt="{{$sermon->series->series}}">
                                </a>
                            </div>
                            <div class="card-body text-center">
                                <h5 class="h5 font-weight-bold">
                                    <a href="{{url('/')}}/sermon/{{date('Y',strtotime($sermon->servicedate))}}/{{$sermon->series->slug}}/{{$sermon->id}}">{{$sermon->title}}</a>
                                </h5>
                                <p class="mb-0">{{$sermon->readings}} ({{$sermon->person->firstname}} {{$sermon->person->surname}})</p>
                                <div id="sermon"></div>
                            </div>
                            <div class="text-center">
                                <audio controls>
                                    <source src="{{$sermon->audio}}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-video" role="tabpanel" aria-labelledby="pills-video-tab">
                        <div class="ratio ratio-16x9">
                            <iframe src="https://youtube.com/embed/{{substr($sermon->video,8+strpos($sermon->video,'watch?v='))}}" frameborder="0" allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
<section id="blog" class="blog-posts section light-background">
    <div class="container section-title" data-aos="fade-up">
    <h1>Blog Posts</h1>
    <a href="{{url('/')}}/blog" class="content-subtitle">See all blog posts</a>
    </div>
    <div class="container">
        @if (isset($blogs))
        <div class="row gy-4">
            @foreach ($blogs as $blog)
                <div class="col-md-4 col-sm-12">
                    <div class="post-entry" data-aos="fade-up" data-aos-delay="100">
                        <a href="{{url('/blog') . '/' . date('Y',strtotime($blog->published_at)) . '/' . date('m',strtotime($blog->published_at)) . '/' . $blog->slug}}" class="thumb d-block"><img src="{{url('/storage/app/media/images/blog/' . $blog->image)}}" alt="Image" class="img-fluid rounded"></a>
                        <div class="text-justify">
                            <div class="meta mb-0">
                                <h3 class="text-center"><a href="{{url('/blog') . '/' . date('Y',strtotime($blog->published_at)) . '/' . date('m',strtotime($blog->published_at)) . '/' . $blog->slug}}">{{$blog->title}}</a></h3>
                                <a href="{{url('/people') . '/' . $blog->person->slug}}" class="cat">{{$blog->person->fullname}}</a> • <span class="date">{{date('Y-m-d',strtotime($blog->published_at))}}</span>
                                @foreach ($blog->tags as $tag)
                                    <span class="badge text-uppercase"><a href="{{url('/')}}/subject/{{$tag->slug}}" class="badge">{{$tag->name}}</a></span>
                                @endforeach
                            </div>
                            <p>
                                {{$blog->excerpt}}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif
    </div>
</section><!-- /Blog Posts Section -->

<section id="connecting" class="section">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h1>Connecting</h1>
    </div><!-- End Section Title -->
    <div class="container">
        <div class="row gy-4">
            <div class="col-md-4 text-center">
                <img src="{{url('/church/images/smallgroups.png')}}" height="80px" alt="Image">
                <h4>Groups</h4>Great as it is to be all together on Sundays, it is in small groups that we experience the best of community life - friendships, encouragement and a shared journey following Jesus in the midst of ordinary life. Contact us if you'd like to join a group, or visit our <a href="{{url('/')}}/groups">groups</a> page for news and more info.
            </div>
            <div class="col-md-4 text-center">
                <img src="{{url('/church/images/meet.png')}}" height="80px" alt="Image"><h4>Come and meet us</h4>It's easy to feel anonymous in a church community, but we really want to get to know all our people. We serve tea and eats at every service and would love to meet you! If you're new, sign up for one of our regular Newcomers Tea's.
            </div>
            <div class="col-md-4 text-center">
                <img src="{{url('/church/images/whatsapp.png')}}" height="80px" alt="Image"><h4>Staying in the loop</h4>Contact the office to be added to our WhatsApp group and/or email list to keep up to date with what is happening in our community (and have a look at our weekly Staying Connected newsletter).
            </div>
        </div>
    </div>
</section>

<section id="serving" class="section light-background">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h1>Getting involved</h1>
    </div><!-- End Section Title -->
    <div class="container">
        <div class="row gy-4">
            <div class="col-md-4 text-center">
                <img src="{{url('/church/images/outreach.png')}}" height="80px" alt="Image">
                <h4>Mission projects</h4>We support many worthwhile causes in our area and host two relief projects on our property - the Westville Churches Food Bank and our Soup Kitchen. Plan to come and visit these <a href="{{url('/')}}/projects">projects</a> and find out where you can play your part.
            </div>
            <div class="col-md-4 text-center">
                <img src="{{url('/church/images/serve.png')}}" height="80px" alt="Image">
                <h4>Serving in a team</h4>Serving forms us. It changes us from guests into hosts and really makes us feel like part of the church family. Whatever your gifts and availability, we have a team for you to join - musicians, greeters, tea / coffee pourers, tech support, maintenance gurus, designers, decorators, organisers, communicators, and more! Let us know where you'd like to help.
            </div>
            <div class="col-md-4 text-center">
                <img src="{{url('/church/images/giving.png')}}" height="80px" alt="Image">
                <h4>Giving</h4>Ministry is made possible at WMC through the faithful and generous giving of our people. More than that, for Jesus, giving is a discipleship issue - it reveals where our heart is. Find more details about our planned giving programme <a href="{{url('/')}}/giving">here.</a>
            </div>
        </div>
    </div>
</section>

<section id="faqs" class="faq section">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h1>Questions</h1>
    </div><!-- End Section Title -->

    <div class="container" data-aos="fade-up">
        <div class="row">
            <div class="col-12">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Memorial services
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse show collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                If you need help or advice with a memorial service, please contact our office. Your family do not need to be linked to our church - we will do our best to help.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                Weddings
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                Our minister conducts a limited number of wedding services for couples connected to our church. We are not able to host large receptions on our property. 
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                Baptism
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                We baptise adults (who have not been baptised before) and the children of members of the church. Contact our minister for more details.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section><!-- /Faq Section -->

<section id="contact" class="section">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h1>Contact us</h1>
    </div><!-- End Section Title -->
    <div class="container">
        <div class="row gy-4">
            <div class="col-md-6 col-lg-6">
                <h5><span class="bi bi-pin-map-fill" style="padding-right: 10px;"></span>{{setting('general.physical_address')}}</h5>
                <h5><span class="bi bi-telephone-fill" style="padding-right: 10px;""></span>{{substr(setting('website.church_telephone'),0,3)}} {{substr(setting('website.church_telephone'),3,4)}} {{substr(setting('website.church_telephone'),7,3)}}</h5>
                <h5><span class="bi bi-envelope-fill" style="padding-right: 10px;"></span>{{setting('email.church_email')}}</h5>
                <div class="mb-4">
                    <a target="_blank" title="Facebook page" href="{{setting('website.facebook_page')}}"><span class="bi bi-facebook h3"></span></a>&nbsp;
                    <a target="_blank" title="Instagram page" href="{{setting('website.instagram_page')}}"><span class="bi bi-instagram h3"></span></a>&nbsp;
                    <a target="_blank" title="YouTube channel" href="{{setting('website.youtube_channel')}}"><span class="bi bi-youtube h3"></span></a>&nbsp;
                    <a target="_blank" title="Youversion page" href="{{setting('website.youversion_page')}}"><span class="bi bi-bookmark-plus h3"></span></a>&nbsp;
                    <a target="_blank" title="WhatsApp" href="https://wa.me/27{{substr(setting('website.whatsapp'),1)}}"><span class="bi bi-whatsapp h3"></span></a>
                </div>
                <div>
                    <h3>Send us a message</h3>
                    <form method="post">
                        @csrf
                        <textarea class="form-control" name="message" placeholder="Message" rows="8" required></textarea>
                        <div class="input-group my-2">
                            <input name="user" type="email" class="form-control" placeholder="Email address" required>&nbsp;
                            <button class="btn btn-dark bi bi-send"></button>
                        </div>
                    </form>
                </div>
                <div class="bg-secondary p-3 rounded">
                    <h5><span class="bi bi-bank2" style="padding-right: 10px;"></span>Bank details</h5>
                    {!! nl2br(setting('admin.bank_details')) !!}
                </div>
            </div>
            <div class="col-md-6 col-lg-6">    
                <div id="mapid" style="height:333px;"></div>
                <script>
                    var coords;
                    coords = [{{setting('general.map_location')['lat']}},{{setting('general.map_location')['lng']}}];
                    var mymap = L.map('mapid').setView(L.latLng(coords), 15);
                    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                    maxZoom: 18,
                    id: 'mapbox/streets-v11',
                    tileSize: 512,
                    zoomOffset: -1,
                    accessToken: '{{setting('website.mapbox_token')}}'
                }).addTo(mymap);
                var marker = L.marker(L.latLng(coords)).addTo(mymap);
                </script>
            </div>
        </div>
    </div>
</section>
</x-church::layout>                
