<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Pesanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if($orders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="p-4">Kode Order</th>
                                    <th class="p-4">Tanggal</th>
                                    <th class="p-4">Total</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-4 font-bold">{{ $order->order_code }}</td>
                                        <td class="p-4">{{ $order->created_at->format('d M Y') }}</td>
                                        <td class="p-4">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                        <td class="p-4">
                                            <span class="px-2 py-1 rounded text-xs font-bold
                                                {{ $order->status == 'pending_payment' ? 'bg-yellow-200 text-yellow-800' : '' }}
                                                {{ $order->status == 'paid' ? 'bg-green-200 text-green-800' : '' }}
                                                {{ $order->status == 'processing' ? 'bg-blue-200 text-blue-800' : '' }}
                                                {{ $order->status == 'shipped' ? 'bg-indigo-200 text-indigo-800' : '' }}
                                                {{ $order->status == 'completed' ? 'bg-gray-200 text-gray-800' : '' }}
                                                {{ $order->status == 'cancelled' ? 'bg-red-200 text-red-800' : '' }}
                                            ">
                                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                        </td>
                                        <td class="p-4">
                                            <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">Detail</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                @else
                    <p class="text-gray-500 text-center">Belum ada pesanan.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
