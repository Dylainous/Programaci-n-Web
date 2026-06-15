const express = require("express");
const Customer = require("../models/customer");
const router = express.Router();

// ── Helper ────────────────────────────────────────────────────────────────────
// Returns true only when a document has ALL four required fields with real values.
function hasRequiredFields(doc) {
  return (
    doc.id != null &&
    doc.name != null &&
    doc.age != null &&
    doc.moneySpent != null
  );
}

// Formats a raw Mongoose document into the shape the front-end expects.
function formatCustomer(doc, index) {
  return {
    number: index + 1,
    _id: doc._id,
    id: doc.id,
    name: doc.name,
    age: doc.age,
    moneySpent: parseFloat(parseFloat(doc.moneySpent).toFixed(2)),
  };
}

// ── GET /customers ────────────────────────────────────────────────────────────
// Returns all customers that have the four required fields.
router.get("/customers", async (req, res) => {
  try {
    const raw = await Customer.find().lean();
    // Keep only documents that contain id, name, age AND moneySpent.
    const valid = raw.filter(hasRequiredFields);
    const customers = valid.map((doc, i) => formatCustomer(doc, i));
    res.json(customers);
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
});

// ── GET /customers/total ──────────────────────────────────────────────────────
// Returns the sum of moneySpent across all valid customers.
router.get("/customers/total", async (req, res) => {
  try {
    const raw = await Customer.find().lean();
    const valid = raw.filter(hasRequiredFields);
    const total = valid
      .reduce((sum, doc) => sum + parseFloat(doc.moneySpent), 0)
      .toFixed(2);
    res.json({ total: parseFloat(total) });
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
});

// ── GET /customers/stats ──────────────────────────────────────────────────────
// Bonus GET: summary statistics (count, average age, average spend).
router.get("/customers/stats", async (req, res) => {
  try {
    const raw = await Customer.find().lean();
    const valid = raw.filter(hasRequiredFields);

    if (valid.length === 0) {
      return res.json({ count: 0, averageAge: 0, averageMoneySpent: 0 });
    }

    const count = valid.length;
    const averageAge = parseFloat(
      (valid.reduce((sum, d) => sum + d.age, 0) / count).toFixed(2)
    );
    const averageMoneySpent = parseFloat(
      (
        valid.reduce((sum, d) => sum + parseFloat(d.moneySpent), 0) / count
      ).toFixed(2)
    );

    res.json({ count, averageAge, averageMoneySpent });
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
});

// ── GET /customers/top-spenders ───────────────────────────────────────────────
// Bonus GET: returns the top 5 customers sorted by moneySpent (descending).
router.get("/customers/top-spenders", async (req, res) => {
  try {
    const raw = await Customer.find().lean();
    const valid = raw.filter(hasRequiredFields);
    const sorted = valid
      .sort((a, b) => parseFloat(b.moneySpent) - parseFloat(a.moneySpent))
      .slice(0, 5)
      .map((doc, i) => formatCustomer(doc, i));
    res.json(sorted);
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
});

// ── GET /customers/:id ────────────────────────────────────────────────────────
// Returns a single customer by their custom numeric id field.
// Only returned if the document passes the four-field check.
router.get("/customers/:id", async (req, res) => {
  try {
    const doc = await Customer.findOne({ id: req.params.id }).lean();
    if (!doc) {
      return res.status(404).json({ status: 404, message: "Customer not found" });
    }
    if (!hasRequiredFields(doc)) {
      return res
        .status(422)
        .json({ status: 422, message: "Customer record is missing required fields" });
    }
    res.json(formatCustomer(doc, 0));
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
});

// ── POST /customers ───────────────────────────────────────────────────────────
// Creates a new customer. All four required fields must be present in the body.
router.post("/customers", async (req, res) => {
  const { id, name, age, moneySpent } = req.body;

  if (id == null || name == null || age == null || moneySpent == null) {
    return res.status(400).json({
      status: 400,
      message: "Missing required fields: id, name, age and moneySpent are mandatory",
    });
  }

  try {
    const exists = await Customer.findOne({ id }).lean();
    if (exists) {
      return res.status(409).json({ status: 409, message: "A customer with that id already exists" });
    }

    const customer = new Customer({ id, name, age, moneySpent, ...req.body });
    const saved = await customer.save();
    res.status(201).json(formatCustomer(saved.toObject(), 0));
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
});

// ── PUT /customers/:id ────────────────────────────────────────────────────────
// Updates an existing customer by their custom numeric id field.
router.put("/customers/:id", async (req, res) => {
  const paramId = Number(req.params.id);
  const bodyId = req.body.id != null ? Number(req.body.id) : null;

  try {
    // VALIDACIÓN: Si intentan cambiar el ID en el body, verificar que no choque con otro cliente
    if (bodyId !== null && bodyId !== paramId) {
      const idConflict = await Customer.findOne({ id: bodyId }).lean();
      if (idConflict) {
        return res.status(409).json({
          status: 409,
          message: `Conflict: The ID ${bodyId} already belongs to another client. It cannot be duplicated.`
        });
      }
    }

    const doc = await Customer.findOneAndUpdate(
      { id: paramId },
      { $set: req.body },
      { new: true, runValidators: true }
    ).lean();

    if (!doc) {
      return res.status(404).json({ status: 404, message: "Customer not found" });
    }

    if (!hasRequiredFields(doc)) {
      return res.status(422).json({
        status: 422,
        message: "Update would remove a required field (id, name, age, moneySpent)",
      });
    }

    res.json(formatCustomer(doc, 0));
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
});

// ── DELETE /customers/:id ─────────────────────────────────────────────────────
// Deletes a customer by their custom numeric id field.
router.delete("/customers/:id", async (req, res) => {
  try {
    const doc = await Customer.findOneAndDelete({ id: req.params.id }).lean();
    if (!doc) {
      return res.status(404).json({ status: 404, message: "Customer not found" });
    }
    res.json({ message: `Customer with id ${req.params.id} deleted successfully` });
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
});

module.exports = router;
