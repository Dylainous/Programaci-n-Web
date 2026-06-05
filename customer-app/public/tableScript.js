let allRows = [];   // all <tr> elements
    let grandTotal = 0;

    // ── Fetch data from the API ──────────────────────────────────────────────
    async function loadCustomers() {
      try {
        const res = await fetch("/computerstore/customers");
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const data = await res.json();

        grandTotal = data.grandTotal;
        renderTable(data.customers);
      } catch (err) {
        const statusEl = document.getElementById("statusMsg");
        statusEl.textContent = `❌ Failed to load customers: ${err.message}`;
        statusEl.classList.add("error");
      }
    }

    // ── Build the table rows ─────────────────────────────────────────────────
    function renderTable(customers) {
      const tbody = document.getElementById("tableBody");
      tbody.innerHTML = "";
      allRows = [];

      customers.forEach((c) => {
        const tr = document.createElement("tr");
        tr.dataset.search =
          `${c.number} ${c.id} ${c.name} ${c.age}`.toLowerCase();

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

      // Show table and totals
      document.getElementById("statusMsg").style.display = "none";
      document.getElementById("customerTable").style.display = "table";
      document.getElementById("totalBar").style.display = "flex";
      document.getElementById("grandTotal").textContent =
        `$${grandTotal.toFixed(2)}`;
      updateCount(customers.length, customers.length);
    }

    // ── Live search / filter ─────────────────────────────────────────────────
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

    // ── Utility ──────────────────────────────────────────────────────────────
    function escHtml(str) {
      return String(str)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
    }

    // ── Boot ─────────────────────────────────────────────────────────────────
    loadCustomers();