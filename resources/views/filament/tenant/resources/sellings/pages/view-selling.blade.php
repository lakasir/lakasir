<x-filament-panels::page>
    @if ($this->hasInfolist())
        {{ $this->infolist }}
    @else
        {{ $this->form }}
    @endif

    @if (count($relationManagers = $this->getRelationManagers()))
        <x-filament-panels::resources.relation-managers
            :active-manager="$this->activeRelationManager"
            :managers="$relationManagers"
            :owner-record="$record"
            :page-class="static::class"
        />
    @endif
</x-filament-panels::page>
@script
<script>
  let selectedDevice = null; // Variable to store the selected USB device

  let selling = @js($record);
  let about = @js($about);

  async function selectUSBPrinter() {
    try {
      selectedDevice = await navigator.usb.requestDevice({ filters: [] });
      await selectedDevice.open();
      await selectedDevice.selectConfiguration(1);
      await selectedDevice.claimInterface(0);

      localStorage.setItem("printer", JSON.stringify({
        name: selectedDevice.productName,
        vendorId: selectedDevice.vendorId
      }))
      console.log('USB printer selected:', selectedDevice.productName);
    } catch (error) {
      console.error(error);
    }
  }

  function padText(text, length, alignRight = false, center = false, textSize = 'normal') {
    const sizes = {
      'normal': '\x1D\x21\x00', // Normal text
      'large': '\x1D\x21\x11', // Large text
    };
    let paddedText = text;

    if (center) {
      const padLength = Math.max(0, length - text.length);
      const padStart = Math.floor(padLength / 2);
      const padEnd = Math.ceil(padLength / 2);
      paddedText = ' '.repeat(padStart) + text + ' '.repeat(padEnd);
    } else if (alignRight) {
      paddedText = text.padStart(length);
    } else {
      paddedText = text.padEnd(length);
    }

    return paddedText;
  }

  function moneyFormat(number) {
    const formatter = new Intl.NumberFormat({
      style: 'currency',
    });

    return formatter.format(number);
  }

  async function printToUSBPrinter() {
    let header = ``;
    let detail = ``;
    if (about) {
      header += `${padText(about.shop_name, 32, false, true)}\n`
      header += `${padText(about.shop_location, 32, false, true)}\n\n`
      header += `${padText('-------------------------------', 32)}\n`;
    }
    detail += `${padText('@lang('Cashier')', 16)}${padText(selling.user.name ?? selling.user.email, 16, true)}\n`;
    if(selling.member != null) {
      detail += `${padText('@lang('Member')', 16)}${padText(selling.member.name, 16, true)}\n`;
    }
    detail += `${padText('@lang('Payment method')', 16)}${padText(selling.payment_method.name, 16, true)}\n`;
    detail += `${padText('-------------------------------', 32)}\n`;
    selling.selling_details.forEach((sellingDetail) => {
      detail += `${padText(sellingDetail.product.name, 16)}${padText(moneyFormat(sellingDetail.price / sellingDetail.qty) + ' x ' + sellingDetail.qty.toString(), 16, true)}\n`;
      let price = sellingDetail.price;
      if (sellingDetail.discount_price > 0) {
        price = price - sellingDetail.discount_price;
        detail += `${padText('@lang('Discount')', 16)}${padText(moneyFormat(sellingDetail.discount_price), 16, true)}\n`;
      }
      detail += `${padText(moneyFormat(price), 32, true)}\n`;
    });
    detail += `${padText('-------------------------------', 32)}\n`;
    detail += `${padText('@lang('Subtotal')', 16)}${padText(moneyFormat(selling.total_price), 16, true)}\n`;
    let totalPrice = selling.total_price;
    if (selling.discount_price > 0) {
      detail += `${padText('@lang('Discount')', 16)}${padText(moneyFormat(selling.discount_price), 16, true)}\n`;
    }
    detail += `${padText('@lang('Tax')', 16)}${padText(selling.tax.toString(), 16, true)}\n`;
    detail += `${padText('@lang('Total price')', 16)}${padText(moneyFormat((selling.total_price * selling.tax / 100) + selling.total_price), 16, true)}\n`;
    detail += `${padText('-------------------------------', 32)}\n`;
    detail += `${padText('@lang('Payed money')', 16)}${padText(moneyFormat(selling.payed_money), 16, true)}\n`;
    detail += `${padText('@lang('Change')', 16)}${padText(moneyFormat(selling.money_changes), 16, true)}\n`;
    detail += `${padText('-------------------------------', 32)}\n`;
    detail += `${padText('@lang('Copy')', 32)}\n`;
    detail += `${padText('    ', 32)}\n`;
    detail += `${padText('    ', 32)}\n`;
    let receiptText = header+detail;
    console.log(receiptText);

    try {
      if (localStorage.printer == undefined) {
        console.error('No USB printer selected');
        return;
      }

      let printer = JSON.parse(localStorage.printer);
      const devices = await navigator.usb.getDevices();

      const device = devices.find(device => device.vendorId === printer.vendorId);
      if (device) {
        console.log('Found USB device:', device.productName);

        /* await device.open(); */
        /* await device.selectConfiguration(1); */
        /* await device.claimInterface(0); */
        /**/
        /* const encoder = new TextEncoder(); */
        /* const data = encoder.encode(receiptText); */
        /* await device.transferOut(1, data); */

        console.log('Data sent to printer');
      } else {
        console.log('No USB device with the specified vendor ID found');
      }
    } catch (error) {
      console.error(error);
    }
  }

  document.getElementById('usbButton').addEventListener('click', async () => {
    try {
      if (localStorage.printer == undefined) {
        selectUSBPrinter();
      } else {
        printToUSBPrinter();
      }
    } catch (error) {
      console.error(error);
    }
  });
</script>
@endscript
