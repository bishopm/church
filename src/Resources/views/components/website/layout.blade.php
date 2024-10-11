<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{setting('general.church_name')}} - {{$pageName}}</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="/public/church/img/favicon.png" rel="icon">
  <link href="/public/church/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
  
  <!-- Vendor CSS Files -->
  <link href="/public/church/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/public/church/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="/public/church/vendor/aos/aos.css" rel="stylesheet">
  <link href="/public/church/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="/public/church/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

  <!-- Media player -->
  <link rel="stylesheet" href="https://cdn.vidstack.io/player/theme.css" />
  <link rel="stylesheet" href="https://cdn.vidstack.io/player/audio.css" />
  <script src="https://cdn.vidstack.io/player" type="module"></script>
  <!-- Main CSS File -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <link href="/public/church/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Active
  * Template URL: https://bootstrapmade.com/active-bootstrap-website-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  @laravelPWA
</head>

<body class="index-page">

  <header id="header" class="dark-background header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="{{url('/')}}" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="/public/church/img/logo.png" alt=""> -->
        <h1 class="sitename">{{setting('general.church_abbreviation')}}</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="{{url('/')}}#about" class="active">Home</a></li>
          <li><a href="{{url('/')}}#sundays">Sundays</a></li>
          <li><a href="{{url('/')}}#blog">Blog</a></li>
          <li><a href="{{url('/')}}#connecting">Connecting</a></li>
          <li><a href="{{url('/')}}#serving">Getting involved</a></li>
          <li><a href="{{url('/')}}#contact">Contact</a></li>
          @if (!count($member))<li><a href="{{url('/')}}/login">Login</a></li>@else
          <li><a href="{{url('/')}}/mymenu">My menu</a></li>@endif
          <li class="dropdown"><a href="#"><span>More</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="{{url('/')}}#faqs">FAQ's</a></li>
              <li><a href="{{url('/')}}/giving">Giving</a></li>
              <li><a href="{{url('/')}}/groups">Groups</a></li>
              <li><a href="{{url('/')}}/projects">Mission projects</a></li>
              <li class="dropdown"><a href="#"><span>Resources</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <li><a href="{{url('/')}}/blog">Blog archives</a></li>
                  <li><a href="{{url('/')}}/sermons">Sermon archives</a></li>
                  <li><a href="{{url('/')}}/books">Books</a></li>
                </ul>
              </li>
            </ul>
          </li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
    @laravelPWA
  </header>

  <main class="main">
    <div class="container my-3">
      @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">  
          <p>{{ session('message') }}</p>          
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      {{$slot}}
    </div>
  </main>

  <footer id="footer" class="footer light-background">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4 mb-md-0">
          <div class="widget text-start">
            @if (count($member))
              <h3 class="widget-heading">User details</h3>
            @else
              <h3 class="widget-heading">Login</h3>
            @endif
            <p class="mb-4">
              @if (count($member))
                Logged in as {{$member['fullname']}}
              @else
                Log in using your cellphone number and get access to member content
              @endif
            </p>
            <p class="mb-0">
              @if (count($member))
                <a href="{{url('/')}}/logout" class="btn-learn-more">Logout</a>
              @else 
                <a href="{{url('/')}}/login" class="btn-learn-more">Login</a>
              @endif
            </p>
          </div>
        </div>
        <div class="col-md-4 mb-md-0">
          <div class="widget">
            <div class="footer-subscribe">
              <h3 class="widget-heading">Subscribe to Staying Connected</h3>
              <form action="forms/newsletter.php" method="post" class="php-email-form">
                <div class="mb-2">
                  <input type="text" class="form-control" name="email" placeholder="Enter your email">

                  <button type="submit" class="btn btn-link">
                    <span class="bi bi-arrow-right"></span>
                  </button>
                </div>
                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message">
                  Your subscription request has been sent. Thank you!
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-md-0">
          <div class="widget">
            <h3 class="widget-heading">Connect</h3>
            <ul class="list-unstyled social-icons light mb-3">
              <li>
                <a target="_blank" title="Facebook page" href="{{setting('website.facebook_page')}}"><span class="bi bi-facebook"></span></a>
              </li>
              <li>
                <a target="_blank" title="Instagram page" href="{{setting('website.instagram_page')}}"><span class="bi bi-instagram"></span></a>
              </li>
              <li>
                <a target="_blank" title="YouTube channel" href="{{setting('website.youtube_channel')}}"><span class="bi bi-youtube"></span></a>
              </li>
              <li>
                <a target="_blank" title="Youversion page" href="{{setting('website.youversion_page')}}"><span class="bi bi-bookmark-plus"></span></a>
              </li>
              <li>
                <a target="_blank" title="WhatsApp" href="https://wa.me/27{{substr(setting('communication.whatsapp'),1)}}"><span class="bi bi-whatsapp"></span></a>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="copyright d-flex flex-column flex-md-row align-items-center justify-content-md-between">
        <p>© Westville Methodist Church {{date('Y')}}</p>
        <div class="credits">
          <!-- All the links in the footer should remain intact. -->
          <!-- You can delete the links only if you've purchased the pro version. -->
          <!-- Licensing information: https://bootstrapmade.com/license/ -->
          <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
          <a style="color:lightgray"; target="_blank" href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>
  <!-- Vendor JS Files -->
  <script src="/public/church/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/public/church/vendor/php-email-form/validate.js"></script>
  <script src="/public/church/vendor/aos/aos.js"></script>
  <script src="/public/church/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="/public/church/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="/public/church/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="/public/church/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="/public/church/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="/public/church/js/main.js"></script>
</body>

</html>