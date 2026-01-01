@extends('layouts.app')
@section('title', 'Sales Entry')

@section('content')

<div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Header -->
    <div class="bg-white shadow p-4 md:p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <!-- Title -->
        <div class="flex items-center gap-2">
            <i data-lucide="shopping-bag" class="w-6 h-6 text-emerald-600"></i>
            <h1 class="text-xl md:text-2xl font-semibold text-gray-800">Sales Entry</h1>
        </div>

        <!-- Quick Navigation Strip -->
        <nav class="flex flex-wrap justify-center md:justify-end gap-2 md:gap-3">
            <a href="{{ route('transaction.daily') }}"
               class="flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition"
               title="View today's transactions">
                <i data-lucide="calendar-days" class="w-4 h-4"></i>
                <span class="hidden sm:inline">Daily</span>
            </a>

            <!-- Add Low Stock Alert Link -->
            <a href="{{ route('products.low-stock') }}"
               class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition"
               title="View low stock products">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                <span class="hidden sm:inline">Low Stock</span>
            </a>

            <!-- REMOVED: Debts route as it doesn't exist -->
            <a href="{{ route('debts') }}" class="flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition" title="View indebted customers">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                <span class="hidden sm:inline">Debts</span>
            </a>

            <a href="{{ route('sold.items') }}"
               class="flex items-center gap-1.5 px-3 py-1.5 bg-sky-50 text-sky-600 rounded-lg hover:bg-sky-100 transition"
               title="View sold products">
                <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                <span class="hidden sm:inline">Sold</span>
            </a>

            <a href="{{ route('stock.out') }}"
               class="flex items-center gap-1.5 px-3 py-1.5 bg-orange-50 text-orange-600 rounded-lg hover:bg-orange-100 transition"
               title="Stock out record">
                <i data-lucide="package-minus" class="w-4 h-4"></i>
                <span class="hidden sm:inline">Stock</span>
            </a>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit"
                    class="flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition"
                    title="Logout from system">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">Logout</span>
                </button>
            </form>

            <button id="backToDashboardBtn"
                class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 border border-gray-200 transition"
                title="Return to Dashboard">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span class="hidden sm:inline">Dashboard</span>
            </button>
        </nav>
    </div>

    <!-- Main Content -->
    <main class="flex-1 p-6 space-y-6">
        <!-- 🧾 Transaction Entry Form -->
        <form id="salesForm" class="bg-white p-6 rounded-xl shadow space-y-6">
            <div class="grid md:grid-cols-3 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Location</label>
                    <select name="location" class="w-full border rounded-lg px-4 py-2">
                        <option value="">Select Location</option>
                        <option>Main Shop</option>
                        <option>Godown</option>
                        <option>Branch</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Customer Name</label>
                    <input type="text" name="customer" placeholder="Enter customer name"
                           class="w-full border rounded-lg px-4 py-2" id="customerName">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Transaction Ref</label>
                    <input type="text" id="transactionRef" readonly
                        class="w-full border rounded-lg px-4 py-2 bg-gray-100 font-mono text-gray-700">
                </div>

                <!-- Payment Type Selection -->
                <div>
                  <label class="block text-gray-700 font-medium mb-2">Payment Type</label>
                  <div id="payment_typeGroup" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <label class="flex items-center justify-center gap-2 border rounded-lg p-2 cursor-pointer hover:bg-emerald-50 transition">
                      <input type="radio" name="payment_type" value="Cash" class="hidden" checked>
                      <i data-lucide="wallet" class="w-5 h-5 text-emerald-600"></i>
                      <span class="font-medium text-gray-700">Cash</span>
                    </label>

                    <label class="flex items-center justify-center gap-2 border rounded-lg p-2 cursor-pointer hover:bg-emerald-50 transition">
                      <input type="radio" name="payment_type" value="POS" class="hidden">
                      <i data-lucide="credit-card" class="w-5 h-5 text-emerald-600"></i>
                      <span class="font-medium text-gray-700">POS</span>
                    </label>

                    <label class="flex items-center justify-center gap-2 border rounded-lg p-2 cursor-pointer hover:bg-emerald-50 transition">
                      <input type="radio" name="payment_type" value="Bank Transfer" class="hidden">
                      <i data-lucide="banknote" class="w-5 h-5 text-emerald-600"></i>
                      <span class="font-medium text-gray-700">Transfer</span>
                    </label>

                    <label class="flex items-center justify-center gap-2 border rounded-lg p-2 cursor-pointer hover:bg-emerald-50 transition">
                      <input type="radio" name="payment_type" value="Credit" class="hidden">
                      <i data-lucide="file-minus" class="w-5 h-5 text-emerald-600"></i>
                      <span class="font-medium text-gray-700">Credit</span>
                    </label>
                  </div>
                </div>

            </div>

            <hr class="my-4">

            <div class="grid md:grid-cols-5 sm:grid-cols-2 gap-4 items-end">
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Product</label>
                    <select id="productSelect" class="w-full border rounded-lg px-3 py-2">
                        <option value="">Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}">
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Unit Price (₦)</label>
                    <input type="number" id="unitPrice" readonly
                           class="w-full border rounded-lg px-3 py-2 bg-gray-100">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Discount (₦)</label>
                    <input type="number" id="discount" min="0" value="0"
                           class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Quantity</label>
                    <input type="number" id="quantity" min="0.01" step="0.01"
                           class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <button type="button" id="addItemBtn"
                            class="w-full bg-emerald-600 text-white py-2 rounded-lg hover:bg-emerald-700 transition">
                        Add Item
                    </button>
                </div>
            </div>
        </form>

        <!-- 🧮 Transaction Table -->
        <section class="bg-white p-6 rounded-xl shadow space-y-6">
            <h2 class="text-lg font-semibold text-gray-800">Transaction Items</h2>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-sm text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3">Product</th>
                            <th class="p-3">Unit Price</th>
                            <th class="p-3">Discount</th>
                            <th class="p-3">Quantity</th>
                            <th class="p-3">Total</th>
                            <th class="p-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody id="transactionBody" class="divide-y divide-gray-100">
                        <tr id="emptyRow">
                            <td colspan="6" class="text-center text-gray-500 py-4 italic">
                                No items added yet.
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-gray-50 font-semibold text-gray-800">
                        <tr>
                            <td colspan="4" class="p-3 text-right">Grand Total:</td>
                            <td id="grandTotal" class="p-3">₦0.00</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- ⚙ Action Buttons -->
            <div class="flex flex-col gap-6 mt-8">

                <!-- Transaction Control Buttons -->
                <div class="flex flex-wrap justify-end gap-3">
                    <button id="resetBtn" type="button"
                        class="flex items-center gap-2 bg-gray-100 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-200 transition">
                        <i data-lucide="refresh-ccw" class="w-4 h-4"></i>
                        <span>Reset</span>
                    </button>

                    <button id="holdBtn" type="button"
                        class="flex items-center gap-2 bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">
                        <i data-lucide="pause-circle" class="w-4 h-4"></i>
                        <span>Hold</span>
                    </button>

                    <button id="completeBtn" type="button"
                        class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition group">
                        <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                        <span>Complete Sale</span>
                    </button>
                </div>

            </div>
        </section>
    </main>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const axiosInstance = window.axios || axios; // make sure axios is available
        // now you can use axiosInstance.post(...)

    // Elements (guarded)
    const transactionRefEl = document.getElementById("transactionRef");
    const productSelect = document.getElementById("productSelect");
    const unitPrice = document.getElementById("unitPrice");
    const discountInput = document.getElementById("discount");
    const qtyInput = document.getElementById("quantity");
    const addItemBtn = document.getElementById("addItemBtn");
    const transactionBody = document.getElementById("transactionBody");
    const grandTotalEl = document.getElementById("grandTotal");
    const completeBtn = document.getElementById("completeBtn");
    const customerInput = document.getElementById("customerName");
    const backBtn = document.getElementById("backToDashboardBtn");
    const resetBtn = document.getElementById("resetBtn");
    const payment_typeGroup = document.getElementById("payment_typeGroup");

    if (payment_typeGroup) {
      payment_typeGroup.addEventListener("click", function (e) {
        const label = e.target.closest("label");
        if (!label) return;

        // remove active highlight
        payment_typeGroup.querySelectorAll("label").forEach(l => {
          l.classList.remove("border-emerald-500", "bg-emerald-50");
        });

        // activate selected
        label.classList.add("border-emerald-500", "bg-emerald-50");

        // ensure the input is checked
        const input = label.querySelector("input[type='radio']");
        if (input) input.checked = true;
      });
    }

    // Safety: stop if required elements missing
    if (!transactionRefEl || !productSelect || !addItemBtn || !transactionBody || !grandTotalEl) {
        console.warn("Sales script: missing DOM elements, script halted.");
        return;
    }

    // Generate transaction ref
    const ref = "TX" + new Date().toISOString().replace(/\D/g, "").slice(0, 14);
    transactionRefEl.value = ref;

    // Recalc helper
    window.recalcTotal = function() {
        let sum = 0;
        const rows = transactionBody.querySelectorAll("tr");
        rows.forEach(row => {
            // skip placeholder row
            if (row.id === "emptyRow") return;
            const totalCell = row.children[4];
            if (totalCell) {
                const raw = totalCell.textContent.replace(/[₦,]/g, "").trim();
                const n = parseFloat(raw) || 0;
                sum += n;
            }
        });
        grandTotalEl.textContent = "₦" + sum.toFixed(2);
    };

    // Auto-fill unit price on product change
    productSelect.addEventListener("change", function() {
        const opt = this.selectedOptions[0];
        const price = opt && opt.dataset && opt.dataset.price ? parseFloat(opt.dataset.price) : 0;
        unitPrice.value = price ? price.toFixed(2) : "";
    });

    // Add item
    addItemBtn.addEventListener("click", function() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const productId = productSelect.value;
        const productName = selectedOption ? selectedOption.text.trim() : "";
        const price = parseFloat(unitPrice.value || 0);
        const discount = parseFloat(discountInput?.value || 0);
        const qty = parseFloat(qtyInput?.value || 0);

        // Validation
        if (!productId) {
            alert("Select a proper product.");
            productSelect.focus();
            return;
        }
        if (!(qty > 0)) {
            alert("Enter a valid quantity greater than 0.");
            qtyInput?.focus();
            return;
        }

        const discountedUnit = price - discount;
        if (discountedUnit <= 0) {
            alert("Invalid discount — price after discount must be greater than 0.");
            discountInput?.focus();
            return;
        }

        const itemTotal = discountedUnit * qty;

        // Remove placeholder if present
        const placeholder = document.getElementById("emptyRow");
        if (placeholder) placeholder.remove();

        // create row
        const tr = document.createElement("tr");
        // store product id and values in data attributes to facilitate edits later if desired
        tr.innerHTML = `
            <td class="p-3" data-product-id="${productId}">${escapeHtml(productName)}</td>
            <td class="p-3">₦${price.toFixed(2)}</td>
            <td class="p-3 text-red-600">₦${discount.toFixed(2)}</td>
            <td class="p-3">${qty % 1 === 0 ? qty.toFixed(0) : qty.toFixed(2)}</td>
            <td class="p-3 font-semibold">₦${itemTotal.toFixed(2)}</td>
            <td class="p-3 text-right">
                <button type="button" class="delete-btn text-red-500 hover:text-red-700 transition" title="Remove Item">🗑</button>
            </td>`;

        transactionBody.appendChild(tr);

        // reset fields (product remains selectable from dropdown but input fields cleared)
        productSelect.selectedIndex = 0;
        unitPrice.value = "";
        if (discountInput) discountInput.value = "0";
        if (qtyInput) qtyInput.value = "";
        productSelect.focus();

        // recalc total
        recalcTotal();
    });

    // Delegated delete handler with confirmation (single listener)
    transactionBody.addEventListener("click", function(e) {
        const target = e.target;
        if (target.classList.contains("delete-btn")) {
            const row = target.closest("tr");
            const productCell = row?.children[0];
            const productName = productCell ? productCell.textContent.trim() : "this item";

            if (confirm("Are You Sure you want to remove " + productName + " from this transaction?")) {
                // optional animation
                row.style.transition = "opacity 0.2s ease, height 0.2s ease";
                row.style.opacity = 0;
                setTimeout(() => {
                    row.remove();
                    recalcTotal();

                    // restore placeholder if empty
                    const rowsLeft = transactionBody.querySelectorAll("tr");
                    if (rowsLeft.length === 0) {
                        transactionBody.innerHTML = `
                            <tr id="emptyRow">
                                <td colspan="6" class="text-center text-gray-500 py-4 italic">No items added yet.</td>
                            </tr>`;
                    }
                }, 180);
            }
        }
    });

    // Reset button behaviour
    if (resetBtn) {
        resetBtn.addEventListener("click", function() {
            if (confirm("Reset this transaction? This will remove all added items.")) {
                document.getElementById("salesForm")?.reset();
                transactionBody.innerHTML = `
                    <tr id="emptyRow">
                        <td colspan="6" class="text-center text-gray-500 py-4 italic">No items added yet.</td>
                    </tr>`;
                grandTotalEl.textContent = "₦0.00";
            }
        });
    }

 // Complete sale button: validate customer and items
if (completeBtn) {
    completeBtn.addEventListener("click", async function () {
        console.log('🔍 DEBUG 1: Complete button clicked');
        
        // Basic validation
        const rows = transactionBody.querySelectorAll("tr:not(#emptyRow)");
        const hasItems = Array.from(rows).some(r => !r.id || r.id !== "emptyRow");
        const customerName = customerInput ? customerInput.value.trim() : "";

        console.log('🔍 DEBUG 2: Customer name:', customerName);
        console.log('🔍 DEBUG 3: Has items:', hasItems);

        if (!customerName) {
            alert("⚠ Please enter the customer's name before completing the sale.");
            customerInput?.focus();
            return;
        }

        if (!hasItems) {
            alert("⚠ You must add at least one product to the transaction before completing it.");
            return;
        }

        // Build payload with careful parsing
        try {
            console.log('🔍 DEBUG 4: Building payload...');
            
            const payload = {
                reference: document.getElementById("transactionRef").value,
                customer_name: customerName,
                location: document.querySelector("select[name='location']").value,
                payment_type: document.querySelector("input[name='payment_type']:checked").value,
                items: Array.from(document.querySelectorAll("#transactionBody tr:not(#emptyRow)")).map((row, index) => {
                    console.log(`🔍 DEBUG 4.${index}: Processing row`, row);
                    const cells = row.children;
                    
                    const productId = parseInt(cells[0].dataset.productId);
                    const unitPriceText = cells[1].textContent.replace(/[₦,]/g, "");
                    const discountText = cells[2].textContent.replace(/[₦,]/g, "");
                    const quantityText = cells[3].textContent;
                    const totalText = cells[4].textContent.replace(/[₦,]/g, "");

                    console.log(`🔍 DEBUG 4.${index}.1: Raw values -`, {
                        productId, unitPriceText, discountText, quantityText, totalText
                    });

                    const item = {
                        product_id: productId,
                        unit_price: parseFloat(unitPriceText),
                        discount: parseFloat(discountText),
                        quantity: parseFloat(quantityText),
                        total: parseFloat(totalText),
                    };

                    console.log(`🔍 DEBUG 4.${index}.2: Parsed item -`, item);
                    return item;
                }),
                total_amount: parseFloat(grandTotalEl.textContent.replace(/[₦,]/g, ""))
            };

            console.log('🔍 DEBUG 5: Final payload:', payload);
            console.log('🔍 DEBUG 6: Route URL:', "{{ route('transactions.store') }}");
            console.log('🔍 DEBUG 7: CSRF Token:', "{{ csrf_token() }}");

            // Make the request
            console.log('🔍 DEBUG 8: Making Axios request...');
            
            const response = await axios.post("{{ route('transactions.store') }}", payload, {
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                },
                timeout: 10000 // 10 second timeout
            });

            console.log('🔍 DEBUG 9: Request successful! Response:', response);
            console.log('🔍 DEBUG 10: Response data:', response.data);
            console.log('🔍 DEBUG 11: Response status:', response.status);

            alert(response.data.message || "✅ Transaction saved successfully!");
            window.location.reload();

        } catch (error) {
            console.error('🔍 DEBUG ERROR: Full error object:', error);
            console.error('🔍 DEBUG ERROR: Error name:', error.name);
            console.error('🔍 DEBUG ERROR: Error message:', error.message);
            console.error('🔍 DEBUG ERROR: Error code:', error.code);
            
            if (error.response) {
                // Server responded with error status
                console.error('🔍 DEBUG ERROR: Response status:', error.response.status);
                console.error('🔍 DEBUG ERROR: Response data:', error.response.data);
                console.error('🔍 DEBUG ERROR: Response headers:', error.response.headers);
            } else if (error.request) {
                // Request was made but no response received
                console.error('🔍 DEBUG ERROR: No response received. Request:', error.request);
            } else {
                // Something else happened
                console.error('🔍 DEBUG ERROR: Other error:', error);
            }
            
            console.error('🔍 DEBUG ERROR: Error config:', error.config);
            
            // User-friendly error message
            let userMessage = "Transaction failed: ";
            if (error.response?.data?.message) {
                userMessage += error.response.data.message;
            } else if (error.message) {
                userMessage += error.message;
            } else {
                userMessage += "Unknown error occurred";
            }
            
            alert("⚠ " + userMessage);
        }
    });
}
    // Back to dashboard with confirmation & reset
    if (backBtn) {
        backBtn.addEventListener("click", function() {
            const rows = transactionBody.querySelectorAll("tr:not(#emptyRow)");
            const hasItems = Array.from(rows).some(r => !r.id || r.id !== "emptyRow");
            const customerName = customerInput ? customerInput.value.trim() : "";

            let confirmMsg = "Are you sure you want to return to the dashboard?";
            if (hasItems || customerName) {
                confirmMsg = "⚠ You have an ongoing transaction.\nIf you leave now, it will be reset.\n\nDo you want to proceed?";
            }

            if (confirm(confirmMsg)) {
                document.getElementById("salesForm")?.reset();
                transactionBody.innerHTML = `
                    <tr id="emptyRow">
                        <td colspan="6" class="text-center text-gray-500 py-4 italic">No items added yet.</td>
                    </tr>`;
                grandTotalEl.textContent = "₦0.00";
                setTimeout(() => {
                    // redirect using window.location.href; blade route string will be rendered on server-side
                    window.location.href = "{{ route('dashboard') }}";
                }, 250);
            }
        });
    }

    // Small helper to escape HTML (prevents injection in productName)
    function escapeHtml(unsafe) {
        return unsafe
            .replaceAll("&", "&amp;")
            .replaceAll("<", "&lt;")
            .replaceAll(">", "&gt;")
            .replaceAll('"', "&quot;")
            .replaceAll("'", "&#039;");
    }
});
</script>
@endsection