<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            @page {
                size: auto;
                margin: 1mm 2mm 0 0;
            }
        }
    </style>
</head>

{{-- <pre>
    {{ json($order, JSON_PRETTY_PRINT) }}
</pre> --}}

<body class="box-border text-sm/4 w-[58mm] mx-auto font-mono">
    <p class="text-base font-semibold text-center">PHARMACY CRESCA</p>
    <p class="text-center">Jalan Cresca By Benedictiodev No 22 RT 003 RW 006 Desa Bojongsari, Kec.
        Bojongsoang, Kab. Bandung 40375</p>

    <hr class="border-t border-black border-dashed my-1.5" />

    <table class="table table-auto border-collapse w-full">
        <tbody>
            <tr>
                <td class="text-left">Faktur</td>
                <td class="px-2">:</td>
                <td>{{ $order->id_order }}</td>
            </tr>
            <tr>
                <td class="text-left">Tgl</td>
                <td class="px-2">:</td>
                <td>{{ $order->created_at }}</td>
            </tr>
            <tr>
                <td class="text-left">Pemb.</td>
                <td class="px-2">:</td>
                <td>{{ $order->payment_method }}</td>
            </tr>
        </tbody>
    </table>

    <hr class="border-t border-black border-dashed my-1.5" />

    {{-- <table class="table border-collapse w-full">
        <thead>
            <tr>
                <td class="text-left">Nama</td>
                <td class="text-right">Qty</td>
                <td class="text-right px-1">Harga</td>
                <td class="text-right">Total</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orders as $item)
                <tr class="border-t border-black border-dashed py-1.5 last:border-0">
                    <td>{{ $item->product->name }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right px-1">{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->total_price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table> --}}

    @foreach ($order->orders as $item)
        <p>{{ $item->product->name }}</p>
        <table class="table table-auto border-collapse w-full">
            <tbody>
                <tr>
                    <td class="text-left">{{ $item->quantity }} {{ $item->uom }}</td>
                    <td class="text-right">{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right pe-2">{{ number_format($item->total_price, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    @endforeach

    <hr class="border-t border-black border-dashed my-1.5" />

    <table class="table table-auto border-collapse ml-auto me-2">
        <tbody>
            <tr>
                <td class="text-left">Total</td>
                <td class="px-2">:</td>
                <td class="text-right">Rp {{ number_format($order->total_price_item, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-left">Discount</td>
                <td class="px-2">:</td>
                <td class="text-right">({{ $order->discount }}%) Rp
                    {{ number_format($order->total_discount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-left">Grand Total</td>
                <td class="px-2">:</td>
                <td class="text-right">Rp {{ number_format($order->total_payment, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-left">Tunai</td>
                <td class="px-2">:</td>
                <td class="text-right">Rp {{ number_format($order->payment, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-left">Kembali</td>
                <td class="px-2">:</td>
                <td class="text-right">Rp {{ number_format($order->change, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <hr class="border-t border-black border-dashed my-1.5" />

    <p class="font-semibold text-center">TERIMA KASIH</p>
    <p class="text-xs text-center">Telah Belanja Di Pharmacy Cresca</p>
    <p class="text-xs text-center">Barang yang sudah dibeli tidak dapat di kembalikan/tukar.</p>
    <p class="text-center mt-2">Support by Cresca</p>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
