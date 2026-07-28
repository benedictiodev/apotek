<?php

namespace App\Services\ThermalPrinter;

use Exception;
use Illuminate\Support\Facades\Log;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class ThermalPrinterService
{

    public function print()
    {
        $connector = new WindowsPrintConnector("Eppos 58mm");

        try {
            $printer = new Printer($connector);
            $printer->text("Hello World!");
            $printer->feed(2);
            $printer->cut();

            $printer->close();
        } catch (Exception $e) {
            Log::error("Couldn't print to this printer: " . $e->getMessage() . "\n");
            throw $e;
        }
    }
}
