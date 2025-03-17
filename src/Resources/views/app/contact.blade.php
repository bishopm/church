<x-church::layouts.app pageName="Contac us">
    <div class="container">
        <div class="row gy-4">
            <div class="col-md-6 col-lg-6">
                <h3>Contact us</h3>
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
                            @honeypot
                            <button class="btn btn-dark bi bi-send"></button>
                        </div>
                    </form>
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
            <div class="bg-secondary p-3 rounded mt-3">
                <h5><span class="bi bi-bank2" style="padding-right: 10px;"></span>Bank details</h5>
                {!! nl2br(setting('admin.bank_details')) !!}
            </div>
        </div>
    </div>
</x-church::layout>                
