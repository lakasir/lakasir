<div x-data="barcode()" class="flex">
  <button
    @click="openBarcodeScanner"
    class="px-3 py-2 bg-orange-500 text-white rounded-lg flex items-center justify-center hover:bg-orange-600 transition-colors"
    :disabled="isScanning">
    <template x-if="!isScanning">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
      </svg>
    </template>
    <template x-if="isScanning">
      <div class="animate-spin h-5 w-5 border-2 border-white border-b-transparent rounded-full"></div>
    </template>
  </button>

  <div
    x-show="isScanning"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 bg-black"
    style="display: none;">

    <!-- Header -->
    <div class="absolute top-0 left-0 right-0 bg-black bg-opacity-50 z-10">
      <div class="flex items-center justify-between p-4">
        <button
          @click="closeBarcodeScanner"
          class="text-white p-2">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
        <h2 class="text-white text-lg font-medium">Scan Barcode</h2>
        <button
          @click="toggleFlash"
          class="text-white p-2">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Camera View -->
    <div class="relative h-full">
      <video
        x-ref="video"
        class="h-full w-full object-cover"
        playsinline
        autoplay></video>

      <!-- Scanning Overlay -->
      <div class="absolute inset-0 flex items-center justify-center">
        <!-- Scanner Frame -->
        <div class="relative w-64 h-64">
          <!-- Corner Markers -->
          <div class="absolute top-0 left-0 w-10 h-10 border-t-4 border-l-4 border-orange-500"></div>
          <div class="absolute top-0 right-0 w-10 h-10 border-t-4 border-r-4 border-orange-500"></div>
          <div class="absolute bottom-0 left-0 w-10 h-10 border-b-4 border-l-4 border-orange-500"></div>
          <div class="absolute bottom-0 right-0 w-10 h-10 border-b-4 border-r-4 border-orange-500"></div>

          <!-- Scanning Line Animation -->
          <div
            class="absolute left-0 right-0 h-0.5 bg-orange-500"
            style="top: 50%; animation: scan 2s linear infinite;">
          </div>
        </div>
      </div>

      <!-- Helper Text -->
      <div class="absolute bottom-20 left-0 right-0 text-center">
        <p class="text-white text-lg mb-2">Position barcode within the frame</p>
        <p class="text-gray-300 text-sm">Scanner will automatically detect the barcode</p>
      </div>
    </div>
  </div>

</div>

@script()
<script>
  Alpine.data('barcode', () => {
    return {
      stream: null,
      flashMode: 'off',
      async openBarcodeScanner() {
        if (!('BarcodeDetector' in window)) {
          alert('Barcode scanner is not supported in this browser');
          return;
        }

        try {
          this.isScanning = true;
          const constraints = {
            video: {
              facingMode: 'environment',
              width: { ideal: 1280 },
              height: { ideal: 720 }
            }
          };

          this.stream = await navigator.mediaDevices.getUserMedia(constraints);
          const video = this.$refs.video;
          video.srcObject = this.stream;

          const barcodeDetector = new BarcodeDetector();

          // Scan for barcode
          const detectBarcode = async () => {
            if (!this.isScanning) return;

            try {
              const barcodes = await barcodeDetector.detect(video);
              if (barcodes.length > 0) {
                // Vibrate device if supported
                if ('vibrate' in navigator) {
                  navigator.vibrate(200);
                }

                this.isLoading = true;

                // await $wire.scanProduct(barcodes[0].rawValue)
                this.closeBarcodeScanner();
                $store.openCartButtonModal = true;
              } else {
                requestAnimationFrame(detectBarcode);
              }
            } catch (error) {
              console.error('Barcode detection error:', error);
              requestAnimationFrame(detectBarcode);
            } finally {
              this.isLoading = false;
            }
          };

          requestAnimationFrame(detectBarcode);
        } catch (error) {
          console.error('Error accessing camera:', error);
          this.isScanning = false;
          alert('Unable to access camera. Please check permissions.');
        }
      },

      async toggleFlash() {
        if (!this.stream) return;

        const track = this.stream.getVideoTracks()[0];
        const capabilities = track.getCapabilities();

        if (!capabilities.torch) {
          alert('Flash is not supported on this device');
          return;
        }

        try {
          this.flashMode = this.flashMode === 'off' ? 'on' : 'off';
          await track.applyConstraints({
            advanced: [{ torch: this.flashMode === 'on' }]
          });
        } catch (error) {
          console.error('Error toggling flash:', error);
          alert('Unable to toggle flash');
        }
      },

      closeBarcodeScanner() {
        if (this.stream) {
          this.stream.getTracks().forEach(track => track.stop());
          this.stream = null;
        }
        this.isScanning = false;
        this.flashMode = 'off';
      },
    }
  })
</script>
@endscript
