class Printer {
  constructor(apiUrl = 'http://localhost:5463', paperWidth = 32) {
    this.apiUrl = apiUrl;
    this.paperWidth = paperWidth;
    this.lines = [];
  }

  createFormatter() {
    const paperWidth = this.paperWidth;
    
    const repeat = (char) => char.repeat(paperWidth);

    const center = (text) => {
      const len = text.length;
      if (len >= paperWidth) return text.slice(0, paperWidth);
      const spaces = Math.floor((paperWidth - len) / 2);
      return " ".repeat(spaces) + text;
    };

    const table2 = (left, right) => {
      const total = paperWidth;
      const leftText = left.slice(0, total);
      const spaceCount = total - leftText.length - right.length;
      const spaces = spaceCount > 0 ? " ".repeat(spaceCount) : "";
      return `${leftText}${spaces}${right}`;
    };

    const table3 = (col1, col2, col3, widths = [6, 18, 8]) => {
      const totalWidth = widths.reduce((a, b) => a + b, 0);
      if (totalWidth !== paperWidth) {
        throw new Error(`Sum of column widths (${totalWidth}) must equal paperWidth (${paperWidth})`);
      }

      const c1 = col1.toString().padEnd(widths[0]).slice(0, widths[0]);
      const c2 = col2.toString().padEnd(widths[1]).slice(0, widths[1]);
      const c3 = col3.toString().padStart(widths[2]).slice(0, widths[2]);
      return `${c1}${c2}${c3}`;
    };

    return { repeat, center, table2, table3 };
  }

  async fetchSettings() {
    try {
      const response = await fetch(`${this.apiUrl}/settings`);
      const settings = await response.json();
      this.paperWidth = settings.paperWidth || 32;
    } catch (e) {
      console.warn('Failed to fetch settings, using default paper width:', this.paperWidth);
    }
  }

  addLine(line) {
    this.lines.push(line);
    return this;
  }

  addLines(lines) {
    this.lines.push(...lines);
    return this;
  }

  async print() {
    try {
      const separator = this.createFormatter().repeat("=");
      const receiptData = {
        text: separator,
        items: this.lines
      };

      const response = await fetch(`${this.apiUrl}/print`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(receiptData)
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

  clearCommands() {
    this.lines = [];
  }
}

