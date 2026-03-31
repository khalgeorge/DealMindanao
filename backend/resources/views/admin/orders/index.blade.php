@extends('layouts.admin')

@section('content')
<header class="admin-header">
    <div>
        <h1 class="text-xl font-black text-gray-900">Orders</h1>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Process and Manage Transactions</p>
    </div>
    <div class="flex items-center gap-4">
        <button onclick="exportCSV()" class="btn-secondary btn-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Export CSV
        </button>
    </div>
</header>

<div class="admin-content">
    <!-- Search & Filters -->
    <div class="mb-8 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-96">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input type="text" id="order-search" placeholder="Search Order ID or Customer name..." class="input pl-11" value="{{ $search }}">
        </div>
        <div class="flex gap-2">
            <select id="sort-order" class="input py-2 text-xs font-bold uppercase tracking-wider w-40">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="highest">Highest Amount</option>
            </select>
            <button onclick="resetFilters()" class="btn-secondary px-4 py-2 text-[10px] font-black uppercase tracking-widest flex items-center gap-2 whitespace-nowrap">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Reset
            </button>
        </div>
    </div>

    <!-- Tabs for status -->
    <div class="mb-8 border-b border-gray-200">
        <nav class="flex gap-8">
            <button onclick="filterStatus('all')" class="order-tab active pb-4 px-1 border-b-2 font-bold text-sm transition-all">All Orders</button>
            <button onclick="filterStatus('pending')" class="order-tab pb-4 px-1 border-b-2 border-transparent font-bold text-sm text-gray-400 hover:text-gray-600 transition-all">Pending</button>
            <button onclick="filterStatus('processing')" class="order-tab pb-4 px-1 border-b-2 border-transparent font-bold text-sm text-gray-400 hover:text-gray-600 transition-all">Processing</button>
            <button onclick="filterStatus('completed')" class="order-tab pb-4 px-1 border-b-2 border-transparent font-bold text-sm text-gray-400 hover:text-gray-600 transition-all">Completed</button>
        </nav>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Order ID</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Customer</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Total</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Method</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody id="order-list-body">
                    @forelse($orders as $order)
                        @php
                            $total = $order->items->sum(function ($item) {
                                return $item->price * $item->quantity;
                            });
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors" data-order-id="{{ $order->id }}">
                            <td class="px-6 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">#{{ $order->id }}</td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-gray-900">{{ $order->customer_name }}</p>
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">{{ $order->email }}</p>
                            </td>
                            <td class="px-6 py-5 text-sm font-bold text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-5 font-black text-gray-900">₱{{ number_format($total, 2) }}</td>
                            <td class="px-6 py-5"><span class="text-[10px] font-bold uppercase py-1 px-2 bg-gray-100 rounded-lg text-gray-600">{{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}</span></td>
                            <td class="px-6 py-5">
                                <span class="badge-{{ strtolower($order->status) }} text-[10px]">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <button onclick="manageOrder({{ $order->id }})" class="text-brand-600 font-bold text-xs uppercase hover:underline">Manage</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-400 font-bold italic">No orders found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-6 border-t border-gray-50 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span id="pagination-info" class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                    Showing {{ $orders->firstItem() ?? 0 }}-{{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} orders
                </span>
                <div class="h-4 w-px bg-gray-200"></div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Rows:</span>
                    <select id="rows-per-page" class="bg-transparent border-none text-xs font-black text-gray-900 focus:ring-0 cursor-pointer">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-2">
                @if($orders->previousPageUrl())
                    <a href="{{ $orders->previousPageUrl() }}" class="btn-secondary btn-sm">Previous</a>
                @else
                    <button disabled class="btn-secondary btn-sm opacity-50 cursor-not-allowed">Previous</button>
                @endif
                
                @if($orders->nextPageUrl())
                    <a href="{{ $orders->nextPageUrl() }}" class="btn-secondary btn-sm">Next</a>
                @else
                    <button disabled class="btn-secondary btn-sm opacity-50 cursor-not-allowed">Next</button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('modals')
<!-- Order Details Modal -->
<div id="order-modal" class="fixed inset-0 z-[999] hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeOrderModal()"></div>
    
    <div class="fixed inset-0 pointer-events-none flex items-center justify-center p-4">
        <div class="pointer-events-auto bg-white w-full max-w-2xl rounded-xl shadow-2xl overflow-hidden transform transition-all flex flex-col max-h-[90vh] border border-gray-200">
            
            <!-- Header -->
            <div class="relative bg-gray-50 border-b border-gray-100 p-8 flex-shrink-0">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 id="modal-order-id" class="text-2xl font-black text-gray-900 uppercase tracking-tighter mb-1">Order Details</h2>
                        <div class="flex items-center gap-3">
                            <span id="modal-order-date" class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Loading...</span>
                            <div id="modal-order-status" class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border">
                                Pending
                            </div>
                        </div>
                    </div>
                    <button onclick="closeOrderModal()" class="p-2 text-gray-400 hover:text-gray-900 transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="px-8 py-10 overflow-y-auto custom-scrollbar space-y-10">
                
                <div class="grid grid-cols-2 gap-10">
                    <section>
                        <h3 class="text-[11px] font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span class="w-1 h-4 bg-brand-600 rounded-full"></span>
                            Customer Information
                        </h3>
                        <div class="p-6 rounded-xl bg-gray-50 border border-gray-100 space-y-4">
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Name</p>
                                <p id="modal-customer-name" class="text-sm font-black text-gray-900">Loading...</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Email</p>
                                <p id="modal-customer-email" class="text-sm font-black text-gray-900">Loading...</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Payment Method</p>
                                <p id="modal-payment-method" class="text-sm font-black text-gray-900">Loading...</p>
                            </div>
                        </div>
                    </section>

                    <section>
                        <h3 class="text-[11px] font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span class="w-1 h-4 bg-brand-600 rounded-full"></span>
                            Management
                        </h3>
                        <div class="p-6 rounded-xl bg-gray-50 border border-gray-100 space-y-4">
                            <div>
                                <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-2">Update Status</label>
                                <select id="modal-update-status" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none text-gray-900 text-xs font-bold appearance-none">
                                    <option value="pending">Pending Review</option>
                                    <option value="processing">Processing</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-2">Admin Notes</label>
                                <textarea id="modal-admin-notes" rows="3" placeholder="Internal notes about this order..."
                                          class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none text-gray-700 text-xs resize-none"></textarea>
                            </div>
                            <button onclick="updateOrderStatus()" class="w-full py-2.5 bg-brand-600 text-white rounded-lg font-black text-[10px] uppercase tracking-widest shadow-lg shadow-brand-100 hover:bg-brand-700 transition-all">
                                Save Changes
                            </button>
                        </div>
                    </section>
                </div>

                <section>
                    <h3 class="text-[11px] font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-1 h-4 bg-brand-600 rounded-full"></span>
                        Order Items1
                    </h3>
                    <div class="rounded-xl border border-gray-100 overflow-hidden">
                        <table class="w-full text-left bg-white">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Item</th>
                                    <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Price</th>
                                    <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Qty</th>
                                    <th class="px-6 py-3 text-right text-[9px] font-black text-gray-400 uppercase tracking-widest">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="modal-order-items">
                                <!-- Populated by JavaScript -->
                            </tbody>
                            <tfoot class="bg-gray-50 border-t border-gray-100 font-black">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right text-[10px] uppercase tracking-widest">Total Amount</td>
                                    <td id="modal-total-amount" class="px-6 py-4 text-right text-sm text-gray-900">₱0.00</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </section>

            </div>

            <!-- Footer -->
            <div class="p-8 bg-gray-50 border-t border-gray-100 flex gap-4 flex-shrink-0">
                <button onclick="closeOrderModal()" class="px-8 py-4 bg-white text-gray-600 rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-gray-100 border border-gray-200 transition-all flex-1">
                    Close
                </button>
                <button onclick="openPartnerSheet()" class="flex-1 py-4 px-8 bg-gray-700 text-white rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-gray-900 transition-all">
                    Partner Sheet
                </button>
                <button onclick="printInvoice()" class="flex-[2] py-4 px-8 bg-brand-600 text-white rounded-lg font-black text-[10px] uppercase tracking-widest shadow-lg shadow-brand-100 hover:bg-brand-700 transition-all">
                    Print Invoice
                </button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('styles')
<style>
.order-tab.active {
    border-color: #f7941d; /* brand-600 */
    color: #f7941d;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f3f4f6;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}
</style>
@endpush

@push('scripts')
<script>
// Embed Laravel data as JavaScript
const ordersData = @json($orders->items());
let currentOrderId = null;

function formatPrice(amount) {
    return '₱' + parseFloat(amount).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function filterStatus(status) {
    // Update UI
    document.querySelectorAll('.order-tab').forEach(tab => {
        const tabText = tab.innerText.replace(' Orders', '').trim().toLowerCase();
        if (tabText === status.toLowerCase()) {
            tab.classList.add('active');
            tab.classList.remove('text-gray-400', 'border-transparent');
        } else {
            tab.classList.remove('active');
            tab.classList.add('text-gray-400', 'border-transparent');
        }
    });

    // Redirect with filter
    const url = new URL(window.location.href);
    if (status === 'all') {
        url.searchParams.delete('status');
    } else {
        url.searchParams.set('status', status);
    }
    window.location.href = url.toString();
}

function resetFilters() {
    window.location.href = '{{ route("admin.orders.index") }}';
}

async function manageOrder(orderId) {
    currentOrderId = orderId;
    
    try {
        // Use web route instead of API route to leverage session auth
        const response = await fetch(`/admin/orders/${orderId}/json`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });
        
        if (!response.ok) throw new Error('Failed to fetch order');
        
        const order = await response.json();
        
        // Populate modal
        document.getElementById('modal-order-id').innerText = `Order #${order.id}`;
        document.getElementById('modal-order-date').innerText = new Date(order.created_at).toLocaleDateString('en-PH', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
        document.getElementById('modal-customer-name').innerText = order.customer_name;
        document.getElementById('modal-customer-email').innerText = order.email;
        document.getElementById('modal-payment-method').innerText = order.payment_method.replace(/_/g, ' ').toUpperCase();
        document.getElementById('modal-update-status').value = order.status;
        document.getElementById('modal-admin-notes').value = order.notes ?? '';

        const statusBadge = document.getElementById('modal-order-status');
        statusBadge.innerText = order.status.charAt(0).toUpperCase() + order.status.slice(1);
        statusBadge.className = `px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border badge-${order.status.toLowerCase()}`;

        // Calculate and display total
        const total = order.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        document.getElementById('modal-total-amount').innerText = formatPrice(total);

        // Render order items
        const itemBody = document.getElementById('modal-order-items');
        itemBody.innerHTML = order.items.map(item => `
            <tr class="border-b border-gray-50">
                <td class="px-6 py-4">
                    <p class="text-xs font-bold text-gray-900">${item.product_name || item.product?.name || 'Product'}</p>
                    ${item.product?.model_code ? `<p class="text-[9px] font-bold uppercase tracking-wider mt-0.5" style="color:#9ca3af;"><span style="color:#6b7280;">MODEL:</span> ${item.product.model_code}</p>` : ''}
                    ${item.variant ? `<p class="text-[9px] font-bold uppercase tracking-wider mt-0.5" style="color:#6b7280;"><span style="color:#059669;">VARIANT:</span> ${item.variant}</p>` : ''}
                </td>
                <td class="px-6 py-4 text-xs font-bold text-gray-500">${formatPrice(item.price)}</td>
                <td class="px-6 py-4 text-xs font-bold text-gray-500">${item.quantity}</td>
                <td class="px-6 py-4 text-right text-xs font-black text-gray-900">${formatPrice(item.price * item.quantity)}</td>
            </tr>
        `).join('');

        // Show modal
        document.getElementById('order-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    } catch (error) {
        console.error('Error loading order:', error);
        alert('Failed to load order details');
    }
}

function closeOrderModal() {
    document.getElementById('order-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentOrderId = null;
}

async function updateOrderStatus() {
    if (!currentOrderId) return;
    
    const newStatus = document.getElementById('modal-update-status').value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    
    try {
        const response = await fetch(`/admin/orders/${currentOrderId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: newStatus, notes: document.getElementById('modal-admin-notes').value })
        });
        
        if (!response.ok) throw new Error('Failed to update status');
        
        alert(`Order #${currentOrderId} status updated to ${newStatus}`);
        closeOrderModal();
        window.location.reload();
    } catch (error) {
        console.error('Error updating order:', error);
        alert('Failed to update order status');
    }
}

function openPartnerSheet() {
    if (!currentOrderId) return;
    window.open(`/admin/orders/${currentOrderId}/partner-sheet`, '_blank');
}

function printInvoice() {
    if (!currentOrderId) return;
    
    const orderData = ordersData.find(o => o.id == currentOrderId);
    if (!orderData) {
        alert('Order data not found');
        return;
    }

    const currentDate = new Date().toLocaleDateString('en-PH', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });

    // Calculate total
    const total = orderData.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    // Create print window
    const printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Invoice #${orderData.id}</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: Arial, sans-serif; padding: 40px; color: #333; font-size: 12px; }
                .invoice-header { display: flex; justify-content: space-between; margin-bottom: 40px; padding-bottom: 20px; border-bottom: 3px solid #16a34a; }
                .company-info h1 { font-size: 28px; font-weight: bold; color: #16a34a; margin-bottom: 5px; }
                .company-info p { font-size: 11px; color: #666; line-height: 1.6; }
                .invoice-info { text-align: right; }
                .invoice-info h2 { font-size: 16px; font-weight: bold; margin-bottom: 10px; }
                .invoice-info p { font-size: 11px; margin-bottom: 5px; }
                table { width: 100%; border-collapse: collapse; margin: 30px 0; }
                th { background: #f3f4f6; padding: 12px; text-align: left; font-size: 10px; font-weight: bold; text-transform: uppercase; }
                td { padding: 15px 12px; border-bottom: 1px solid #f3f4f6; font-size: 12px; }
                .total-section { margin-top: 20px; padding-top: 20px; border-top: 2px solid #e5e7eb; }
                .total-row { display: flex; justify-content: flex-end; padding: 8px 0; }
                .total-label { width: 150px; text-align: right; padding-right: 20px; }
                .total-value { width: 120px; text-align: right; font-weight: 600; }
                .footer { margin-top: 60px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-align: center; color: #999; font-size: 10px; }
            </style>
        </head>
        <body>
            <div class="invoice-header">
                <div class="company-info">
                    <h1>DealMindanao</h1>
                    <p>Regional Marketplace for Mindanao</p>
                    <p>Email: hello@dealmindanao.ph</p>
                </div>
                <div class="invoice-info">
                    <h2>INVOICE</h2>
                    <p><strong>Invoice #:</strong> ${orderData.id}</p>
                    <p><strong>Date:</strong> ${new Date(orderData.created_at).toLocaleDateString('en-PH')}</p>
                    <p><strong>Printed:</strong> ${currentDate}</p>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th style="text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    ${orderData.items.map(item => `
                        <tr>
                            <td>
                                ${item.product_name || 'Product'}
                                ${item.variant ? '<br><span style="font-size:10px;color:#059669;font-weight:700;text-transform:uppercase;letter-spacing:.05em;">' + item.variant + '</span>' : ''}
                            </td>
                            <td>${formatPrice(item.price)}</td>
                            <td>${item.quantity}</td>
                            <td style="text-align: right;">${formatPrice(item.price * item.quantity)}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
            <div class="total-section">
                <div class="total-row">
                    <div class="total-label">TOTAL:</div>
                    <div class="total-value">${formatPrice(total)}</div>
                </div>
            </div>
            <div class="footer">
                <p>Thank you for your business with DealMindanao!</p>
            </div>
        </body>
        </html>
    `);
    
    printWindow.document.close();
    setTimeout(() => printWindow.print(), 250);
}

function exportCSV() {
    if (ordersData.length === 0) {
        alert('No orders to export');
        return;
    }

    const headers = ['Order ID', 'Customer', 'Email', 'Date', 'Amount', 'Payment Method', 'Status'];
    const rows = ordersData.map(order => {
        const total = order.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        return [
            `#${order.id}`,
            order.customer_name,
            order.email,
            new Date(order.created_at).toLocaleDateString('en-PH'),
            formatPrice(total),
            order.payment_method.replace(/_/g, ' ').toUpperCase(),
            order.status.charAt(0).toUpperCase() + order.status.slice(1)
        ];
    });

    const csvContent = [headers.join(','), ...rows.map(row => row.map(cell => `"${cell}"`).join(','))].join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    const timestamp = new Date().toISOString().split('T')[0];
    link.setAttribute('href', url);
    link.setAttribute('download', `dealmindanao_orders_${timestamp}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    alert(`Exported ${ordersData.length} orders to CSV`);
}

// ESC key to close modal
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeOrderModal();
    }
});

// Initialize active tab based on current URL parameter
(function initializeActiveTab() {
    const urlParams = new URLSearchParams(window.location.search);
    const currentStatus = urlParams.get('status') || 'all';
    
    document.querySelectorAll('.order-tab').forEach(tab => {
        const tabText = tab.innerText.replace(' Orders', '').trim().toLowerCase();
        if (tabText === currentStatus.toLowerCase()) {
            tab.classList.add('active');
            tab.classList.remove('text-gray-400', 'border-transparent');
        } else {
            tab.classList.remove('active');
            tab.classList.add('text-gray-400', 'border-transparent');
        }
    });
})();

// Client-side search filtering
document.getElementById('order-search')?.addEventListener('input', (e) => {
    const searchTerm = e.target.value.toLowerCase().trim();
    const rows = document.querySelectorAll('#order-list-body tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        if (row.querySelector('td[colspan]')) return; // Skip "no results" row
        
        const orderId = row.querySelector('td:nth-child(1)')?.textContent.toLowerCase() || '';
        const customerName = row.querySelector('td:nth-child(2) p:first-child')?.textContent.toLowerCase() || '';
        const customerEmail = row.querySelector('td:nth-child(2) p:last-child')?.textContent.toLowerCase() || '';
        
        const matches = orderId.includes(searchTerm) || 
                       customerName.includes(searchTerm) || 
                       customerEmail.includes(searchTerm);
        
        if (matches || searchTerm === '') {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Update pagination info
    const paginationInfo = document.getElementById('pagination-info');
    if (paginationInfo) {
        const totalOrders = rows.length;
        paginationInfo.textContent = `Showing ${visibleCount} of ${totalOrders} orders${searchTerm ? ' (filtered)' : ''}`;
    }
});

// Sort functionality (currently disabled - would need backend implementation)
document.getElementById('sort-order')?.addEventListener('change', (e) => {
    // TODO: implement sort
});
</script>
@endpush
