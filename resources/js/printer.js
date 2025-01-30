class Printer {
  constructor(printerId) {
    console.log(this.commands);
    this.commands = '';
    this.lineWidth = 32;
    this.printerId = printerId;
  }

  async print() {
    const commands = this.getCommands();
    console.log(commands)
    try {
      const devices = await navigator.usb.getDevices();
      const device = devices.find(device => device.vendorId === this.printerId);
      if (device) {
        await device.open();
        await device.selectConfiguration(1);
        await device.claimInterface(0);

        const encoder = new TextEncoder();
        const data = encoder.encode(commands);
        const endpoint = device.configuration.interfaces[0].alternate.endpoints.filter(endpoint => endpoint.direction === 'out')[0]
        await device.transferOut(endpoint.endpointNumber, data);
        await device.close();
        console.log('Data sent to printer');
        this.clearCommands();
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

  addCommand(command) {
    this.commands += command;
  }

  font(font) {
    const fonts = {
      'a': '\x1b\x4d\x00', // Font A
      'b': '\x1b\x4d\x01'  // Font B
    };
    this.addCommand(fonts[font]);
    return this;
  }

  align(align) {
    const alignments = {
      'left': '\x1b\x61\x00',
      'center': '\x1b\x61\x01',
      'right': '\x1b\x61\x02'
    };
    this.addCommand(alignments[align]);
    return this;
  }

  style(style) {
    const styles = {
      'bold': '\x1b\x45\x01',
      'underline': '\x1b\x2d\x01',
      'normal': '\x1b\x45\x00' + '\x1b\x2d\x00'
    };
    this.addCommand(styles[style ?? 'normal']);
    return this;
  }

  size(width, height) {
    this.addCommand('\x1d\x21' + String.fromCharCode((width << 4) | height));
    return this;
  }

  text(text) {
    this.addCommand(text + '\n');
    return this;
  }

  barcode(code, type) {
    const types = {
      'EAN8': '\x1d\x6b\x02'
    };
    this.addCommand(types[type] + code + '\x00');
    return this;
  }

  table(data) {
    let row = '';
    const totalTextLength = data.reduce((sum, text) => sum + text.length, 0);

    const totalPadding = this.lineWidth - totalTextLength;
    data.forEach((text, index) => {
      row += text;
      if (index < data.length) {
        for (let i = 0; i < totalPadding; i++) {
          row += ' ';
        }
      }
    });

    this.addCommand(row.trim() + '\x0A');
    return this;
  }

  tableCustom(data) {
    const totalTextLength = data.reduce((sum, text) => sum + text.text.length, 0);

    const totalPadding = this.lineWidth - totalTextLength;
    let row = '';

    data.forEach((cell, index) => {
      let style = cell.style === 'B' ? '\x1b\x45\x01' : '\x1b\x45\x00';

      row += style + cell.text;
      if (index < data.length) {
        for (let i = 0; i < totalPadding; i++) {
          row += ' ';
        }
      }
    });

    this.addCommand(row.trim() + '\x0A');
    return this;
  }

  newLine(line = 1) {
    for (let i = 0; i < line; i++) {
      this.text('\n');
    }

    return this;
  }

  cut() {
    this.addCommand('\n\n' + '\x1d\x56\x00');
    return this;
  }

  getCommands() {
    return this.commands;
  }

  clearCommands() {
    this.commands = '';
  }
}

