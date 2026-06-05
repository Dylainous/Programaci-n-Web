const express = require("express");
const Customer = require("../models/customer");
const router = express.Router();

// Helper: normalize a raw DB document into a clean customer object
function normalize(doc, index) {
  const displayName = doc.fullName || doc.name || "—";
  const spent =
    doc.moneySpent != null
      ? doc.moneySpent
      : doc.totalSale != null
      ? doc.totalSale
      : 0;

  return {
    number: index + 1,
    _id: doc._id,
    id: doc.id,
    name: displayName,
    age: doc.age != null ? doc.age : "—",
    moneySpent: parseFloat(spent.toFixed(2)),
  };
}

// GET /computerstore/customers
// Returns all customers, normalized + grand total
router.get("/customers", async (req, res) => {
  try {
    const raw = await Customer.find().lean();
    const customers = raw.map((doc, i) => normalize(doc, i));
    const grandTotal = customers
      .reduce((sum, c) => sum + c.moneySpent, 0)
      .toFixed(2);

    res.json({ customers, grandTotal: parseFloat(grandTotal) });
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
});

// GET /computerstore/customers/:id
// Returns a single customer by their numeric id field
router.get("/customers/:id", async (req, res) => {
  try {
    const doc = await Customer.findOne({ id: req.params.id }).lean();
    if (!doc) return res.status(404).json({ status: 404, message: "Customer not found" });
    res.json(normalize(doc, 0));
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
});

module.exports = router;
