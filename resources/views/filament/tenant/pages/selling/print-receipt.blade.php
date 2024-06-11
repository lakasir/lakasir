<!DOCTYPE html>
<html>
<body>
  <div id="body-print">
    <button id="usbButton">Print</button>
  </div>
<script>
  let selectedDevice = null; // Variable to store the selected USB device

  async function selectUSBPrinter() {
    try {
      selectedDevice = await navigator.usb.requestDevice({ filters: [{ vendorId: 0x6868 }] });
      await selectedDevice.open();
      await selectedDevice.selectConfiguration(1);
      await selectedDevice.claimInterface(0);

      console.log('USB printer selected:', selectedDevice.productName);
    } catch (error) {
      console.error(error);
    }
  }

  async function printToUSBPrinter() {
    try {
      if (!selectedDevice) {
        console.error('No USB printer selected');
        return;
      }

      const encoder = new TextEncoder();
      const data = encoder.encode("Hello, thermal printer!\n");
      await selectedDevice.transferOut(1, data);

      console.log('Data sent to printer');
    } catch (error) {
      console.error(error);
    }
  }
  document.getElementById('usbButton').addEventListener('click', async () => {
    try {
      if (!selectedDevice) {
      selectUSBPrinter();
      } else {
        printToUSBPrinter();
      }
    } catch (error) {
      console.error(error);
    }
  });

</script>
</body>
</html>

