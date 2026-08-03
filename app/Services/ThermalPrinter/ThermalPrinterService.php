<?php

namespace App\Services\ThermalPrinter;

use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Log;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class ThermalPrinterService
{
    protected $printerWidth = 32; // 32 = 58mm <> 48 = 80mm 

    public function print(Order $order)
    {
        try {
            $connector = new WindowsPrintConnector("Eppos 58mm");
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->text("PHARMACY STORE");
            $printer->feed();
            $printer->text("Jalan Benedictiodev 123");
            $printer->feed();
            $printer->initialize();
            $printer->text($this->separator());
            $printer->text("TRX: {$order->id_order}");
            $printer->feed();
            $printer->text("DATE: {$order->date_time}");
            $printer->feed();
            $printer->text("CASHIER: {$order->user->name}");
            $printer->feed();
            $printer->text($this->separator());

            foreach ($order->products as $key => $value) {
                $printer->text($value->product->name);
                $printer->feed();

                $p = number_format($value->price, 0, ',', '.');
                $q = $value->quantity . " " . $value->uom->name . "@$p";
                $t = number_format($value->total_price, 0, ',', '.');

                $detailOrder = $this->columnify($q, $t);
                $printer->text($detailOrder);

                if ($value->discount != 0) {
                    $d = number_format($value->total_discount, 0, ',', '.');
                    $a = number_format($value->amount, 0, ',', '.');
                    $printer->text($this->columnify("Disc. ({$value->discount}%) $d", $a));
                }
            }

            $total_price_item = "Rp. " . number_format($order->total_price_item, 0, ',', '.');
            $discount = "({$order->discount}%)" . " Rp. " . number_format($order->total_discount, 0, ',', '.');
            $grand_total = "Rp. " . number_format($order->total_payment, 0, ',', '.');
            $payment = "Rp. " . number_format($order->payment, 0, ',', '.');
            $change = "Rp. " . number_format($order->change, 0, ',', '.');

            $printer->text($this->separator());
            $printer->text($this->columnify("Payment Method:", $order->payment_method));
            $printer->text($this->columnify("Total:", $total_price_item));
            $printer->text($this->columnify("Discount:", $discount));
            $printer->text($this->columnify("Grand Total:", $grand_total));
            $printer->text($this->columnify("Payment:", $payment));
            $printer->text($this->columnify("Change:", $change));
            $printer->text($this->separator());

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("TERIMA KASIH");
            $printer->feed();
            $printer->text("Support by Cresca");
            $printer->feed();
            $printer->cut();

            $printer->close();
        } catch (Exception $e) {
            Log::error("Couldn't print to this printer: " . $e->getMessage() . "\n");
            throw $e;
        }
    }

    /**
     * Helper untuk membuat teks rata kiri dan rata kanan (Contoh: "Total        15.000")
     */
    private function columnify(string $leftCol, string $rightCol)
    {
        $leftLen = strlen($leftCol);
        $rightLen = strlen($rightCol);

        // Jika teks terlalu panjang, potong agar tidak merusak layout
        if ($leftLen + $rightLen >= $this->printerWidth) {
            $leftCol = substr($leftCol, 0, $this->printerWidth - $rightLen - 1);
            $leftLen = strlen($leftCol);
        }

        $spaces = str_repeat(' ', $this->printerWidth - $leftLen - $rightLen);
        return $leftCol . $spaces . $rightCol . "\n";
    }

    public function separator(string $separator = "=")
    {
        return str_repeat($separator, $this->printerWidth) . "\n";
    }
}
