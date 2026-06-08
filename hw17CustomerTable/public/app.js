// ─────────────────────────────────────────────────────────────────────────────
// State
// ─────────────────────────────────────────────────────────────────────────────
let allRows   = [];          // all <tr> elements currently in the table
let editingId = null;        // numeric id of the customer being edited (null = adding)

// ─────────────────────────────────────────────────────────────────────────────
// Boot
// ─────────────────────────────────────────────────────────────────────────────
loadCustomers();

// ─────────────────────────────────────────────────────────────────────────────
// GET /computerstore/customers  — populate the table
// ─────────────────────────────────────────────────────────────────────────────
async function loadCustomers() {
  showStatus("⏳ Loading customers…", false);
  document.getElementById("customerTable").style.display = "none";
  document.getElementById("totalBar").style.display     = "none";

  try {
    const res = await fetch("/computerstore/customers");
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const customers = await res.json();

    // GET /computerstore/customers/total  — grand total
    const resTotal = await fetch("/computerstore/customers/total");
    if (!resTotal.ok) throw new Error(`HTTP ${resTotal.status}`);
    const { total } = await resTotal.json();

    renderTable(customers, total);
  } catch (err) {
    showStatus(`❌ Failed to load customers: ${err.message}`, true);
  }
}

// ─────────────────────────────────────────────────────────────────────────────
// GET /computerstore/customers/:id  — search panel
// ─────────────────────────────────────────────────────────────────────────────
async function lookupById() {
  const input    = document.getElementById("lookupInput").value.trim();
  const resultEl = document.getElementById("lookupResult");

  if (!input) {
    show(resultEl, "Please enter a customer ID.", "error");
    return;
  }

  try {
    const res = await fetch(`/computerstore/customers/${encodeURIComponent(input)}`);
    if (res.status === 404) {
      show(resultEl, `No customer found with ID ${input}.`, "error");
      return;
    }
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const c = await res.json();
    show(
      resultEl,
      `<strong>#${c.id}</strong> — ${escHtml(c.name)} &nbsp;|&nbsp; ` +
      `Age: ${c.age} &nbsp;|&nbsp; ` +
      `Money Spent: <strong>$${c.moneySpent.toFixed(2)}</strong>`,
      "success"
    );
  } catch (err) {
    show(resultEl, `Error: ${err.message}`, "error");
  }
}

// ─────────────────────────────────────────────────────────────────────────────
// GET /computerstore/customers/total
// ─────────────────────────────────────────────────────────────────────────────
async function fetchTotal() {
  const el = document.getElementById("totalResult");
  try {
    const res = await fetch("/computerstore/customers/total");
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const { total } = await res.json();
    show(el, `Grand Total: <strong>$${parseFloat(total).toFixed(2)}</strong>`, "success");
  } catch (err) {
    show(el, `Error: ${err.message}`, "error");
  }
}

// ─────────────────────────────────────────────────────────────────────────────
// GET /computerstore/customers/stats
// ─────────────────────────────────────────────────────────────────────────────
async function fetchStats() {
  const el = document.getElementById("statsResult");
  try {
    const res = await fetch("/computerstore/customers/stats");
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const s = await res.json();
    show(
      el,
      `Count: <strong>${s.count}</strong> &nbsp;|&nbsp; ` +
      `Avg Age: <strong>${s.averageAge}</strong> &nbsp;|&nbsp; ` +
      `Avg Spend: <strong>$${s.averageMoneySpent.toFixed(2)}</strong>`,
      "success"
    );
  } catch (err) {
    show(el, `Error: ${err.message}`, "error");
  }
}

// ─────────────────────────────────────────────────────────────────────────────
// GET /computerstore/customers/top-spenders
// ─────────────────────────────────────────────────────────────────────────────
async function fetchTopSpenders() {
  const el = document.getElementById("topResult");
  try {
    const res = await fetch("/computerstore/customers/top-spenders");
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const list = await res.json();
    const lines = list
      .map((c, i) =>
        `${i + 1}. <strong>${escHtml(c.name)}</strong> (ID ${c.id}) — $${c.moneySpent.toFixed(2)}`
      )
      .join("<br>");
    show(el, lines || "No data.", "success");
  } catch (err) {
    show(el, `Error: ${err.message}`, "error");
  }
}

// ─────────────────────────────────────────────────────────────────────────────
// POST /computerstore/customers  /  PUT /computerstore/customers/:id
// Shared form — switches mode depending on editingId.
// ─────────────────────────────────────────────────────────────────────────────
async function submitForm() {
  const id      = document.getElementById("fId").value.trim();
  const name    = document.getElementById("fName").value.trim();
  const age     = document.getElementById("fAge").value.trim();
  const money   = document.getElementById("fMoney").value.trim();
  const msgEl   = document.getElementById("formMsg");

  if (!id || !name || !age || !money) {
    show(msgEl, "All four fields are required.", "error");
    return;
  }

  const body = { id: Number(id), name, age: Number(age), moneySpent: parseFloat(money) };

  try {
    let res;
    if (editingId === null) {
      // ── POST ──────────────────────────────────────────────
      res = await fetch("/computerstore/customers", {
        method:  "POST",
        headers: { "Content-Type": "application/json" },
        body:    JSON.stringify(body),
      });
    } else {
      // ── PUT ───────────────────────────────────────────────
      res = await fetch(`/computerstore/customers/${encodeURIComponent(editingId)}`, {
        method:  "PUT",
        headers: { "Content-Type": "application/json" },
        body:    JSON.stringify(body),
      });
    }

    const data = await res.json();

    if (!res.ok) {
      show(msgEl, data.message || `Error ${res.status}`, "error");
      return;
    }

    const action = editingId === null ? "added" : "updated";
    show(msgEl, `Customer ${action} successfully.`, "success");
    cancelEdit();      // reset form
    loadCustomers();   // refresh table
  } catch (err) {
    show(msgEl, `Error: ${err.message}`, "error");
  }
}

// ─────────────────────────────────────────────────────────────────────────────
// DELETE /computerstore/customers/:id  — called from table row button
// ─────────────────────────────────────────────────────────────────────────────
async function deleteCustomer(id) {
  if (!confirm(`Delete customer with ID ${id}?`)) return;

  try {
    const res = await fetch(`/computerstore/customers/${encodeURIComponent(id)}`, {
      method: "DELETE",
    });
    const data = await res.json();

    if (!res.ok) {
      alert(data.message || `Error ${res.status}`);
      return;
    }

    loadCustomers();   // refresh table after delete
  } catch (err) {
    alert(`Error: ${err.message}`);
  }
}

// ─────────────────────────────────────────────────────────────────────────────
// Edit — populates the shared form with existing customer data
// ─────────────────────────────────────────────────────────────────────────────
function editCustomer(id, name, age, moneySpent) {
  editingId = id;

  document.getElementById("fId").value    = id;
  document.getElementById("fName").value  = name;
  document.getElementById("fAge").value   = age;
  document.getElementById("fMoney").value = moneySpent;

  document.getElementById("formTitle").textContent   = "✏️ Edit Customer";
  document.getElementById("formUri").innerHTML =
    `PUT <code>/computerstore/customers/${id}</code>`;
  document.getElementById("formBtnLabel").textContent = "Save Changes";
  document.getElementById("cancelEditBtn").style.display = "inline-block";

  // Scroll to the form
  document.querySelector(".form-card").scrollIntoView({ behavior: "smooth" });

  const msgEl = document.getElementById("formMsg");
  msgEl.style.display = "none";
}

function cancelEdit() {
  editingId = null;
  ["fId","fName","fAge","fMoney"].forEach(id => document.getElementById(id).value = "");
  document.getElementById("formTitle").textContent     = "➕ Add New Customer";
  document.getElementById("formUri").innerHTML =
    `POST <code>/computerstore/customers</code>`;
  document.getElementById("formBtnLabel").textContent  = "Add Customer";
  document.getElementById("cancelEditBtn").style.display = "none";
  document.getElementById("formMsg").style.display     = "none";
}

// ─────────────────────────────────────────────────────────────────────────────
// Render table
// ─────────────────────────────────────────────────────────────────────────────
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
      <td class="money-cell">$${c.moneySpent.toFixed(2)}</td>
      <td class="actions-col">
        <button class="btn-action btn-edit"
          onclick="editCustomer(${c.id}, '${escHtml(c.name).replace(/'/g,"\\'")}', ${c.age}, ${c.moneySpent})">
          Edit
        </button>
        <button class="btn-action btn-delete"
          onclick="deleteCustomer(${c.id})">
          Delete
        </button>
      </td>
    `;
    tbody.appendChild(tr);
    allRows.push(tr);
  });

  showStatus("", false);
  document.getElementById("customerTable").style.display = "table";
  document.getElementById("totalBar").style.display      = "flex";
  document.getElementById("grandTotal").textContent      = `$${parseFloat(total).toFixed(2)}`;
  updateCount(customers.length, customers.length);
}

// ─────────────────────────────────────────────────────────────────────────────
// Search / filter
// ─────────────────────────────────────────────────────────────────────────────
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

// ─────────────────────────────────────────────────────────────────────────────
// Utilities
// ─────────────────────────────────────────────────────────────────────────────
function showStatus(msg, isError) {
  const el = document.getElementById("statusMsg");
  el.textContent = msg;
  el.className   = isError ? "error" : "";
  el.style.display = msg ? "block" : "none";
}

function show(el, html, type) {
  el.innerHTML   = html;
  el.className   = `lookup-result ${type}`;
  el.style.display = "block";
}

function escHtml(str) {
  return String(str)
    .replace(/&/g,"&amp;")
    .replace(/</g,"&lt;")
    .replace(/>/g,"&gt;")
    .replace(/"/g,"&quot;");
}
