<?php

namespace App\Services\Tenants;

use App\Models\Tenants\About;
use App\Models\Tenants\Printer;
use App\Models\Tenants\Profile;
use App\Models\Tenants\Selling;
use Illuminate\Support\Facades\Http;

class PrinterService
{
    private int $paperWidth;
    private array $lines = [];
    private ?Printer $printer;

    public function __construct(?Printer $printer = null)
    {
        $this->printer = $printer ?? Printer::first();
        $this->paperWidth = 32;
    }

    public function buildReceiptFromSelling(Selling $selling): self
    {
        $this->lines = [];

        $f = $this->createFormatter();
        $separator = $f['repeat']("=");
        $dashedLine = $f['repeat']("-");

        $this->addHeader($f, $separator);

        $about = About::first();

        if ($about) {
            $this->addLine($f['center']($about->shop_name));
            $this->addLine($separator);
            $this->addLine($f['center']($about->shop_location));
        } else {
            $this->addLine($f['center']('Receipt'));
            $this->addLine($separator);
        }

        $date = now()->parse($selling->date)
            ->setTimezone(Profile::get()->timezone ?? 'UTC')
            ->format('d F Y H:i');

        $this->addLine($f['table2']("Date:", $date));
        $this->addLine($f['table2']("Cashier:", $selling->user->name));

        if ($selling->table) {
            $this->addLine($f['table2']("Table:", $selling->table->number));
        }

        $this->addLine($f['table2']("Payment:", $selling->paymentMethod->name));

        if ($selling->member) {
            $this->addLine($f['table2']("Member:", $selling->member->name));
        }

        $this->addLine("");
        $this->addLine($f['center']("ITEMS"));
        $this->addLine($dashedLine);
        $this->addLine($f['table3']("QTY", "ITEM", "PRICE"));

        foreach ($selling->sellingDetails as $detail) {
            $total = $detail->price - ($detail->discount_price ?? 0);
            $this->addLine($f['table3'](
                $detail->qty,
                $detail->product->name,
                price_format($total)
            ));

            if ($detail->discount_price > 0) {
                $this->addLine($f['table2'](
                    "  Discount:",
                    "(" . price_format($detail->discount_price) . ")"
                ));
            }
        }

        $this->addLine($dashedLine);
        $this->addLine($f['table2']("Subtotal:", price_format($selling->total_price)));

        if ($selling->total_discount_per_item + $selling->discount_price > 0) {
            $this->addLine($f['table2'](
                "Discount:",
                "(" . price_format($selling->total_discount_per_item + $selling->discount_price) . ")"
            ));
        }

        if (feature(\App\Features\SellingTax::class)) {
            $this->addLine($f['table2'](
                "Tax ({$selling->tax}%):",
                price_format($selling->tax_price)
            ));
        }

        $this->addLine($dashedLine);
        $this->addLine($f['table2']("TOTAL:", price_format($selling->grand_total_price)));
        $this->addLine($separator);
        $this->addLine($f['table2']("Payed:", price_format($selling->payed_money)));
        $this->addLine($f['table2']("Change:", price_format($selling->money_changes)));
        $this->addLine($separator);
        
        $this->addFooter($f, $separator);

        return $this;
    }

    public function print(): array
    {
        if (!$this->printer) {
            throw new \Exception('No printer configured. Please configure a printer first.');
        }

        $separator = $this->createFormatter()['repeat']("=");

        $receiptData = [
            'text' => $separator,
            'items' => $this->lines
        ];

        $apiUrl = 'http://localhost:5463/print';

        if ($this->printer->ip_address) {
            $apiUrl = "http://{$this->printer->ip_address}";
            if ($this->printer->port) {
                $apiUrl .= ":{$this->printer->port}";
            }
            $apiUrl .= "/print";
        }

        $response = Http::timeout(10)
            ->post($apiUrl, $receiptData);

        if (!$response->successful()) {
            throw new \Exception('Failed to print. Please check if the printer service is running.');
        }

        $this->lines = [];

        return $response->json();
    }

    public function addLine(string $line): self
    {
        $this->lines[] = $line;
        return $this;
    }

    public function addLines(array $lines): self
    {
        $this->lines = array_merge($this->lines, $lines);
        return $this;
    }

    private function addHeader(array $formatter, string $separator): void
    {
        if ($this->printer && $this->printer->logo) {
            $this->addLine($formatter['center']("[LOGO]"));
            $this->addLine($formatter['center']($this->printer->logo));
            $this->addLine($separator);
        }
    }

    private function addFooter(array $formatter, string $separator): void
    {
        $defaultFooter = "Thank you for visiting!";
        $footerText = $this->printer?->footer_text ?: $defaultFooter;
        
        $lines = explode("\n", $footerText);
        foreach ($lines as $line) {
            $this->addLine($formatter['center'](trim($line)));
        }
        $this->addLine($separator);
    }

    public function buildTestReceipt(string $printerName = 'Thermal Printer'): self
    {
        $this->lines = [];

        $f = $this->createFormatter();
        $separator = $f['repeat']("=");
        $dashedLine = $f['repeat']("-");

        $this->addHeader($f, $separator);

        $about = About::first();

        $this->addLine($f['center']("TEST RECEIPT"));
        $this->addLine($separator);

        if ($about) {
            $this->addLine($f['center']($about->shop_name));
        } else {
            $this->addLine($f['center']($printerName));
        }

        $this->addLine($separator);
        $this->addLine($f['table2']("Date:", now()->format('d M Y H:i')));
        $this->addLine($f['table2']("Test ID:", strtoupper(substr(md5((string) time()), 0, 8))));
        $this->addLine("");
        $this->addLine($f['center']("SAMPLE ITEMS"));
        $this->addLine($dashedLine);
        $this->addLine($f['table3']("QTY", "ITEM", "PRICE"));
        $this->addLine($f['table3']("1", "Coffee", "$3.50"));
        $this->addLine($f['table3']("1", "Sandwich", "$6.50"));
        $this->addLine($f['table3']("1", "Orange Juice", "$4.00"));
        $this->addLine($dashedLine);
        $this->addLine($f['table2']("Subtotal:", "$14.00"));
        $this->addLine($f['table2']("Tax (10%):", "$1.40"));
        $this->addLine($separator);
        $this->addLine($f['table2']("TOTAL:", "$15.40"));
        $this->addLine($separator);
        $this->addLine($f['center']("Test Print Successful!"));
        $this->addLine($f['center']("Configuration verified"));
        
        $this->addFooter($f, $separator);

        return $this;
    }

    public function printWithCustomPrinter(array $printerConfig): array
    {
        $separator = $this->createFormatter()['repeat']("=");

        $receiptData = [
            'text' => $separator,
            'items' => $this->lines
        ];

        $apiUrl = "http://{$printerConfig['ip_address']}";
        if (!empty($printerConfig['port'])) {
            $apiUrl .= ":{$printerConfig['port']}";
        }
        $apiUrl .= "/print";

        $response = Http::timeout(10)
            ->post($apiUrl, $receiptData);

        if (!$response->successful()) {
            throw new \Exception('Failed to print. Please check if the printer service is running.');
        }

        $this->lines = [];

        return $response->json();
    }

    private function createFormatter(): array
    {
        $paperWidth = $this->paperWidth;

        $repeat = function(string $char) use ($paperWidth): string {
            return str_repeat($char, $paperWidth);
        };

        $center = function(string $text) use ($paperWidth): string {
            $len = strlen($text);
            if ($len >= $paperWidth) {
                return substr($text, 0, $paperWidth);
            }
            $spaces = (int) floor(($paperWidth - $len) / 2);
            return str_repeat(" ", $spaces) . $text;
        };

        $table2 = function(string $left, string $right) use ($paperWidth): string {
            $leftText = substr($left, 0, $paperWidth);
            $spaceCount = $paperWidth - strlen($leftText) - strlen($right);
            $spaces = $spaceCount > 0 ? str_repeat(" ", $spaceCount) : "";
            return "{$leftText}{$spaces}{$right}";
        };

        $table3 = function($col1, $col2, $col3, array $widths = [6, 18, 8]) use ($paperWidth): string {
            $totalWidth = array_sum($widths);
            if ($totalWidth !== $paperWidth) {
                throw new \Exception("Sum of column widths ({$totalWidth}) must equal paperWidth ({$paperWidth})");
            }

            $c1 = str_pad(substr((string) $col1, 0, $widths[0]), $widths[0]);
            $c2 = str_pad(substr((string) $col2, 0, $widths[1]), $widths[1]);
            $c3 = str_pad(substr((string) $col3, 0, $widths[2]), $widths[2], " ", STR_PAD_LEFT);

            return "{$c1}{$c2}{$c3}";
        };

        return [
            'repeat' => $repeat,
            'center' => $center,
            'table2' => $table2,
            'table3' => $table3,
        ];
    }
}
