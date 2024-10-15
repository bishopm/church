<div>
    <div>
        <div class="input-group mb-3">
            <input wire:model.lazy="barcode" id="result" placeholder="Click to scan barcode ->" type="text" class="form-control">
            <div class="input-group-append">
                <button id="startButton" class="btn btn-outline-secondary" type="button"><span class="bi bi-upc-scan"></span></button>
            </div>
        </div>
    </div>
    <div id="scanbox" style="display:none;">
        <div>
            <video id="video" width="100%" height="320" style="border: 1px solid gray"></video>
        </div>
        <div id="sourceSelectPanel" style="display:none">
            <label>Video source</label>
            <select id="sourceSelect" class="form-control"></select>
        </div>
    </div>
</div>
@assets
<script src="{{asset('church/js/Zxing.min.js')}}"></script>
<script src="{{asset('church/js/barcodescanner.js')}}"></script>
@endassets
