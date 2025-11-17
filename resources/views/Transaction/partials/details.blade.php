<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="font-semibold text-gray-600">Reference:</label>
            <p class="text-gray-900">{{ $transaction->reference }}</p>
        </div>
        <div>
            <label class="font-semibold text-gray-600">Customer:</label>
            <p class="text-gray-900">{{ $transaction->customer_name }}</p>
        </div>
        <div>
            <label class="font-semibold text-gray-600">Payment Type:</label>
            <p class="text-gray-900">{{ $transaction->payment_type }}</p>
        </div>
        <div>
            <label class="font-semibold text-gray-600">Date & Time:</label>
            <p class="text-gray-900">{{ $transaction->created_at->format('M j, Y h:i A') }}</p>
        </div>
    </div>

    <div>
        <h4 class="font-semibold text-gray-600 mb-2">Items:</h4>
        <div class="border rounded-lg overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Product</th>
                        <th class="px-4 py-2 text-left">Price</th>
                        <th class="px-4 py-2 text-left">Qty</th>
                        <th class="px-4 py-2 text-left">Total</th>
                        <th class="px-4 py-2 text-left">Profit</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($transaction->items as $item)
                    <tr>
                        <td class="px-4 py-2">{{ $item->product->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">₦{{ number_format($item->unit_price, 2) }}</td>
                        <td class="px-4 py-2">{{ $item->quantity }}</td>
                        <td class="px-4 py-2">₦{{ number_format($item->total, 2) }}</td>
                        <td class="px-4 py-2 text-green-600">₦{{ number_format($item->profit, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 font-semibold">
                    <tr>
                        <td colspan="3" class="px-4 py-2 text-right">Grand Total:</td>
                        <td class="px-4 py-2">₦{{ number_format($transaction->total_amount, 2) }}</td>
                        <td class="px-4 py-2 text-green-600">₦{{ number_format($transaction->items->sum('profit'), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>