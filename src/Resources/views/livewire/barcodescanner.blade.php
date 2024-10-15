<div>
    <div>
        <input id="barcode" class="form-control">
    <a class="button" id="startButton">Start</a>
    </div>

    <div>
        <video id="video" width="640" height="320" style="border: 1px solid gray"></video>
    </div>
    <div id="sourceSelectPanel" style="display:none">
        <label for="sourceSelect">Change video source:</label>
        <select id="sourceSelect" style="max-width:400px"></select>
    </div>

    <label>Result:</label>
    <pre><code id="result"></code></pre>
</div>
@assets
<script src="{{asset('church/js/Zxing.min.js')}}"></script>
<script src="{{asset('church/js/barcodescanner.js')}}"></script>
@endassets
