<x-church::website.layout pageName="Home">
<!-- About Section -->
<section id="about" class="about section light-background">
    <div class="container">
    <div class="row align-items-center justify-content-between">
        <div class="col-lg-7 mb-5 mb-lg-0 order-lg-2" data-aos="fade-up" data-aos-delay="400">
        <div class="swiper init-swiper">
            <script type="application/json" class="swiper-config">
            {
                "loop": true,
                "speed": 600,
                "autoplay": {
                "delay": 5000
                },
                "slidesPerView": "auto",
                "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
                },
                "breakpoints": {
                "320": {
                    "slidesPerView": 1,
                    "spaceBetween": 40
                },
                "1200": {
                    "slidesPerView": 1,
                    "spaceBetween": 1
                }
                }
            }
            </script>
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="{{url('/public/storage/welcomeslide.png')}}" alt="Image" class="img-fluid">
                </div>
                <div class="swiper-slide">
                    <img src="{{url('/public/storage/knowslide.png')}}" alt="Image" class="img-fluid">
                </div>
                <div class="swiper-slide">
                    <img src="{{url('/public/storage/growslide.png')}}" alt="Image" class="img-fluid">
                </div>
                <div class="swiper-slide">
                    <img src="{{url('/public/storage/showslide.png')}}" alt="Image" class="img-fluid">
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
        </div>
        <div class="col-lg-4 order-lg-1">
        <h1 class="mb-4" data-aos="fade-up">
            Welcome to Westville Methodist Church
        </h1>
        <p data-aos="fade-up">
            Far far away, behind the word mountains, far from the countries
            Vokalia and Consonantia, there live the blind texts. Separated they
            live in Bookmarksgrove right at the coast of the Semantics, a large
            language ocean.
        </p>
        </div>
    </div>
    </div>
</section>
<section id="sundays" class="blog-posts section">
    <div class="container section-title" data-aos="fade-up">
        <h2>Sundays</h2>
    </div>
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-6 order-lg-1">
                <h1 class="mb-4" data-aos="fade-up">
                    Everyone's welcome!
                </h1>
                <p data-aos="fade-up">
                    We have three services every Sunday:
                    <ul>
                        <li>07h30 (traditional hymns)</li>
                        <li>09h00 (family service with teen church)</li>
                        <li>18h30 (informal, contemporary)</li>
                    </ul>

                    Our 09h00 service is streamed live each week <br>
                    on our <a href="{{setting('website.youtube_channel')}}" target="_blank">YouTube channel</a>.
                </p>
            </div>
            <div class="col-lg-6 order-lg-1">                
                <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-audio-tab" data-bs-toggle="pill" data-bs-target="#pills-audio" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Sermon audio</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-video-tab" data-bs-toggle="pill" data-bs-target="#pills-video" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Service video</button>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-audio" role="tabpanel" aria-labelledby="pills-audio-tab">
                        <script type="module">
                            import { VidstackPlayer, VidstackPlayerLayout } from 'https://cdn.vidstack.io/player';

                            const player = await VidstackPlayer.create({
                                target: '#sermon',
                                title: '{{$sermon->title}}',
                                src: '{{$sermon->audio}}',
                                layout: new VidstackPlayerLayout({}),
                            });
                        </script>
                        <div class="card">
                            <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light">
                                <a href="{{url('/')}}/sermons/{{date('Y',strtotime($sermon->series->startingdate))}}/{{$sermon->series->slug}}"><img class="card-img-top" src="{{url('/public/storage/' . $sermon->series->image)}}"
                                alt="{{$sermon->series->series}}">
                                </a>
                            </div>
                            <div class="card-body text-center">
                                <h5 class="h5 font-weight-bold">
                                    <a href="{{url('/')}}/sermons/{{date('Y',strtotime($sermon->servicedate))}}/{{$sermon->series->slug}}/{{$sermon->id}}">{{$sermon->title}}</a>
                                </h5>
                                <p class="mb-0">{{$sermon->readings}} ({{$sermon->person->firstname}} {{$sermon->person->surname}})</p>
                                <div id="sermon"></div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-video" role="tabpanel" aria-labelledby="pills-video-tab">
                        <iframe width="560" height="315" src="https://youtube.com/embed/{{substr($sermon->video,8+strpos($sermon->video,'watch?v='))}}" frameborder="0" allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="blog" class="blog-posts section light-background">
    <div class="container section-title" data-aos="fade-up">
    <h2>Blog Posts</h2>
    </div>
    <div class="container">
        <div class="row gy-4">
            @foreach ($blogs as $blog)
                <div class="col-md-4 col-sm-12">
                    <div class="post-entry" data-aos="fade-up" data-aos-delay="100">
                        <a href="{{url('/blog') . '/' . date('Y',strtotime($blog->published_at)) . '/' . date('m',strtotime($blog->published_at)) . '/' . $blog->slug}}" class="thumb d-block"><img src="{{url('/public/storage/' . $blog->image)}}" alt="Image" class="img-fluid rounded"></a>
                        <div class="post-content text-justify px-0">
                            <div class="meta mb-0">
                                <h3 class="text-center"><a href="{{url('/blog') . '/' . date('Y',strtotime($blog->published_at)) . '/' . date('m',strtotime($blog->published_at)) . '/' . $blog->slug}}">{{$blog->title}}</a></h3>
                                <a href="{{url('/people') . '/' . $blog->person->slug}}" class="cat">{{$blog->person->fullname}}</a> • <span class="date">{{date('Y-m-d',strtotime($blog->published_at))}}</span>
                                @foreach ($blog->tags as $tag)
                                    <span class="badge text-uppercase"><a href="{{url('/')}}/subject/{{$tag->slug}}" class="">{{$tag->name}}</a></span>
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
    </div>
</section><!-- /Blog Posts Section -->

<section id="connecting" class="section">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2>Connecting</h2>
    </div><!-- End Section Title -->
    <div class="container">
        <div class="row gy-4">
            <div class="col-md-4 text-center">
                <img src="{{url('/public/church/images/smallgroups.png')}}" height="80px" alt="Image">
                <h4>Groups</h4>Great as it is to be all together on Sundays, it is in small groups that we experience the best of community life - friendships, encouragement and a shared journey following Jesus in the midst of ordinary life. Contact us if you'd like to join a group, or visit our groups page for news and more info.
            </div>
            <div class="col-md-4 text-center">
                <img src="{{url('/public/church/images/meet.png')}}" height="80px" alt="Image"><h4>Come and meet us</h4>It's easy to feel anonymous in a church community, but we really want to get to know all our people. We serve tea and eats at every service and would love to meet you! If you're new, sign up for one of our regular Newcomers Tea's.
            </div>
            <div class="col-md-4 text-center">
                <img src="{{url('/public/church/images/whatsapp.png')}}" height="80px" alt="Image"><h4>Staying in the loop</h4>Contact the office to be added to our WhatsApp group and/or email list to keep up to date with what is happening in our community (and have a look at our weekly Staying Connected newsletter).
            </div>
        </div>
    </div>
</section>

<section id="serving" class="section light-background">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2>Getting involved</h2>
    </div><!-- End Section Title -->
    <div class="container">
        <div class="row gy-4">
            <div class="col-md-4 text-center">
                <img src="{{url('/public/church/images/outreach.png')}}" height="80px" alt="Image">
                <h4>Mission projects</h4>We support many worthwhile causes in our area and host two relief projects on our property - the Westville Churches Food Bank and our Soup Kitchen. Plan to come and visit these projects and find out where you can play your part.
            </div>
            <div class="col-md-4 text-center">
                <img src="{{url('/public/church/images/serve.png')}}" height="80px" alt="Image">
                <h4>Serving in a team</h4>Serving forms us. It changes us from guests into hosts and really makes us feel like part of the church family. Whatever your gifts and availability, we have a team for you to join - musicians, greeters, tea / coffee pourers, tech support, maintenance gurus, designers, decorators, organisers, communicators, and more! Let us know where you'd like to help.
            </div>
            <div class="col-md-4 text-center">
                <img src="{{url('/public/church/images/giving.png')}}" height="80px" alt="Image">
                <h4>Giving</h4>Ministry is made possible at WMC through the faithful and generous giving of our people. More than that, for Jesus, giving is a discipleship issue - it reveals where our heart is. Find more details about our planned giving programme here.
            </div>
        </div>
    </div>
</section>

<section id="faqs" class="faq section">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Frequently Asked Questions</h2>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up">
        <div class="row">
          <div class="col-12">
            <div class="custom-accordion" id="accordion-faq">
              <div class="accordion-item">
                <h2 class="mb-0">
                  <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-faq-1">
                    Weddings and memorial services
                  </button>
                </h2>

                <div id="collapse-faq-1" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion-faq">
                  <div class="accordion-body">
                    Our minister conducts a limited number of wedding services for church members and their families. We do not able to host large receptions on our property.
                  </div>
                </div>
              </div>
              <!-- .accordion-item -->

              <div class="accordion-item">
                <h2 class="mb-0">
                  <button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-faq-2" "="">
                How to create your paypal account?
              </button>
            </h2>
            <div id="collapse-faq-2" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion-faq">
                    <div class="accordion-body">
                      A small river named Duden flows by their place and supplies it
                      with the necessary regelialia. It is a paradisematic country, in
                      which roasted parts of sentences fly into your mouth.
                    </div>
              </div>
            </div>
            <!-- .accordion-item -->

            <div class="accordion-item">
              <h2 class="mb-0">
                <button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-faq-3">
                  How to link your paypal and bank account?
                </button>
              </h2>

              <div id="collapse-faq-3" class="collapse" aria-labelledby="headingThree" data-parent="#accordion-faq">
                <div class="accordion-body">
                  When she reached the first hills of the Italic Mountains, she
                  had a last view back on the skyline of her hometown
                  Bookmarksgrove, the headline of Alphabet Village and the subline
                  of her own road, the Line Lane. Pityful a rethoric question ran
                  over her cheek, then she continued her way.
                </div>
              </div>
            </div>
            <!-- .accordion-item -->

          </div>
        </div>
      </div>
      </div>
    </section><!-- /Faq Section -->

<section id="contact" class="section">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2>Contact us</h2>
    </div><!-- End Section Title -->
    <div class="container">
        <div class="row gy-4">
            <div class="col-md-6 col-lg-6">
                <h5><span class="bi bi-pin-map-fill" style="padding-right: 10px;"></span>{{setting('general.physical_address')}}</h5>
                <h5><span class="bi bi-telephone-fill" style="padding-right: 10px;""></span>{{substr(setting('communication.church_telephone'),0,3)}} {{substr(setting('communication.church_telephone'),3,4)}} {{substr(setting('communication.church_telephone'),7,3)}}</h5>
                <h5><span class="bi bi-envelope-fill" style="padding-right: 10px;"></span>{{setting('communication.church_email')}}</h5>
                <h5><span class="bi bi-bank2" style="padding-right: 10px;"></span>Bank details</h5>
                <div class="light-background">{!! nl2br(setting('admin.bank_details')) !!}</div>
            </div>
            <div class="col-md-6 col-lg-6">    
                <div id="mapid" style="height:250px;"></div>
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
