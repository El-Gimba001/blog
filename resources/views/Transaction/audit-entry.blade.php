@extends('layouts.app')
@section('title', 'Auditor Panel - Over-Stock Adjustment')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Header -->
    <div class="bg-white shadow p-4 md:p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <!-- Title -->
        <div class="flex items-center gap-2">
            <i data-lucide="clipboard-check" class="w-6 h-6 text-purple-600"></i>
            <h1 class="text-xl md:text-2xl font-semibold text-gray-800">Audit Over-Stock Adjustment</h1>
        </div>

        <!-- Quick Navigation Strip -->
        <nav class="flex flex-wrap justify-center md:justify-end gap-2 md:gap-3">
            <a href="{{ route('auditor.reports') }}"
               class="flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition"
               title="View audit reports">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                <span class="hidden sm:inline">Reports</span>
            </a>

            <a href="{{ route('products.index') }}"
               class="flex items-center gap-1.5 px-3 py-1.5 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition"
               title="View all products">
                <i data-lucide="package" class="w-4 h-4"></i>
                <span class="hidden sm:inline">Products</span>
            </a>

            <a href="{{ route('daily.transactions') }}"
               class="flex items-center gap-1.5 px-3 py-1.5 bg-orange-50 text-orange-600 rounded-lg hover:bg-orange-100 transition"
               title="View transactions">
                <i data-lucide="receipt" class="w-4 h-4"></i>
                <span class="hidden sm:inline">Transactions</span>
            </a>

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
        <!-- 🧾 Audit Adjustment Form -->
        <form id="auditForm" class="bg-white p-6 rounded-xl shadow space-y-6">
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
                    <label class="block text-gray-700 font-medium mb-1">Audit Reference</label>
                    <input type="text" id="auditReference" readonly
                        class="w-full border rounded-lg px-4 py-2 bg-gray-100 font-mono text-gray-700">
                </div>

                <!-- Payment Type Selection -->
                <div>
                  <label class="block text-gray-700 font-medium mb-2">Payment Type</label>
                  <div id="paymentTypeGroup" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <label class="flex items-center justify-center gap-2 border rounded-lg p-2 cursor-pointer hover:bg-purple-50 transition">
                      <input type="radio" name="paymentType" value="Cash" class="hidden" checked>
                      <i data-lucide="wallet" class="w-5 h-5 text-purple-600"></i>
                      <span class="font-medium text-gray-700">Cash</span>
                    </label>

                    <label class="flex items-center justify-center gap-2 border rounded-lg p-2 cursor-pointer hover:bg-purple-50 transition">
                      <input type="radio" name="paymentType" value="POS" class="hidden">
                      <i data-lucide="credit-card" class="w-5 h-5 text-purple-600"></i>
                      <span class="font-medium text-gray-700">POS</span>
                    </label>

                    <label class="flex items-center justify-center gap-2 border rounded-lg p-2 cursor-pointer hover:bg-purple-50 transition">
                      <input type="radio" name="paymentType" value="Bank Transfer" class="hidden">
                      <i data-lucide="banknote" class="w-5 h-5 text-purple-600"></i>
                      <span class="font-medium text-gray-700">Transfer</span>
                    </label>

                    <label class="flex items-center justify-center gap-2 border rounded-lg p-2 cursor-pointer hover:bg-purple-50 transition">
                      <input type="radio" name="paymentType" value="Credit" class="hidden">
                      <i data-lucide="file-minus" class="w-5 h-5 text-purple-600"></i>
                      <span class="font-medium text-gray-700">Credit</span>
                    </label>
                  </div>
                </div>
            </div>

            <hr class="my-4">

            <!-- Over-Stock Product Selection -->
            <div class="grid md:grid-cols-5 sm:grid-cols-2 gap-4 items-end">
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Over-Stock Product</label>
                    <select id="productSelect" class="w-full border rounded-lg px-3 py-2">
                        <option value="">Select Over-Stock Product</option>
                        <!-- Products will be loaded via JavaScript -->
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
                    <label class="block text-gray-700 font-medium mb-1">Quantity to Remove</label>
                    <input type="number" id="quantity" min="0.01" step="0.01"
                           class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <button type="button" id="addItemBtn"
                            class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition">
                        Add to Audit
                    </button>
                </div>
            </div>

            <!-- Audit Details -->
            <div class="grid md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Reason for Over-Stock</label>
                    <textarea name="reason" class="w-full border rounded-lg px-3 py-2" rows="3"
                              placeholder="Explain why this product became over-stocked (e.g., registration error, incorrect counting, etc.)..." required></textarea>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Audit Findings & Impact</label>
                    <textarea name="discrepancy_notes" class="w-full border rounded-lg px-3 py-2" rows="3"
                              placeholder="Provide detailed explanation of the over-stock situation and its financial impact..." required></textarea>
                </div>
            </div>
        </form>

        <!-- 🧮 Audit Items Table -->
        <section class="bg-white p-6 rounded-xl shadow space-y-6">
            <h2 class="text-lg font-semibold text-gray-800">Audit Adjustment Items</h2>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-sm text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3">Product</th>
                            <th class="p-3">Current Stock</th>
                            <th class="p-3">Unit Price</th>
                            <th class="p-3">Discount</th>
                            <th class="p-3">Qty to Remove</th>
                            <th class="p-3">Total</th>
                            <th class="p-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody id="auditItemsBody" class="divide-y divide-gray-100">
                        <tr id="emptyRow">
                            <td colspan="7" class="text-center text-gray-500 py-4 italic">
                                No audit items added yet.
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-gray-50 font-semibold text-gray-800">
                        <tr>
                            <td colspan="5" class="p-3 text-right">Audit Total:</td>
                            <td id="auditTotal" class="p-3">₦0.00</td>
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
                        <span>Reset Audit</span>
                    </button>

                    <button id="completeAuditBtn" type="button"
                        class="flex items-center gap-2 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition group">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        <span>Send Audit Report to Admin</span>
                    </button>
                </div>
            </div>
        </section>
    </main>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Elements
        const auditReferenceEl = document.getElementById("auditReference");
        const productSelect = document.getElementById("productSelect");
        const unitPrice = document.getElementById("unitPrice");
        const discountInput = document.getElementById("discount");
        const qtyInput = document.getElementById("quantity");
        const addItemBtn = document.getElementById("addItemBtn");
        const auditItemsBody = document.getElementById("auditItemsBody");
        const auditTotalEl = document.getElementById("auditTotal");
        const completeAuditBtn = document.getElementById("completeAuditBtn");
        const customerInput = document.getElementById("customerName");
        const backBtn = document.getElementById("backToDashboardBtn");
        const resetBtn = document.getElementById("resetBtn");
        const paymentTypeGroup = document.getElementById("paymentTypeGroup");

        // Generate audit reference (different from sales)
        const ref = "AUD" + new Date().toISOString().replace(/\D/g, "").slice(0, 14);
        auditReferenceEl.value = ref;

        // Load over-stock products
        async function loadOverstockProducts() {
            try {
                const response = await fetch('{{ route("auditor.overstock-products") }}');
                const products = await response.json();
                
                // Clear existing options
                productSelect.innerHTML = '<option value="">Select Over-Stock Product</option>';
                
                // Add products
                products.forEach(product => {
                    const option = document.createElement('option');
                    option.value = product.id;
                    option.textContent = `${product.name} (${product.quantity} units)`;
                    option.dataset.price = product.selling_price || product.price;
                    option.dataset.quantity = product.quantity;
                    productSelect.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading over-stock products:', error);
            }
        }

        // Payment type selection
        if (paymentTypeGroup) {
            paymentTypeGroup.addEventListener("click", function (e) {
                const label = e.target.closest("label");
                if (!label) return;

                paymentTypeGroup.querySelectorAll("label").forEach(l => {
                    l.classList.remove("border-purple-500", "bg-purple-50");
                });

                label.classList.add("border-purple-500", "bg-purple-50");
                const input = label.querySelector("input[type='radio']");
                if (input) input.checked = true;
            });
        }

        // Auto-fill unit price on product change
        productSelect.addEventListener("change", function() {
            const opt = this.selectedOptions[0];
            const price = opt && opt.dataset && opt.dataset.price ? parseFloat(opt.dataset.price) : 0;
            const currentQty = opt && opt.dataset && opt.dataset.quantity ? parseInt(opt.dataset.quantity) : 0;
            
            unitPrice.value = price ? price.toFixed(2) : "";
            
            // Update quantity placeholder with max value
            if (qtyInput) {
                qtyInput.placeholder = `Max: ${currentQty}`;
                qtyInput.max = currentQty;
            }
        });

        // Recalc helper
        window.recalcTotal = function() {
            let sum = 0;
            const rows = auditItemsBody.querySelectorAll("tr");
            rows.forEach(row => {
                if (row.id === "emptyRow") return;
                const totalCell = row.children[5];
                if (totalCell) {
                    const raw = totalCell.textContent.replace(/[₦,]/g, "").trim();
                    const n = parseFloat(raw) || 0;
                    sum += n;
                }
            });
            auditTotalEl.textContent = "₦" + sum.toFixed(2);
        };

        // Add audit item
        addItemBtn.addEventListener("click", function() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const productId = productSelect.value;
            const productName = selectedOption ? selectedOption.text.trim() : "";
            const currentStock = selectedOption ? parseInt(selectedOption.dataset.quantity) : 0;
            const price = parseFloat(unitPrice.value || 0);
            const discount = parseFloat(discountInput?.value || 0);
            const qty = parseFloat(qtyInput?.value || 0);

            // Validation
            if (!productId) {
                alert("Select an over-stock product to audit.");
                productSelect.focus();
                return;
            }
            if (!(qty > 0)) {
                alert("Enter a valid quantity greater than 0.");
                qtyInput?.focus();
                return;
            }
            if (qty > currentStock) {
                alert(`Cannot remove more than current stock of ${currentStock} units.`);
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

            // Create audit item row
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td class="p-3" data-product-id="${productId}">${escapeHtml(productName)}</td>
                <td class="p-3">${currentStock}</td>
                <td class="p-3">₦${price.toFixed(2)}</td>
                <td class="p-3 text-red-600">₦${discount.toFixed(2)}</td>
                <td class="p-3">${qty % 1 === 0 ? qty.toFixed(0) : qty.toFixed(2)}</td>
                <td class="p-3 font-semibold">₦${itemTotal.toFixed(2)}</td>
                <td class="p-3 text-right">
                    <button type="button" class="delete-btn text-red-500 hover:text-red-700 transition" title="Remove Item">🗑</button>
                </td>`;

            auditItemsBody.appendChild(tr);

            // Reset fields
            productSelect.selectedIndex = 0;
            unitPrice.value = "";
            if (discountInput) discountInput.value = "0";
            if (qtyInput) qtyInput.value = "";
            productSelect.focus();

            // Recalc total
            recalcTotal();
        });

        // Delegated delete handler
        auditItemsBody.addEventListener("click", function(e) {
            const target = e.target;
            if (target.classList.contains("delete-btn")) {
                const row = target.closest("tr");
                const productCell = row?.children[0];
                const productName = productCell ? productCell.textContent.trim() : "this audit item";

                if (confirm("Remove " + productName + " from audit?")) {
                    row.style.transition = "opacity 0.2s ease, height 0.2s ease";
                    row.style.opacity = 0;
                    setTimeout(() => {
                        row.remove();
                        recalcTotal();

                        // Restore placeholder if empty
                        const rowsLeft = auditItemsBody.querySelectorAll("tr");
                        if (rowsLeft.length === 0) {
                            auditItemsBody.innerHTML = `
                                <tr id="emptyRow">
                                    <td colspan="7" class="text-center text-gray-500 py-4 italic">No audit items added yet.</td>
                                </tr>`;
                        }
                    }, 180);
                }
            }
        });

        // Reset audit
        if (resetBtn) {
            resetBtn.addEventListener("click", function() {
                if (confirm("Reset this audit? This will remove all added items.")) {
                    document.getElementById("auditForm")?.reset();
                    auditItemsBody.innerHTML = `
                        <tr id="emptyRow">
                            <td colspan="7" class="text-center text-gray-500 py-4 italic">No audit items added yet.</td>
                        </tr>`;
                    auditTotalEl.textContent = "₦0.00";
                    
                    // Regenerate reference
                    const newRef = "AUD" + new Date().toISOString().replace(/\D/g, "").slice(0, 14);
                    auditReferenceEl.value = newRef;
                }
            });
        }

        // Complete audit and send to admin
        if (completeAuditBtn) {
            completeAuditBtn.addEventListener("click", async function () {
                // Basic validation
                const rows = auditItemsBody.querySelectorAll("tr:not(#emptyRow)");
                const hasItems = Array.from(rows).some(r => !r.id || r.id !== "emptyRow");
                const customerName = customerInput ? customerInput.value.trim() : "";
                const reason = document.querySelector("textarea[name='reason']").value.trim();
                const discrepancyNotes = document.querySelector("textarea[name='discrepancy_notes']").value.trim();

                if (!customerName) {
                    alert("⚠ Please enter the customer name for this audit adjustment.");
                    customerInput?.focus();
                    return;
                }

                if (!hasItems) {
                    alert("⚠ You must add at least one over-stock product to audit.");
                    return;
                }

                if (!reason) {
                    alert("⚠ Please provide the reason for over-stock discrepancy.");
                    document.querySelector("textarea[name='reason']").focus();
                    return;
                }

                if (!discrepancyNotes) {
                    alert("⚠ Please provide detailed audit findings and impact analysis.");
                    document.querySelector("textarea[name='discrepancy_notes']").focus();
                    return;
                }

                // Build payload
                try {
                    const payload = {
                        reference: auditReferenceEl.value,
                        customer_name: customerName,
                        location: document.querySelector("select[name='location']").value,
                        payment_type: document.querySelector("input[name='paymentType']:checked").value,
                        reason: reason,
                        discrepancy_notes: discrepancyNotes,
                        items: Array.from(document.querySelectorAll("#auditItemsBody tr:not(#emptyRow)")).map((row) => {
                            const cells = row.children;
                            
                            const productId = parseInt(cells[0].dataset.productId);
                            const unitPriceText = cells[2].textContent.replace(/[₦,]/g, "");
                            const discountText = cells[3].textContent.replace(/[₦,]/g, "");
                            const quantityText = cells[4].textContent;
                            const totalText = cells[5].textContent.replace(/[₦,]/g, "");

                            return {
                                product_id: productId,
                                unit_price: parseFloat(unitPriceText),
                                discount: parseFloat(discountText),
                                quantity: parseFloat(quantityText),
                                total: parseFloat(totalText),
                            };
                        }),
                        total_amount: parseFloat(auditTotalEl.textContent.replace(/[₦,]/g, ""))
                    };

                    // Show loading state
                    completeAuditBtn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Sending Audit Report...';
                    completeAuditBtn.disabled = true;

                    const response = await axios.post("{{ route('auditor.send-report') }}", payload, {
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json",
                            "Content-Type": "application/json"
                        },
                        timeout: 15000
                    });

                    if (response.data.success) {
                        alert("✅ " + response.data.message);
                        // Reset form and redirect to reports
                        document.getElementById("auditForm")?.reset();
                        auditItemsBody.innerHTML = `
                            <tr id="emptyRow">
                                <td colspan="7" class="text-center text-gray-500 py-4 italic">No audit items added yet.</td>
                            </tr>`;
                        auditTotalEl.textContent = "₦0.00";
                        
                        // Generate new reference
                        const newRef = "AUD" + new Date().toISOString().replace(/\D/g, "").slice(0, 14);
                        auditReferenceEl.value = newRef;
                        
                        // Redirect to reports page
                        setTimeout(() => {
                            window.location.href = "{{ route('auditor.reports') }}";
                        }, 1000);
                    } else {
                        alert("⚠ " + response.data.message);
                    }

                } catch (error) {
                    console.error('Audit submission error:', error);
                    let userMessage = "Audit submission failed: ";
                    if (error.response?.data?.message) {
                        userMessage += error.response.data.message;
                    } else if (error.message) {
                        userMessage += error.message;
                    } else {
                        userMessage += "Unknown error occurred";
                    }
                    alert("⚠ " + userMessage);
                } finally {
                    // Reset button
                    completeAuditBtn.innerHTML = '<i data-lucide="send" class="w-4 h-4"></i> Send Audit Report to Admin';
                    completeAuditBtn.disabled = false;
                }
            });
        }

        // Back to dashboard
        if (backBtn) {
            backBtn.addEventListener("click", function() {
                const rows = auditItemsBody.querySelectorAll("tr:not(#emptyRow)");
                const hasItems = Array.from(rows).some(r => !r.id || r.id !== "emptyRow");

                let confirmMsg = "Return to dashboard?";
                if (hasItems) {
                    confirmMsg = "⚠ You have an ongoing audit.\nIf you leave now, it will be reset.\n\nProceed?";
                }

                if (confirm(confirmMsg)) {
                    window.location.href = "{{ route('dashboard') }}";
                }
            });
        }

        // Helper function
        function escapeHtml(unsafe) {
            return unsafe
                .replaceAll("&", "&amp;")
                .replaceAll("<", "&lt;")
                .replaceAll(">", "&gt;")
                .replaceAll('"', "&quot;")
                .replaceAll("'", "&#039;");
        }

        // Load over-stock products on page load
        loadOverstockProducts();
    });
</script>
@endsection