@extends('layouts.index')

@section('main')
    <div>
        <!-- Header Card -->
        <div class="bg-white p-5 rounded-lg shadow-sm mb-6 flex justify-between items-center border border-gray-200">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $opname->title }}</h2>
                <p class="text-sm text-gray-500 mt-1">Status: <span
                        class="font-semibold text-blue-600">{{ $opname->status }}</span> | Petugas:
                    {{ $opname->user->name ?? '-' }}</p>
                @if ($opname->note)
                    <p class="text-sm text-gray-500 mt-1">Catatan: {{ $opname->note }}</p>
                @endif
            </div>

            <!-- Tombol Aksi Hanya Muncul Jika Editable -->
            @if($isEditable)
                <div class="flex gap-2">
                    <button id="btnSaveDraft" class="btn-draft px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm font-medium">Save Draft</button>
                    <button id="btnComplete" class="btn-complete px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 text-sm font-medium">Complete & Sesuaikan Stok</button>
                </div>
            @else
                <a href="{{ route('dashboard.stock-opname.index') }}"
                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm font-medium">Kembali</a>
            @endif
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <div class="text-sm text-gray-500 font-medium">Total Produk Diopname</div>
                <div class="mt-1 text-2xl font-bold text-gray-900" id="summaryTotalProducts">0</div>
            </div>
            <!-- Mismatch card is clickable -->
            <div id="cardMismatched" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer hover:bg-red-50 transition duration-150 border-l-4 border-l-red-500">
                <div class="text-sm text-gray-500 font-medium flex justify-between items-center">
                    Total Selisih (Mismatched)
                    <span class="text-xs text-red-600 font-semibold bg-red-100 px-2 py-0.5 rounded">Lihat Detail</span>
                </div>
                <div class="mt-1 text-2xl font-bold text-red-600" id="summaryTotalMismatched">0</div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <div class="text-sm text-gray-500 font-medium">Total Qty Selisih</div>
                <div class="mt-1 text-2xl font-bold text-gray-900" id="summaryTotalQtyMismatched">0</div>
            </div>
        </div>

        <!-- Scanner (Hanya muncul jika Editable) -->
        @if($isEditable)
            <div class="mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <label class="block mb-2 text-sm font-bold text-gray-900">🔍 Scan Barcode / Cari Produk</label>
                <input type="text" id="scannerInput"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full md:w-1/2 p-3"
                    placeholder="Scan barcode lalu tekan Enter..." autofocus>
                <p id="errorMessage" class="mt-2 text-sm text-red-600 font-medium" style="display:none;"></p>
            </div>
        @endif

        <!-- Main Table (DataTables) -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <table id="mainTable" class="w-full text-sm text-left text-gray-600 display">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th class="px-4 py-3">Produk</th>
                        <th class="px-4 py-3">Batch</th>
                        <th class="px-4 py-3 text-center">Stok Sistem</th>
                        <th class="px-4 py-3 text-center">Stok Fisik</th>
                        <th class="px-4 py-3 text-center">Selisih</th>
                        <th class="px-4 py-3">Alasan</th>
                        <th class="px-4 py-3">Catatan</th>
                        @if($isEditable)
                            <th class="px-4 py-3">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <!-- Populated by DataTables -->
                </tbody>
            </table>
        </div>

        <!-- Modal Multi-Batch -->
        <div id="batchModal" style="display: none"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900 bg-opacity-50">
            <div class="relative bg-white rounded-lg shadow w-full max-w-md p-5">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Pilih Batch: <span id="modalProductName"></span>
                </h3>
                <div class="space-y-2 max-h-64 overflow-y-auto" id="batchListContainer">
                    <!-- Populated via JS -->
                </div>
                <button id="btnCloseBatchModal"
                    class="mt-4 w-full bg-gray-200 text-gray-800 py-2 rounded-lg font-medium hover:bg-gray-300">Batal</button>
            </div>
        </div>

        <!-- Modal Mismatched Products -->
        <div id="mismatchModal" style="display: none"
            class="fixed inset-0 z-[110] flex items-center justify-center bg-gray-900 bg-opacity-50">
            <div class="relative bg-white rounded-lg shadow w-full max-w-4xl p-5">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex justify-between items-center">
                    Daftar Produk Selisih (Mismatched)
                    <button id="btnCloseMismatchModal" class="text-gray-400 hover:text-gray-900 text-2xl leading-none">&times;</button>
                </h3>
                <div class="overflow-x-auto">
                    <table id="mismatchTable" class="w-full text-sm text-left text-gray-600 display">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                            <tr>
                                <th class="px-4 py-3">Produk</th>
                                <th class="px-4 py-3">Batch</th>
                                <th class="px-4 py-3 text-center">Sistem</th>
                                <th class="px-4 py-3 text-center">Fisik</th>
                                <th class="px-4 py-3 text-center">Selisih</th>
                                <th class="px-4 py-3">Alasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Populated by DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@php
    // Mapping data di luar directive @json agar Blade parser tidak error
    $initialItems = $opname->details
        ->map(function ($d) {
            return [
                'product_id' => $d->product_id,
                'product_name' => $d->product->name ?? 'Produk Terhapus',
                'product_stock_id' => $d->product_stock_id,
                'batch_number' => $d->productStock->batch ?? '-',
                'system_stock' => $d->system_stock,
                'physical_stock' => $d->physical_stock,
                'discrepancy' => $d->discrepancy,
                'reason' => $d->reason ?? '',
                'note' => $d->note ?? '',
            ];
        })
        ->values()
        ->toArray();
@endphp

@push('script')
    <!-- DataTables scripts are already included in layouts/index.blade.php -->

    <style>
        /* Customizing DataTable to match Tailwind slightly */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.25rem 0.5rem;
        }
        table.dataTable tbody tr:hover {
            background-color: #f9fafb !important;
        }
    </style>

    <script>
        let items = @json($initialItems);
        let isEditable = @json($isEditable);
        let opnameId = {{ $opname->id }};
        let scannedProduct = null;
        
        let mainTable;
        let mismatchTable;

        $(document).ready(function() {
            initMainTable();
            initMismatchTable();
            updateSummaryCards();

            // ================= SCANNER LOGIC =================
            $('#scannerInput').on('keypress', function(e) {
                if(e.which == 13) {
                    e.preventDefault();
                    let term = $(this).val().trim();
                    if(term !== '') {
                        handleScan(term);
                    }
                }
            });

            function handleScan(term) {
                if (!isEditable) return;
                
                $('#errorMessage').hide();
                $('#scannerInput').val('');
                
                $.ajax({
                    url: '{{ route('dashboard.stock-opname.scan') }}',
                    method: 'GET',
                    data: { search: term },
                    success: function(res) {
                        scannedProduct = res.product;
                        if (res.batches.length === 1) {
                            addBatch(res.batches[0]);
                        } else {
                            $('#modalProductName').text(scannedProduct.name);
                            $('#batchListContainer').empty();
                            res.batches.forEach(batch => {
                                let btn = $(`
                                    <button class="batch-select-btn w-full text-left bg-gray-50 hover:bg-blue-50 border border-gray-200 p-3 rounded-lg flex justify-between items-center transition mb-2">
                                        <div>
                                            <p class="font-semibold text-gray-900 text-sm">${batch.batch}</p>
                                            <p class="text-xs text-gray-500">Exp: ${batch.expired_date}</p>
                                        </div>
                                        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-1 rounded">Stok: ${batch.stock}</span>
                                    </button>
                                `);
                                btn.on('click', function() { addBatch(batch); });
                                $('#batchListContainer').append(btn);
                            });
                            $('#batchModal').fadeIn(200);
                        }
                    },
                    error: function(xhr) {
                        $('#errorMessage').text(xhr.responseJSON?.message || 'Gagal mencari produk').show();
                    }
                });
            }

            $('#btnCloseBatchModal').on('click', function() {
                $('#batchModal').fadeOut(200);
            });

            function addBatch(batch) {
                $('#batchModal').fadeOut(200);
                
                let existingItem = items.find(i => i.product_stock_id === batch.id);
                
                if (!existingItem) {
                    items.unshift({
                        product_id: scannedProduct.id,
                        product_name: scannedProduct.name,
                        product_stock_id: batch.id,
                        batch_number: batch.batch,
                        system_stock: batch.stock,
                        physical_stock: 0,
                        discrepancy: -batch.stock,
                        reason: '',
                        note: ''
                    });
                    mainTable.clear().rows.add(items).draw(false);
                }
                
                updateSummaryCards();
                
                setTimeout(() => {
                    $(`input.phys-input[data-batch="${batch.id}"]`).focus().select();
                }, 300);
            }


            // ================= TABLE INITIALIZATION =================
            function initMainTable() {
                let columns = [
                    { data: 'product_name' },
                    { data: 'batch_number' },
                    { data: 'system_stock', className: 'text-center font-semibold' },
                    { 
                        data: null, 
                        className: 'text-center',
                        render: function(data, type, row) {
                            if(!isEditable) return `<strong>${row.physical_stock}</strong>`;
                            return `<input type="number" class="phys-input w-20 text-center bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-1.5" data-batch="${row.product_stock_id}" value="${row.physical_stock}">`;
                        }
                    },
                    {
                        data: null,
                        className: 'text-center font-bold disc-text',
                        render: function(data, type, row) {
                            let color = row.discrepancy < 0 ? 'text-red-600' : (row.discrepancy > 0 ? 'text-green-600' : 'text-gray-900');
                            let text = row.discrepancy > 0 ? '+' + row.discrepancy : row.discrepancy;
                            return `<span class="${color}">${text}</span>`;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            if(!isEditable) return `<span class="text-xs bg-gray-200 px-2 py-1 rounded">${row.reason || '-'}</span>`;
                            let disabled = row.discrepancy === 0 ? 'disabled' : '';
                            let errorBorder = (row.discrepancy !== 0 && !row.reason) ? 'border-red-500' : 'border-gray-300';
                            
                            return `
                                <select class="reason-select bg-gray-50 border ${errorBorder} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-1.5" data-batch="${row.product_stock_id}" ${disabled}>
                                    <option value="">- Pilih -</option>
                                    <option value="EXPIRED" ${row.reason=='EXPIRED'?'selected':''}>Kedaluwarsa</option>
                                    <option value="DAMAGED" ${row.reason=='DAMAGED'?'selected':''}>Rusak</option>
                                    <option value="LOST" ${row.reason=='LOST'?'selected':''}>Hilang</option>
                                    <option value="MISCOUNT" ${row.reason=='MISCOUNT'?'selected':''}>Salah Hitung</option>
                                </select>
                            `;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            if(!isEditable) return `<span class="text-sm">${row.note || '-'}</span>`;
                            return `<input type="text" class="note-input w-full md:w-32 lg:w-48 bg-gray-50 border border-gray-300 text-sm rounded-lg p-1.5" data-batch="${row.product_stock_id}" placeholder="Opsional" value="${row.note}">`;
                        }
                    }
                ];

                if(isEditable) {
                    columns.push({
                        data: null,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `<button type="button" class="btn-remove text-red-600 hover:underline text-sm font-medium" data-batch="${row.product_stock_id}">Hapus</button>`;
                        }
                    });
                }

                mainTable = $('#mainTable').DataTable({
                    data: items,
                    columns: columns,
                    pageLength: 25,
                    ordering: false
                });
            }

            function initMismatchTable() {
                mismatchTable = $('#mismatchTable').DataTable({
                    data: [],
                    columns: [
                        { data: 'product_name' },
                        { data: 'batch_number' },
                        { data: 'system_stock', className: 'text-center' },
                        { data: 'physical_stock', className: 'text-center font-bold text-gray-900' },
                        {
                            data: null,
                            className: 'text-center font-bold',
                            render: function(data, type, row) {
                                let color = row.discrepancy < 0 ? 'text-red-600' : 'text-green-600';
                                let text = row.discrepancy > 0 ? '+' + row.discrepancy : row.discrepancy;
                                return `<span class="${color}">${text}</span>`;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                let badgeMap = {
                                    'EXPIRED': 'Kedaluwarsa',
                                    'DAMAGED': 'Rusak',
                                    'LOST': 'Hilang',
                                    'MISCOUNT': 'Salah Hitung'
                                };
                                let text = badgeMap[row.reason] || '-';
                                let bg = !row.reason ? 'bg-red-100 text-red-800' : 'bg-gray-200 text-gray-800';
                                return `<span class="text-xs px-2 py-1 rounded ${bg}">${text}</span>`;
                            }
                        }
                    ],
                    pageLength: 10,
                    ordering: true
                });
            }

            // ================= EVENT DELEGATION FOR INPUTS =================
            $('#mainTable tbody').on('input', '.phys-input', function() {
                let batchId = $(this).data('batch');
                let val = parseInt($(this).val()) || 0;
                
                let item = items.find(i => i.product_stock_id === batchId);
                if(item) {
                    item.physical_stock = val;
                    item.discrepancy = val - item.system_stock;
                    if(item.discrepancy === 0) item.reason = '';

                    let tr = $(this).closest('tr');
                    
                    let discEl = tr.find('.disc-text span');
                    let color = item.discrepancy < 0 ? 'text-red-600' : (item.discrepancy > 0 ? 'text-green-600' : 'text-gray-900');
                    let text = item.discrepancy > 0 ? '+' + item.discrepancy : item.discrepancy;
                    discEl.removeClass('text-red-600 text-green-600 text-gray-900').addClass(color).text(text);
                    
                    let sel = tr.find('.reason-select');
                    if (item.discrepancy === 0) {
                        sel.val('').prop('disabled', true).removeClass('border-red-500').addClass('border-gray-300');
                    } else {
                        sel.prop('disabled', false);
                        if (!item.reason) {
                            sel.removeClass('border-gray-300').addClass('border-red-500');
                        } else {
                            sel.removeClass('border-red-500').addClass('border-gray-300');
                        }
                    }

                    updateSummaryCards();
                }
            });

            $('#mainTable tbody').on('keypress', '.phys-input', function(e) {
                if(e.which == 13) {
                    e.preventDefault();
                    $('#scannerInput').focus();
                }
            });

            $('#mainTable tbody').on('change', '.reason-select', function() {
                let batchId = $(this).data('batch');
                let item = items.find(i => i.product_stock_id === batchId);
                if(item) {
                    item.reason = $(this).val();
                    if(item.reason) {
                        $(this).removeClass('border-red-500').addClass('border-gray-300');
                    } else {
                        $(this).removeClass('border-gray-300').addClass('border-red-500');
                    }
                }
            });

            $('#mainTable tbody').on('input', '.note-input', function() {
                let batchId = $(this).data('batch');
                let item = items.find(i => i.product_stock_id === batchId);
                if(item) {
                    item.note = $(this).val();
                }
            });

            $('#mainTable tbody').on('click', '.btn-remove', function() {
                let batchId = $(this).data('batch');
                items = items.filter(i => i.product_stock_id !== batchId);
                mainTable.clear().rows.add(items).draw(false);
                updateSummaryCards();
            });


            // ================= SUMMARY CARDS & MISMATCH MODAL =================
            function updateSummaryCards() {
                let totalProducts = items.length;
                let mismatchedItems = items.filter(i => i.discrepancy !== 0);
                let totalMismatched = mismatchedItems.length;
                let totalQtyMismatched = items.reduce((sum, item) => sum + Math.abs(item.discrepancy), 0);

                $('#summaryTotalProducts').text(totalProducts);
                $('#summaryTotalMismatched').text(totalMismatched);
                $('#summaryTotalQtyMismatched').text(totalQtyMismatched);
            }

            $('#cardMismatched').on('click', function() {
                let mismatchedItems = items.filter(i => i.discrepancy !== 0);
                mismatchTable.clear().rows.add(mismatchedItems).draw();
                $('#mismatchModal').fadeIn(200);
            });

            $('#btnCloseMismatchModal').on('click', function() {
                $('#mismatchModal').fadeOut(200);
            });

            
            // ================= FORM SUBMISSION =================
            $('#btnSaveDraft').on('click', function() { submitData('DRAFT'); });
            $('#btnComplete').on('click', function() { submitData('COMPLETE'); });

            function submitData(action) {
                if (!isEditable) return;

                if (action === 'COMPLETE') {
                    let invalid = items.find(i => i.discrepancy !== 0 && !i.reason);
                    if (invalid) {
                        alert(`Item ${invalid.product_name} (${invalid.batch_number}) wajib diisi alasan karena ada selisih.`);
                        return;
                    }
                }

                $('.btn-complete, .btn-draft').prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: `/dashboard/stock-opname/${opnameId}/submit`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        action: action,
                        items: items
                    },
                    success: function(res) {
                        if (action === 'COMPLETE') {
                            window.location.href = res.redirect;
                        } else {
                            alert('Draft tersimpan');
                            $('.btn-draft').prop('disabled', false).text('Save Draft');
                            $('.btn-complete').prop('disabled', false).text('Complete & Sesuaikan Stok');
                        }
                    },
                    error: function(xhr) {
                        alert('Error: ' + (xhr.responseJSON?.message || 'Server error'));
                        $('.btn-draft').prop('disabled', false).text('Save Draft');
                        $('.btn-complete').prop('disabled', false).text('Complete & Sesuaikan Stok');
                    }
                });
            }
        });
    </script>
@endpush
