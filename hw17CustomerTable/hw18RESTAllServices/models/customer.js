const mongoose = require("mongoose");

const customerSchema = new mongoose.Schema(
  {
    id: { type: Number },
    name: { type: String },
    fullName: { type: String },
    age: { type: Number },
    moneySpent: { type: Number },
    totalSale: { type: Number },
    email: { type: String },
    type: { type: String },
    discount: { type: Number },
  },
  { collection: "Customer", strict: false }
);

module.exports = mongoose.model("Customer", customerSchema);
