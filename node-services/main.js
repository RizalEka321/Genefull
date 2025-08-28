const express = require("express");
const app = express();
const http = require("http").createServer(app);
const io = require("socket.io")(http, { cors: { origin: "*" } });

const scraper = require("./modules/scraper");

io.on("connection", (socket) => {
  console.log("Client connected:", socket.id);

  socket.on("scrape", async () => {
    try {
      console.log("Mulai Login");
      await scraper();
      console.log("Login Selesai");
    } catch (err) {
      console.log(err);
    }
  });

  socket.on("disconnect", () => console.log("Client disconnected:", socket.id));
});

const PORT = 3000;
http.listen(PORT, () => console.log(`Node.js server jalan di http://localhost:${PORT}`));
