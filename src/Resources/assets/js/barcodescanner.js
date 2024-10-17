window.onload = () => { 
    initapp();    
}

async function initapp () {
    devices = await getdevices();
    select = document.getElementById('devices');
    console.log(devices);
    devices.forEach((device) => {
        var opt = document.createElement('option');
        opt.value = device.deviceId;
        opt.innerHTML = device.label;
        select.appendChild(opt);
    });
    var Quagga = window.Quagga;
    var scanner = scanner = Quagga
    .decoder({readers: ['ean_reader']})
    .locator({patchSize: 'medium'})
    .fromSource({
        target: '.overlay__content',
        constraints: {
            width: 800,
            height: 600,
            deviceId: document.getElementById("devices").value
        },
        area: {
            top: "0%",
            right: "0%",
            left: "0%",
            bottom: "0%"
        }
    });
    onDetected = function (result) {
        document.getElementById('isbn').value = result.codeResult.code;
        Livewire.dispatch('scanned', { isbn: result.codeResult.code })
        scanner.stop();  // should also clear all event-listeners?
        scanner.removeEventListener('detected', onDetected);
        if (this._overlay) {
            //this._overlay.style.display = "none";
            document.getElementById('camera').remove();
        }
    }.bind(this);
    if (!this._overlay) {
        var content = document.createElement('div'),
            closeButton = document.createElement('div');

        closeButton.appendChild(document.createTextNode('X'));
        content.className = 'overlay__content';
        closeButton.className = 'overlay__close';
        this._overlay = document.createElement('div');
        this._overlay.className = 'overlay';
        this._overlay.appendChild(content);
        content.appendChild(closeButton);
        closeButton.addEventListener('click', function closeClick() {
            closeButton.removeEventListener('click', closeClick);
            cancelCb();
        });
        document.body.appendChild(this._overlay);
    } else {
        var closeButton = document.querySelector('.overlay__close');
        closeButton.addEventListener('click', function closeClick() {
            closeButton.removeEventListener('click', closeClick);
            cancelCb();
        });
    }
    this._overlay.style.display = "block";
    scanner.addEventListener('detected', onDetected).start();
}

function getdevices() {
    return navigator.mediaDevices.enumerateDevices({video: true, audio: false});    
}