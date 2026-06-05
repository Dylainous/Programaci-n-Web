const mongoose = require("mongoose");

// Schema accepts both variants found in the DB:
//   name / fullName  →  displayed as "Name"
//   moneySpent / totalSale  →  displayed as "Money Spent"
const customerSchema = new mongoose.Schema(
  {
    id: { type: Number },
    name: { type: String },
    fullName: { type: String },
    age: { type: Number },
    moneySpent: { type: Number },
    totalSale: { type: Number },
    // Extra fields present in some documents (ignored in display but loaded)
    email: { type: String },
    type: { type: String },
    discount: { type: Number },
  },
  { collection: "Customer", strict: false }
);

module.exports = mongoose.model("Customer", customerSchema);
