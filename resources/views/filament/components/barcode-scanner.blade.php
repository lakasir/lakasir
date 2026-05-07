@props(['statePath'])

@php
    $readerId = 'reader-' . trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($statePath)), '-');
@endphp

<div x-data x-init="
    const statePath = @js($statePath);
    const readerId = @js($readerId);

    const findCloseButton = () => {
        const dialog = $el.closest('[role=\'dialog\']');
        return dialog
            ? dialog.querySelector('[id^=\'close-barcode-scanner\']')
            : document.getElementById('close-barcode-scanner-button');
    };

    const onScanSuccess = (decodedText, decodedResult) => {

        $wire.set(statePath, decodedText);

        findCloseButton()?.click();
    };


    const onScanFailure = (error) => {

        // console.warn(`Code scan error = ${error}`);
    };


    const startScanner = () => {
        if (!window.Html5QrcodeScanner) {
            setTimeout(startScanner, 100);
            return;
        }


        const html5QrcodeScanner = new Html5QrcodeScanner(
            readerId,
            {
                fps: 10,
                qrbox: { width: 250, height: 150 },
                formatsToSupport: [
                    Html5QrcodeSupportedFormats.EAN_13,
                    Html5QrcodeSupportedFormats.CODE_128,
                    Html5QrcodeSupportedFormats.UPC_A,
                    Html5QrcodeSupportedFormats.UPC_E,
                    Html5QrcodeSupportedFormats.CODE_39,
                    Html5QrcodeSupportedFormats.ITF,
                ]
            },
            /* verbose= */ false
        );


        html5QrcodeScanner.render(onScanSuccess, onScanFailure);


        const closeButton = findCloseButton();
        if (closeButton) {
            closeButton.addEventListener('click', () => {

                if (html5QrcodeScanner && html5QrcodeScanner.getState() === Html5QrcodeScannerState.SCANNING) {
                    html5QrcodeScanner.clear().catch(err => {
                        console.error('Failed to clear the scanner.', err);
                    });
                }
            });
        }
    };

    
    startScanner();
">
    {{-- wire:ignore prevents Livewire from diffing library-injected DOM --}}
    <div wire:ignore class="barcode-scanner-wrapper">
        <div id="{{ $readerId }}" style="width: 100%;"></div>
    </div>

    @once
    <style>
        /* html5-qrcode library button & control styling */
        .barcode-scanner-wrapper [id$="__dashboard_section_csr"] button,
        .barcode-scanner-wrapper [id$="__dashboard_section_swaplink"] {
            background-color: #f97316 !important;
            border: none !important;
            color: #fff !important;
            padding: 0.5rem 1.25rem !important;
            border-radius: 0.5rem !important;
            font-weight: 600 !important;
            font-size: 0.875rem !important;
            cursor: pointer !important;
            transition: background-color 0.2s ease !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1) !important;
            margin: 0.25rem 0 !important;
        }

        .barcode-scanner-wrapper [id$="__dashboard_section_csr"] button:hover,
        .barcode-scanner-wrapper [id$="__dashboard_section_swaplink"]:hover {
            background-color: #ea580c !important;
        }

        .barcode-scanner-wrapper [id$="__dashboard_section"] {
            padding: 0.75rem !important;
            margin-top: 0.5rem !important;
        }

        .barcode-scanner-wrapper [id$="__dashboard_section_csr"] {
            display: flex !important;
            flex-direction: column !important;
            gap: 0.5rem !important;
            align-items: stretch !important;
        }

        .barcode-scanner-wrapper [id$="__dashboard_section_csr"] select {
            width: 100% !important;
            padding: 0.5rem 0.75rem !important;
            border-radius: 0.5rem !important;
            border: 1px solid #d1d5db !important;
            background-color: #fff !important;
            color: #111827 !important;
            font-size: 0.875rem !important;
            outline: none !important;
            transition: border-color 0.2s ease !important;
        }

        .barcode-scanner-wrapper [id$="__dashboard_section_csr"] select:focus {
            border-color: #f97316 !important;
            box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.25) !important;
        }

        .barcode-scanner-wrapper [id$="__scan_region"] {
            min-height: 200px !important;
            border-radius: 0.5rem !important;
            overflow: hidden !important;
        }

        .barcode-scanner-wrapper > div {
            border: none !important;
        }

        .barcode-scanner-wrapper [id$="__dashboard"] {
            padding: 0.5rem !important;
        }

        .barcode-scanner-wrapper [id$="__status_line"] {
            font-size: 0.875rem !important;
            padding: 0.25rem 0.5rem !important;
        }

        /* Dark mode support */
        .dark .barcode-scanner-wrapper [id$="__dashboard_section_csr"] select {
            background-color: #111827 !important;
            color: #f3f4f6 !important;
            border-color: #374151 !important;
        }

        .dark .barcode-scanner-wrapper [id$="__dashboard_section_csr"] select:focus {
            border-color: #f97316 !important;
            box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.25) !important;
        }

        .dark .barcode-scanner-wrapper [id$="__dashboard_section"] {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
        }

        .dark .barcode-scanner-wrapper [id$="__dashboard"] {
            background-color: #111827 !important;
        }

        .dark .barcode-scanner-wrapper [id$="__status_line"] {
            color: #d1d5db !important;
        }

        .dark .barcode-scanner-wrapper img[alt="Info icon"] {
            filter: invert(1) !important;
        }

        /* Fix dark mode swap link: transparent background keeps text readable */
        .dark .barcode-scanner-wrapper [id$="__dashboard_section_swaplink"] {
            background-color: transparent !important;
            color: #f97316 !important;
            text-decoration: underline !important;
            box-shadow: none !important;
            padding: 0 !important;
        }
    </style>
    @endonce
</div>
