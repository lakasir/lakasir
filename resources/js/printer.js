class Printer {
  constructor(apiUrl = 'http://localhost:5463/print') {
    this.apiUrl = apiUrl;
    this.text_content = '';
    this.items = [];
  }

  async print() {
    try {
      const payload = {
        text: this.text_content,
        items: this.items
      };

      const response = await fetch(this.apiUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload)
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const result = await response.json();
      console.log('Print successful:', result);
      this.clearCommands();
      return result;
    } catch (e) {
      console.error('Print error:', e);
      new FilamentNotification()
        .title('Failed to print. Please check if the printer service is running.')
        .danger()
        .send();
      throw e;
    }
  }

  font(font) {
    return this;
  }

  align(align) {
    return this;
  }

  style(style) {
    return this;
  }

  size(width, height) {
    return this;
  }

  text(text) {
    if (this.text_content === '') {
      this.text_content = text;
    }
    return this;
  }

  barcode(code, type) {
    return this;
  }

  table(data) {
    const row = data.join(' - ');
    this.items.push(row);
    return this;
  }

  tableCustom(data) {
    const row = data.map(cell => cell.text).join(' - ');
    this.items.push(row);
    return this;
  }

  newLine(line = 1) {
    return this;
  }

  cut() {
    return this;
  }

  getCommands() {
    return {
      text: this.text_content,
      items: this.items
    };
  }

  clearCommands() {
    this.text_content = '';
    this.items = [];
  }
}

