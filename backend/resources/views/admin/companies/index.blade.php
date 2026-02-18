@extends('layouts.admin')

@section('content')
<header class="admin-header">
  <div>
    <h1 class="text-xl font-black text-gray-900">Partner Companies</h1>
    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Manage Mindanao Vendors</p>
  </div>
  <button onclick="window.openPartnerModal()" class="btn-primary flex items-center gap-2 px-6">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
    Add Partner
  </button>
</header>

<div class="admin-content">
  <!-- Search & Filter Area -->
  <div class="mb-8 flex flex-col md:flex-row gap-4 items-center justify-between">
    <div class="flex items-center gap-4 w-full md:w-auto flex-1">
      <div class="relative flex-1 md:max-w-96">
        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </span>
        <input type="text" id="company-search" placeholder="Search vendors..." class="input pl-11 py-3">
      </div>
      <button onclick="window.resetFilters()" class="btn-secondary px-4 py-3 text-[10px] font-black uppercase tracking-widest flex items-center gap-2 whitespace-nowrap">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
        Reset
      </button>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-3 gap-6" id="company-grid">
    <!-- Skeletons -->
    <div class="bg-white rounded-xl border border-gray-100 animate-pulse overflow-hidden p-6 shadow-sm">
      <div class="flex items-start gap-4">
        <div class="w-14 h-14 bg-gray-50 rounded-lg"></div>
        <div class="flex-1 space-y-3">
          <div class="h-4 bg-gray-100 rounded-lg w-3/4"></div>
          <div class="h-3 bg-gray-50 rounded-lg w-1/2"></div>
        </div>
      </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 animate-pulse overflow-hidden p-6 shadow-sm hidden md:block">
      <div class="flex items-start gap-4">
        <div class="w-14 h-14 bg-gray-50 rounded-lg"></div>
        <div class="flex-1 space-y-3">
          <div class="h-4 bg-gray-100 rounded-lg w-3/4"></div>
          <div class="h-3 bg-gray-50 rounded-lg w-1/2"></div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('modals')
<!-- Partner Modal -->
<div id="partner-modal" class="fixed inset-0 z-[999] hidden" role="dialog" aria-modal="true">
  <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closePartnerModal()"></div>
  
  <div class="fixed inset-0 pointer-events-none flex items-center justify-center p-4">
    <div class="pointer-events-auto bg-white w-full max-w-md rounded-xl shadow-2xl overflow-hidden transform transition-all border border-gray-200">
      <form id="partner-form" class="flex flex-col">
        <input type="hidden" name="id" id="partner-id">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="p-8">
          <div class="flex justify-between items-center mb-10">
            <div>
              <h3 id="partner-modal-title" class="text-xl font-black text-gray-900 uppercase tracking-tighter">New Partner</h3>
              <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mt-1">Onboard Mindanao Vendor</p>
            </div>
            <button type="button" onclick="closePartnerModal()" class="p-2 text-gray-400 hover:text-gray-900 transition-all">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
          </div>

          <div class="space-y-6">
            <div>
              <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Company Name</label>
              <input type="text" id="partner-name" name="name" required placeholder="Davao Fruit Corp" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-lg focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none text-gray-900 text-sm font-bold">
            </div>

            <div>
              <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Location (City, Province)</label>
              <input type="text" id="partner-location" name="location" required placeholder="Davao City, Davao del Sur" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-lg focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none text-gray-900 text-sm font-bold">
              <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1.5">Format: City, Province</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Contact Email</label>
                <input type="email" id="partner-email" name="email" placeholder="contact@company.com" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-lg focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none text-gray-900 text-sm font-bold">
              </div>
              <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Contact Phone</label>
                <input type="tel" id="partner-phone" name="phone" placeholder="+63 912 345 6789" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-lg focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none text-gray-900 text-sm font-bold">
              </div>
            </div>

            <div>
              <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Description (Optional)</label>
              <textarea id="partner-description" name="description" rows="3" placeholder="Brief description of the company..." class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-lg focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none text-gray-900 text-sm font-bold resize-none"></textarea>
            </div>

            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-100">
              <input type="checkbox" id="partner-is-active" name="is_active" value="1" checked class="w-4 h-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
              <label for="partner-is-active" class="text-xs font-black text-gray-700 uppercase tracking-wide cursor-pointer">
                Active Status
                <span class="block text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Vendor can be assigned to products</span>
              </label>
            </div>
          </div>
        </div>
        
        <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex gap-3">
          <button type="button" onclick="closePartnerModal()" class="flex-1 py-4 bg-white border border-gray-200 text-gray-600 rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-gray-50 transition-all">
            Cancel
          </button>
          <button type="submit" id="partner-submit-btn" class="flex-[2] py-4 bg-brand-600 text-white rounded-lg font-black text-[10px] uppercase tracking-widest shadow-lg shadow-brand-100 hover:bg-brand-700 transition-all">
            Register Partner
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Profile Modal -->
<div id="profile-modal" class="fixed inset-0 z-[999] hidden" role="dialog" aria-modal="true">
  <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeProfileModal()"></div>
  
  <div class="fixed inset-0 pointer-events-none flex items-center justify-center p-4">
    <div class="pointer-events-auto bg-white w-full max-w-2xl rounded-xl shadow-2xl overflow-hidden transform transition-all flex flex-col max-h-[90vh] border border-gray-200">
      
      <!-- Header -->
      <div class="relative bg-gray-50 border-b border-gray-100 p-8 flex-shrink-0">
        <div class="flex items-center gap-6">
          <div id="profile-logo" class="w-14 h-14 bg-white rounded-lg shadow-sm border border-gray-200 flex items-center justify-center text-lg font-black text-brand-600">
            DF
          </div>
          <div class="flex-1">
            <div class="flex items-center gap-3 mb-2">
              <h2 id="profile-name" class="text-2xl font-black text-gray-900 uppercase tracking-tighter">Davao Fruit Corp</h2>
              <span id="profile-status-badge" class="inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest"></span>
            </div>
            <div class="flex items-center gap-2 text-gray-400 font-black text-[10px] uppercase tracking-widest leading-none">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
              <span id="profile-location">Davao City, Philippines</span>
            </div>
          </div>
        </div>
        
        <button onclick="closeProfileModal()" class="absolute top-8 right-8 p-2 text-gray-400 hover:text-gray-900 transition-all">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
      </div>

      <!-- Content -->
      <div class="px-8 py-10 overflow-y-auto custom-scrollbar space-y-10">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
          <section>
            <h3 class="text-[11px] font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center gap-2">
              <span class="w-1 h-4 bg-brand-600 rounded-full"></span>
              Company Profile
            </h3>
            <div class="p-6 rounded-xl bg-gray-50 border border-gray-100">
              <p id="profile-desc" class="text-sm text-gray-600 leading-relaxed font-medium">
                Leading producer and exporter of high-quality tropical fruits.
              </p>
            </div>
          </section>

          <section>
            <h3 class="text-[11px] font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center gap-2">
              <span class="w-1 h-4 bg-brand-600 rounded-full"></span>
              Contact Channel
            </h3>
            <div class="space-y-4">
              <div class="flex items-center gap-4 p-4 rounded-xl border border-gray-100 bg-white shadow-sm">
                <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                  <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Email</p>
                  <p id="profile-email" class="text-sm font-black text-gray-900">contact@davaofruit.com</p>
                </div>
              </div>
              <div class="flex items-center gap-4 p-4 rounded-xl border border-gray-100 bg-white shadow-sm">
                <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                </div>
                <div>
                  <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Phone</p>
                  <p id="profile-phone" class="text-sm font-black text-gray-900">+63 912 345 6789</p>
                </div>
              </div>
            </div>
          </section>
        </div>

        <section class="pt-8 border-t border-gray-100">
          <h3 class="text-[11px] font-black text-gray-900 uppercase tracking-widest mb-4">Management</h3>
          <div id="profile-management-section" class="p-6 rounded-xl border flex items-center justify-between">
            <div id="profile-management-text">
              <h4 class="text-sm font-black mb-1">Account Status</h4>
              <p class="text-xs font-bold uppercase tracking-wide">Manage vendor store access</p>
            </div>
            <button id="profile-toggle-btn" onclick="window.toggleFromProfile()" class="px-6 py-2.5 bg-white border rounded-lg font-black text-[10px] uppercase tracking-widest transition-all shadow-sm">
              Toggle Status
            </button>
          </div>
        </section>

      </div>

      <!-- Footer -->
      <div class="p-8 bg-gray-50 border-t border-gray-100 flex gap-4 flex-shrink-0">
        <button onclick="closeProfileModal()" class="px-8 py-4 bg-white text-gray-600 rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-gray-100 border border-gray-200 transition-all flex-1">
          Dismiss
        </button>
        <button class="flex-[2] py-4 px-8 bg-brand-600 text-white rounded-lg font-black text-[10px] uppercase tracking-widest shadow-lg shadow-brand-100 hover:bg-brand-700 transition-all">
          Send Message
        </button>
      </div>
    </div>
  </div>
</div>
@endpush

@push('scripts')
<script>
let currentSearch = '';
let companiesData = @json($companies->items());

// Load companies from Laravel data
function renderCompanies() {
  const grid = document.getElementById('company-grid');
  if (!grid) return;
  
  let filtered = companiesData;

  // Filter by Search
  if (currentSearch) {
    filtered = filtered.filter(p => {
      const location = [p.city, p.province].filter(Boolean).join(', ');
      return p.name?.toLowerCase().includes(currentSearch) || 
             location.toLowerCase().includes(currentSearch);
    });
  }

  if (filtered.length === 0) {
    grid.innerHTML = `
      <div class="col-span-full py-20 text-center">
        <p class="text-gray-400 font-black text-xs uppercase tracking-widest">No vendors found matching your criteria</p>
      </div>
    `;
    return;
  }

  grid.innerHTML = filtered.map(p => {
    const location = [p.city, p.province].filter(Boolean).join(', ') || 'N/A';
    const initials = p.name?.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase() || '?';
    
    return `
      <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-brand-300 transition-all duration-300 group relative flex flex-col overflow-hidden ${!p.is_active ? 'opacity-60' : ''}">
        ${!p.is_active ? '<div class="absolute top-3 right-3 z-10"><span class="inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-amber-100 text-amber-700 border border-amber-200">Suspended</span></div>' : ''}
        <div class="p-6">
          <div class="flex items-start gap-5 mb-6">
            <div class="w-14 h-14 shrink-0 bg-brand-50 flex items-center justify-center rounded-xl border border-brand-100 font-black text-brand-600 text-lg group-hover:bg-brand-600 group-hover:text-white transition-all overflow-hidden">
              ${initials}
            </div>
            
            <div class="flex-1 min-w-0">
              <h3 class="font-black text-gray-900 text-base lg:text-lg uppercase tracking-tight truncate group-hover:text-brand-600 transition-colors mb-1">${p.name || 'Unnamed Company'}</h3>
              <div class="flex items-center gap-1.5 text-gray-400 font-bold text-[10px] uppercase tracking-widest">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                ${location}
              </div>
            </div>
          </div>

          <div class="space-y-4">
            ${p.contact_email || p.contact_phone ? `
            <div class="space-y-2 text-xs text-gray-600">
              ${p.contact_email ? `
              <div class="flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                <span class="truncate">${p.contact_email}</span>
              </div>
              ` : ''}
              ${p.contact_phone ? `
              <div class="flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                <span>${p.contact_phone}</span>
              </div>
              ` : ''}
            </div>
            ` : ''}

            <div class="flex items-center gap-2 pt-2">
              <button onclick="window.viewCompanyProfile(${p.id})" class="flex-1 text-[10px] font-black text-gray-900 uppercase tracking-widest px-4 py-3 bg-white rounded-lg hover:bg-brand-600 hover:text-white transition-all border border-gray-200 shadow-sm flex items-center justify-center gap-2">
                View Profile
              </button>
              
              <div class="flex items-center gap-1">
                <button onclick="window.toggleCompanyStatus(${p.id}, ${p.is_active ? 'true' : 'false'})" title="${p.is_active ? 'Suspend' : 'Activate'} Company" class="p-3 ${p.is_active ? 'text-green-600 hover:text-amber-600' : 'text-gray-400 hover:text-green-600'} hover:bg-white rounded-lg border border-transparent hover:border-gray-200 transition-all">
                  ${p.is_active ? 
                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' : 
                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                  }
                </button>
                <button onclick="window.quickEdit(${p.id})" title="Edit Company" class="p-3 text-gray-400 hover:text-brand-600 hover:bg-white rounded-lg border border-transparent hover:border-gray-200 transition-all">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </button>
                <button onclick="window.deleteCompany(${p.id})" title="Delete Company" class="p-3 text-gray-400 hover:text-red-600 hover:bg-white rounded-lg border border-transparent hover:border-gray-200 transition-all">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    `;
  }).join('');
}

window.openPartnerModal = () => {
  const form = document.getElementById('partner-form');
  if (form) form.reset();
  document.getElementById('partner-id').value = '';
  document.getElementById('partner-modal-title').innerText = 'New Partner';
  document.getElementById('partner-submit-btn').innerText = 'Register Partner';

  document.getElementById('partner-modal').classList.remove('hidden');
  document.body.classList.add('overflow-hidden');
};

window.closePartnerModal = () => {
  document.getElementById('partner-modal').classList.add('hidden');
  document.body.classList.remove('overflow-hidden');
};

window.quickEdit = (id) => {
  const p = companiesData.find(comp => comp.id == id);
  if (!p) return;

  document.getElementById('partner-id').value = p.id;
  document.getElementById('partner-name').value = p.name || '';
  
  const location = [p.city, p.province].filter(Boolean).join(', ');
  document.getElementById('partner-location').value = location;
  
  document.getElementById('partner-email').value = p.contact_email || '';
  document.getElementById('partner-phone').value = p.contact_phone || '';
  document.getElementById('partner-description').value = p.description || '';
  document.getElementById('partner-is-active').checked = p.is_active !== false;
  
  document.getElementById('partner-modal-title').innerText = 'Edit Partner';
  document.getElementById('partner-submit-btn').innerText = 'Update Partner';
  document.getElementById('partner-modal').classList.remove('hidden');
  document.body.classList.add('overflow-hidden');
};

window.resetFilters = () => {
  document.getElementById('company-search').value = '';
  currentSearch = '';
  renderCompanies();
};

window.toggleCompanyStatus = async (id, currentStatus) => {
  try {
    const response = await fetch(`/admin/companies/${id}/toggle-status`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      }
    });

    if (response.ok) {
      const result = await response.json();
      
      // Update the company in the data array
      const company = companiesData.find(c => c.id == id);
      if (company) {
        company.is_active = result.is_active;
      }
      
      // Re-render to show updated status
      renderCompanies();
      
      showToast(result.message || 'Company status updated!', 'success');
    } else {
      showToast('Failed to update company status.', 'error');
    }
  } catch (error) {
    console.error('Toggle error:', error);
    showToast('An error occurred. Please try again.', 'error');
  }
};

window.viewCompanyProfile = (id) => {
  const p = companiesData.find(comp => comp.id == id);
  if (!p) return;

  const initials = p.name?.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase() || '?';
  const location = [p.city, p.province].filter(Boolean).join(', ') || 'N/A';

  document.getElementById('profile-logo').textContent = initials;
  document.getElementById('profile-name').textContent = p.name || 'Unnamed Company';
  document.getElementById('profile-location').textContent = location + ', Philippines';
  document.getElementById('profile-desc').textContent = p.description || 'No description provided.';
  document.getElementById('profile-email').textContent = p.contact_email || 'N/A';
  document.getElementById('profile-phone').textContent = p.contact_phone || 'N/A';

  // Update status badge
  const badge = document.getElementById('profile-status-badge');
  if (p.is_active) {
    badge.className = 'inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-green-100 text-green-700 border border-green-200';
    badge.textContent = 'Active';
  } else {
    badge.className = 'inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-amber-100 text-amber-700 border border-amber-200';
    badge.textContent = 'Suspended';
  }

  // Update management section
  const managementSection = document.getElementById('profile-management-section');
  const managementText = document.getElementById('profile-management-text');
  const toggleBtn = document.getElementById('profile-toggle-btn');
  
  managementSection.dataset.companyId = p.id;
  
  if (p.is_active) {
    managementSection.className = 'p-6 rounded-xl border bg-green-50 border-green-100 flex items-center justify-between';
    managementText.innerHTML = `
      <h4 class="text-sm font-black text-green-900 mb-1">Account Active</h4>
      <p class="text-xs text-green-600/80 font-bold uppercase tracking-wide">Vendor has full store access</p>
    `;
    toggleBtn.className = 'px-6 py-2.5 bg-white border border-amber-200 text-amber-600 rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-amber-600 hover:text-white transition-all shadow-sm';
    toggleBtn.textContent = 'Suspend';
  } else {
    managementSection.className = 'p-6 rounded-xl border bg-amber-50 border-amber-100 flex items-center justify-between';
    managementText.innerHTML = `
      <h4 class="text-sm font-black text-amber-900 mb-1">Account Suspended</h4>
      <p class="text-xs text-amber-600/80 font-bold uppercase tracking-wide">Vendor store access disabled</p>
    `;
    toggleBtn.className = 'px-6 py-2.5 bg-white border border-green-200 text-green-600 rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-green-600 hover:text-white transition-all shadow-sm';
    toggleBtn.textContent = 'Activate';
  }

  document.getElementById('profile-modal').classList.remove('hidden');
  document.body.classList.add('overflow-hidden');
};

window.toggleFromProfile = async () => {
  const managementSection = document.getElementById('profile-management-section');
  const companyId = managementSection.dataset.companyId;
  const company = companiesData.find(c => c.id == companyId);
  
  if (!company) return;
  
  await window.toggleCompanyStatus(companyId, company.is_active);
  
  // Update profile modal display
  window.viewCompanyProfile(companyId);
};

window.closeProfileModal = () => {
  document.getElementById('profile-modal').classList.add('hidden');
  document.body.classList.remove('overflow-hidden');
};

window.deleteCompany = async (id) => {
  if (!confirm('Are you sure you want to delete this company? This action cannot be undone.')) {
    return;
  }

  try {
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'DELETE');

    const response = await fetch(`/admin/companies/${id}`, {
      method: 'POST',
      body: formData
    });

    if (response.ok) {
      companiesData = companiesData.filter(c => c.id !== id);
      renderCompanies();
      showToast('Company deleted successfully!');
    } else {
      throw new Error('Delete failed');
    }
  } catch (error) {
    console.error('Failed to delete company:', error);
    showToast('Failed to delete company. It may have associated products.', 'error');
  }
};

document.getElementById('partner-form').addEventListener('submit', async (e) => {
  e.preventDefault();
  const formData = new FormData(e.target);
  const pid = document.getElementById('partner-id').value;
  
  const location = formData.get('location') || '';
  const [city, province] = location.split(',').map(s => s.trim());

  const data = {
    name: formData.get('name'),
    city: city || null,
    province: province || null,
    contact_email: formData.get('email') || null,
    contact_phone: formData.get('phone') || null,
    description: formData.get('description') || null,
    is_active: formData.get('is_active') ? 1 : 0,
    _token: formData.get('_token')
  };

  try {
    let response;
    if (pid) {
      data._method = 'PUT';
      response = await fetch(`/admin/companies/${pid}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify(data)
      });
    } else {
      response = await fetch('/admin/companies', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify(data)
      });
    }

    if (response.ok) {
      const result = await response.json();
      
      if (pid) {
        const index = companiesData.findIndex(c => c.id == pid);
        if (index !== -1) {
          companiesData[index] = result.company || result;
        }
        showToast('Company updated successfully!');
      } else {
        companiesData.push(result.company || result);
        showToast('Company created successfully!');
      }

      renderCompanies();
      closePartnerModal();
    } else {
      throw new Error('Save failed');
    }
  } catch (error) {
    console.error('Failed to save company:', error);
    showToast('Failed to save company. Please try again.', 'error');
  }
});

function showToast(message, type = 'success') {
  const toast = document.createElement('div');
  toast.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-[9999] ${
    type === 'error' ? 'bg-red-600' : 'bg-green-600'
  } text-white font-bold text-sm`;
  toast.textContent = message;
  document.body.appendChild(toast);
  
  setTimeout(() => {
    toast.remove();
  }, 3000);
}

// Initial render
renderCompanies();

// Attach search event listener after render
const companySearchInput = document.getElementById('company-search');
if (companySearchInput) {
  companySearchInput.addEventListener('input', (e) => {
    currentSearch = e.target.value.toLowerCase();
    renderCompanies();
  });
}

// Attach keyboard event listener
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    window.closePartnerModal();
    window.closeProfileModal();
  }
});
</script>
@endpush
