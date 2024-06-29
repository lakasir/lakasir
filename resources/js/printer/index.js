class Printer {
  constructor() {
    this.commands = '';
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
    this.addCommand(styles[style]);
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
    const row = data.join(' ') + '\n';
    this.addCommand(row);
    return this;
  }

  tableCustom(data) {
    data.forEach(cell => {
      let style = cell.style === 'B' ? '\x1b\x45\x01' : '\x1b\x45\x00';
      let align = {
        'LEFT': '\x1b\x61\x00',
        'CENTER': '\x1b\x61\x01',
        'RIGHT': '\x1b\x61\x02'
      }[cell.align];

      this.addCommand(align + style + cell.text);
    });
    this.addCommand('\n');
    return this;
  }

  getCommands() {
    return this.commands;
  }

  clearCommands() {
    this.commands = '';
  }
}

export default Printer;

