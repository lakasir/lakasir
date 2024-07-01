let selectedDevice = null;

async function printToUSBPrinter(selling, about, copy = false) {
  console.log(selling, about);
  let header = ``;
  let detail = ``;
  if (about != undefined || about != null) {
    header += `${padText(about.shop_name, 32, false, true)}\n`
    header += `${padText(about.shop_location, 32, false, true)}\n\n`
    header += `${padText('-------------------------------', 32)}\n`;
  }
  detail += `${padText('Cashier', 16)}${padText(selling.user.name ?? selling.user.email, 16, true)}\n`;
  if (selling.member != null) {
    detail += `${padText('Member', 16)}${padText(selling.member.name, 16, true)}\n`;
  }
  detail += `${padText('Payment method', 16)}${padText(selling.payment_method.name, 16, true)}\n`;
  detail += `${padText('-------------------------------', 32)}\n`;
  selling.selling_details.forEach((sellingDetail) => {
    detail += `${padText(sellingDetail.product.name, 16)}${padText(moneyFormat(sellingDetail.price / sellingDetail.qty) + ' x ' + sellingDetail.qty.toString(), 16, true)}\n`;
    let price = sellingDetail.price;
    if (sellingDetail.discount_price > 0) {
      price = price - sellingDetail.discount_price;
      detail += `${padText('Discount', 16)}${padText(moneyFormat(sellingDetail.discount_price), 16, true)}\n`;
    }
    detail += `${padText(moneyFormat(price), 32, true)}\n`;
  });
  detail += `${padText('-------------------------------', 32)}\n`;
  detail += `${padText('Subtotal', 16)}${padText(moneyFormat(selling.total_price), 16, true)}\n`;
  let totalPrice = selling.total_price;
  if (selling.discount_price > 0) {
    detail += `${padText('Discount', 16)}${padText(moneyFormat(selling.discount_price), 16, true)}\n`;
  }
  detail += `${padText('Tax', 16)}${padText(selling.tax.toString(), 16, true)}\n`;
  detail += `${padText('Total price', 16)}${padText(moneyFormat(((totalPrice * selling.tax / 100) + totalPrice) - selling.discount_price), 16, true)}\n`;
  detail += `${padText('-------------------------------', 32)}\n`;
  detail += `${padText('Payed money', 16)}${padText(moneyFormat(selling.payed_money), 16, true)}\n`;
  detail += `${padText('Change', 16)}${padText(moneyFormat(selling.money_changes), 16, true)}\n`;
  detail += `${padText('-------------------------------', 32)}\n`;
  if (copy) {
    detail += `${padText('Copy', 32)}\n`;
  }
  detail += `${padText('    ', 32)}\n`;
  detail += `${padText('    ', 32)}\n`;
  let receiptText = header + detail;
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

      await device.open();
      await device.selectConfiguration(1);
      await device.claimInterface(0);

      const encoder = new TextEncoder();
      const data = encoder.encode(receiptText);
      await device.transferOut(1, data);

      console.log('Data sent to printer');
    } else {
      console.log('No USB device with the specified vendor ID found');
      new FilamentNotification()
        .title('You should choose the printer first in printer setting')
        .danger()
        .actions([
          new FilamentNotificationAction('Setting')
            .icon('heroicon-o-cog-6-tooth')
            .button()
            .url('/member/printer'),
        ])
        .send()
    }
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

function moneyFormat(number, currency = null) {
  const formatter = new Intl.NumberFormat({
    style: 'currency',
    currency: currency,
  });

  return formatter.format(number);
}

