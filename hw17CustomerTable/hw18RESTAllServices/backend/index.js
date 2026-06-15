const express = require("express");
const mongoose = require("mongoose");
const path = require("path");
const cors = require("cors"); // <-- 1. Importar CORS

const port = process.env.PORT || 3000;

const app = express();

app.use(cors()); 
app.use(express.json());

// Hardcoded connection string — no environment variable needed.
const MONGO_URI = "mongodb+srv://oop:oop@cluster0.9knxc.mongodb.net/oop?appName=Cluster0";

mongoose.connect(MONGO_URI);

const db = mongoose.connection;
db.on("error",  (error) => console.error("MongoDB error:", error));
db.once("open", ()      => console.log("Connected to MongoDB Database"));

//app.use(express.json());
//app.use(express.static(path.join(__dirname, "public")));

const customerRouter = require("./routes/customerRoutes");
app.use("/computerstore", customerRouter);

// Catch-all: return the SPA for any unmatched route.
app.get("/{*path}", (req, res) => {
  res.sendFile(path.join(__dirname, "public", "index.html"));
});

// Bind to 0.0.0.0 so AWS (EC2 / Elastic Beanstalk) can expose the port.
app.listen(port, "0.0.0.0", () =>
  console.log(`Edison's Computer Store running on port ${port}`)
);
