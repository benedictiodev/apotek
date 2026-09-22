@extends('layouts.index') <!-- Sesuaikan dengan nama layout parent-mu -->

@section('main')
    <div class="mb-4 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Data Stock Opname</h2>
        <!-- Modal Toggle Button -->
        <button data-modal-target="create-opname-modal" data-modal-toggle="create-opname-modal"
            class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
            type="button">
            + Buat Opname Baru
        </button>
    </div>

    @if (session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50">{{ session('success') }}</div>
    @endif

    <div class="p-4 bg-white rounded-lg shadow-sm">
        <table id="opnameTable" class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th>Judul / Ref</th>
                    <th>Status</th>
                    <th>Petugas</th>
                    <th>Tgl Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($opnames as $opname)
                    <tr>
                        <td class="font-medium text-gray-900">{{ $opname->title }}</td>
                        <td>
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-lg 
                        {{ $opname->status == 'DONE' ? 'bg-green-100 text-green-800' : ($opname->status == 'CANCEL' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ $opname->status }}
                            </span>
                        </td>
                        <td>{{ $opname->user->name ?? 'System' }}</td>
                        <td>{{ $opname->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <a href="{{ route('dashboard.stock-opname.show', $opname->id) }}"
                                class="text-blue-600 hover:underline">
                                {{ in_array($opname->status, ['NEW', 'DRAFT']) ? 'Lanjutkan' : 'Lihat Detail' }}
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Create Flowbite -->
    <div id="create-opname-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow">
                <div class="flex items-center justify-between p-4 border-b rounded-t">
                    <h3 class="text-lg font-semibold text-gray-900">Buat Sesi Opname</h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                        data-modal-toggle="create-opname-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>
                <form action="{{ route('dashboard.stock-opname.store') }}" method="POST" class="p-4">
                    @csrf
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Judul Opname</label>
                        <input type="text" name="title"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Contoh: Opname Gudang Depan Okt 26" required>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Catatan (Opsional)</label>
                        <textarea name="note" rows="3"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"></textarea>
                    </div>
                    <button type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Buat
                        & Mulai Scan</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#opnameTable').DataTable({
                order: [
                    [3, 'desc']
                ], // Urut berdasarkan created_at
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampil _MENU_ data"
                }
            });
        });
    </script>
@endpush
