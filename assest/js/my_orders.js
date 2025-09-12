document.addEventListener('DOMContentLoaded', function () {
  const statusFilter = document.getElementById('statusFilter');
  const dateSort = document.getElementById('dateSort');
  const ordersContainer = document.getElementById('ordersContainer');

  // Save a stable copy of all order elements on load
  const originalOrders = Array.from(ordersContainer.querySelectorAll('.order-item')).map(node => node.cloneNode(true));

  function renderOrders(list) {
    ordersContainer.innerHTML = '';
    if (!list.length) {
      ordersContainer.innerHTML = '<div class="alert alert-info text-center">No orders found.</div>';
      return;
    }
    list.forEach(node => ordersContainer.appendChild(node));
  }

  function filterAndSort() {
    const rawStatus = statusFilter.value.trim().toLowerCase();
    const sortOrder = dateSort.value === 'asc' ? 'asc' : 'desc';

    // Work on clones of original elements so we always have the full source
    let filtered = originalOrders.map(n => n.cloneNode(true));

    if (rawStatus !== 'all') {
      filtered = filtered.filter(node => {
        const ds = (node.getAttribute('data-status') || '').trim().toLowerCase();
        return ds === rawStatus;
      });
    }

    filtered.sort((a, b) => {
      const da = parseInt(a.getAttribute('data-date') || '0', 10);
      const db = parseInt(b.getAttribute('data-date') || '0', 10);
      return sortOrder === 'asc' ? da - db : db - da;
    });

    renderOrders(filtered);
  }

  // Wire up events
  statusFilter.addEventListener('change', filterAndSort);
  dateSort.addEventListener('change', filterAndSort);

  // Initial render (show all)
  filterAndSort();
});