window.addEventListener('load', function () {
    let selectedDeviceId;
    const codeReader = new ZXing.BrowserMultiFormatReader()
    codeReader.listVideoInputDevices()
    .then((videoInputDevices) => {
        const sourceSelect = document.getElementById('sourceSelect')
        selectedDeviceId = videoInputDevices[0].deviceId
        if (videoInputDevices.length >= 1) {
        videoInputDevices.forEach((element) => {
            if (element.label.indexOf('fron') == -1) {
                const sourceOption = document.createElement('option')
                sourceOption.text = element.label
                sourceOption.value = element.deviceId
                sourceSelect.appendChild(sourceOption)
            }
        })

        sourceSelect.onchange = () => {
            selectedDeviceId = sourceSelect.value;
        };

        const sourceSelectPanel = document.getElementById('sourceSelectPanel')
        sourceSelectPanel.style.display = 'block'
        }

        document.getElementById('startButton').addEventListener('click', () => {
            document.getElementById("scanbox").style.display = "block"
            codeReader.decodeFromVideoDevice(selectedDeviceId, 'video', (barcode, err) => {
                if (barcode) {
                    document.getElementById('barcode').value = barcode.text
                    Livewire.dispatch('scanned', { isbn: barcode.text })
                    document.getElementById("scanbox").style.display = "none"
                }
                if (err && !(err instanceof ZXing.NotFoundException)) {
                    console.error(err)
                    document.getElementById('barcode').textContent = err
                }
            })
        })
    })
    .catch((err) => {
        console.error(err)
    })
})