let allRows = [];

// ── Boot ──────────────────────────────────────────────────────────────────────
loadCustomers();

// ── Load table AND grand total (two separate GETs as required) ────────────────
async function loadCustomers() {
  try {
    // GET 1 – list of customers
    const resCustomers = await fetch("/computerstore/customers");
    if (!resCustomers.ok) throw new Error(`HTTP ${resCustomers.status}`);
    const customers = await resCustomers.json();

    // GET 2 – grand total
    const resTotal = await fetch("/computerstore/customers/total");
    if (!resTotal.ok) throw new Error(`HTTP ${resTotal.status}`);
    const { total } = await resTotal.json();

    renderTable(customers, total);
  } catch (err) {
    const statusEl = document.getElementById("statusMsg");
    statusEl.textContent = `❌ Failed to load customers: ${err.message}`;
    statusEl.classList.add("error");
  }
}

// ── Lookup by ID (GET 3) ──────────────────────────────────────────────────────
async function lookupById() {
  const input = document.getElementById("lookupInput").value.trim();
  const resultEl = document.getElementById("lookupResult");

  if (!input) {
    resultEl.textContent = "Please enter a customer ID.";
    resultEl.className = "lookup-result error";
    resultEl.style.display = "block";
    return;
  }

  try {
    const res = await fetch(`/computerstore/customers/${encodeURIComponent(input)}`);
    if (res.status === 404) {
      resultEl.textContent = `No customer found with ID ${input}.`;
      resultEl.className = "lookup-result error";
      resultEl.style.display = "block";
      return;
    }
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const c = await res.json();
    resultEl.innerHTML =
      `<strong>#${c.id}</strong> — ${escHtml(c.name)} &nbsp;|&nbsp; ` +
      `Age: ${c.age} &nbsp;|&nbsp; Money Spent: <strong>$${c.moneySpent.toFixed(2)}</strong>`;
    resultEl.className = "lookup-result success";
    resultEl.style.display = "block";
  } catch (err) {
    resultEl.textContent = `Error: ${err.message}`;
    resultEl.className = "lookup-result error";
    resultEl.style.display = "block";
  }
}

// ── Render ────────────────────────────────────────────────────────────────────
function renderTable(customers, total) {
  const tbody = document.getElementById("tableBody");
  tbody.innerHTML = "";
  allRows = [];

  customers.forEach((c) => {
    const tr = document.createElement("tr");
    tr.dataset.search = `${c.number} ${c.id} ${c.name} ${c.age}`.toLowerCase();

    tr.innerHTML = `
      <td class="num-cell">${c.number}</td>
      <td class="id-cell">${c.id ?? "—"}</td>
      <td class="name-cell">${escHtml(c.name)}</td>
      <td class="age-cell">${c.age}</td>
      <td>$${c.moneySpent.toFixed(2)}</td>
    `;
    tbody.appendChild(tr);
    allRows.push(tr);
  });

  document.getElementById("statusMsg").style.display = "none";
  document.getElementById("customerTable").style.display = "table";
  document.getElementById("totalBar").style.display = "flex";
  document.getElementById("grandTotal").textContent = `$${parseFloat(total).toFixed(2)}`;
  updateCount(customers.length, customers.length);
}

// ── Filter ────────────────────────────────────────────────────────────────────
function filterTable() {
  const query = document.getElementById("searchInput").value.toLowerCase().trim();
  let visible = 0;

  allRows.forEach((tr) => {
    const match = !query || tr.dataset.search.includes(query);
    tr.style.display = match ? "" : "none";
    if (match) visible++;
  });

  document.getElementById("noResults").style.display =
    visible === 0 ? "block" : "none";

  updateCount(visible, allRows.length);
}

function updateCount(visible, total) {
  document.getElementById("countBadge").textContent =
    visible === total
      ? `${total} customer${total !== 1 ? "s" : ""}`
      : `${visible} of ${total} customers`;
}

function escHtml(str) {
  return String(str)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;");
}
