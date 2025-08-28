const { chromium } = require("playwright");
const fs = require("fs");
const path = require("path");

module.exports = async function () {
  // Pastikan folder sessions ada
  const sessionDir = path.join(__dirname, "sessions");
  if (!fs.existsSync(sessionDir)) {
    fs.mkdirSync(sessionDir);
  }

  const browser = await chromium.launch({
    headless: false,
    executablePath: "C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe",
    args: ["--user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36", "--disable-blink-features=AutomationControlled", "--no-sandbox"],
  });

  const context = await browser.newContext();
  const page = await context.newPage();

  console.log("Silakan login secara manual di browser yang muncul...");
  await page.goto("https://www.tiktok.com/login");

  // Cek setiap 0.5 detik apakah sudah berhasil login
  const checkLoginInterval = setInterval(async () => {
    const currentUrl = page.url();
    if (currentUrl.startsWith("https://www.tiktok.com/foryou")) {
      console.log("Login berhasil! Menyimpan session...");

      const cookies = await context.cookies();
      const filePath = path.join(sessionDir, "tes.json");
      fs.writeFileSync(filePath, JSON.stringify(cookies, null, 2));

      console.log(`Session berhasil disimpan di ${filePath}`);

      clearInterval(checkLoginInterval);
      await browser.close();
    }
  }, 500);

  // Timeout maksimal 5 menit
  setTimeout(async () => {
    console.log("Waktu login habis, browser ditutup.");
    clearInterval(checkLoginInterval);
    await browser.close();
  }, 300000);
};
