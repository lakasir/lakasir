let selectedDevice = null;

/**
 * Retrieve printer settings from localStorage.
 * 
 * @returns {Object|Error} The printer settings object or an Error if not set.
 */
function getPrinter() {
  if (localStorage.printer == undefined) {
    console.error('printer didn\'t set');
    return Error('printer didn\'t set');
  }

  return JSON.parse(localStorage.printer);
}

/**
 * Print text to a USB ESC/POS printer.
 * 
 * @param {string} text - The ESC/POS command string to print.
 * @returns {Promise<void>}
 */
async function printToUSBPrinter(text) {
  let receiptText = text;
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
      const endpoint = device.configuration.interfaces[0].alternate.endpoints.filter(endpoint => endpoint.direction === 'out')[0]
      await device.transferOut(endpoint.endpointNumber, data);

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
  } catch (e) {
    console.error(e);
  }
}

/**
 * Pad text for receipt printing.
 * 
 * @param {string} text - The text to pad.
 * @param {number} length - Target length.
 * @param {boolean} alignRight - Whether to align right.
 * @param {boolean} center - Whether to center the text.
 * @param {string} textSize - Text size ('normal' or 'large').
 * @returns {string} The padded text.
 */
function padText(text, length, alignRight = false, center = false, textSize = 'normal') {
  const sizes = {
    'normal': '\x1D\x21\x00', // Normal text
    'large': '\x1D\x21\x11', // Large text
  }[textSize];
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

/**
 * Formats a number as currency.
 * For IDR, decimals are hidden for a cleaner POS look.
 * 
 * @param {number} number - The value to format.
 * @param {string|null} currency - Currency code (e.g., 'IDR', 'USD').
 * @returns {string} The formatted currency string.
 */
function moneyFormat(number, currency = null) {
  const activeCurrency = currency || window.lakasirCurrency || 'IDR';
  const activeLocale = window.lakasirLocale || 'en';

  const options = {
    style: 'currency',
    currency: activeCurrency,
  };

  if (activeCurrency === 'IDR') {
    options.minimumFractionDigits = 0;
  }

  const formatter = new Intl.NumberFormat(activeLocale, options);

  return formatter.format(number);
}

/**
 * Formats a number using the active locale.
 * 
 * @param {number} number - The value to format.
 * @returns {string} The formatted number string.
 */
function numberFormat(number) {
  const activeLocale = window.lakasirLocale || 'en';
  const formatter = new Intl.NumberFormat(activeLocale);

  return formatter.format(number);
}

