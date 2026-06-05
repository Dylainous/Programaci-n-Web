const port = process.env.PORT || 3000;
const express = require("express");
const mongoose = require("mongoose");
const path = require("path");

const app = express();

// ── Database connection ───────────────────────────────────────────────────────
const MONGO_URI =
  process.env.MONGO_URI ||
  "mongodb+srv://oop:oop@cluster0.9knxc.mongodb.net/oop?appName=Cluster0";

mongoose.connect(MONGO_URI);

const db = mongoose.connection;
db.on("error", (error) => console.error("MongoDB error:", error));
db.once("open", () => console.log("Connected to MongoDB Database"));

// ── Middleware ────────────────────────────────────────────────────────────────
app.use(express.json());

// Serve the frontend from /public
app.use(express.static(path.join(__dirname, "public")));

// ── API routes ────────────────────────────────────────────────────────────────
const customerRouter = require("./routes/customerRoutes");
app.use("/computerstore", customerRouter);

// ── Fallback: serve index.html for any non-API route ─────────────────────────
app.get("/{*path}", (req, res) => {
  res.sendFile(path.join(__dirname, "public", "index.html"));
});

// ── Start server ──────────────────────────────────────────────────────────────
app.listen(port, () =>
  console.log(`Edison's Computer Store server running on port ${port}`)
);
