let selectedDevice = null;

function getPrinter() {
  return {
    apiUrl: localStorage.printerApiUrl || 'http://localhost:8888/print'
  };
}

function padText(text, length, alignRight = false, center = false, textSize = 'normal') {
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

