<div
    x-data
    x-init="
        const initScanner = () => {
            // Wait for the library to load
            if (!window.Html5Qrcode) {
                console.log('Waiting for Html5Qrcode to load...');
                setTimeout(initScanner, 100);
                return;
            }

            if (!window.html5QrCode) {
                window.html5QrCode = new Html5Qrcode('reader');
            }

            // Function to safely stop the scanner
            const stopScanner = () => {
                if (window.html5QrCode && window.html5QrCode.isScanning) {
                    window.html5QrCode.stop().catch(err => {
                        console.warn('Scanner was already stopped or could not be stopped cleanly.', err);
                    });
                }
            };

            // Find the close button and assign the stop function to it
            const closeButton = document.getElementById('close-barcode-scanner-button');
            if (closeButton) {
                closeButton.addEventListener('click', stopScanner);
            }

            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    const cameraId = devices[0].id;
                    window.html5QrCode.start(
                        cameraId,
                        {
                            fps: 10,
                            qrbox: { width: 300, height: 200 }, // ideal for barcodes
                            formatsToSupport: [
                                Html5QrcodeSupportedFormats.EAN_13,
                                Html5QrcodeSupportedFormats.CODE_128,
                                Html5QrcodeSupportedFormats.UPC_A,
                                Html5QrcodeSupportedFormats.UPC_E,
                                Html5QrcodeSupportedFormats.CODE_39,
                                Html5QrcodeSupportedFormats.ITF,
                            ],
                        },
                        decodedText => {
                            console.log('Code detected:', decodedText);

                            // Fill the form input
                            const input = document.querySelector('input[name=barcode]');
                            if (input) {
                                input.value = decodedText;
                                // This notifies Livewire that the value has changed
                                input.dispatchEvent(new Event('input', { bubbles: true }));
                            }

                            $wire.set('data.barcode', decodedText);

                            // Close the modal
                            const closeButton = document.getElementById('close-barcode-scanner-button');
                            closeButton.click();

                            // The scanner is stopped by the click event listener now
                        },
                        errorMessage => {
                            console.warn('Scan error:', errorMessage);
                        }
                    );
                } else {
                    console.error('No cameras found');
                }
            }).catch(err => console.error(err));
        }

        initScanner();
    "
>
    <div id="reader" style="width:100%; min-height:300px;"></div>
</div>
