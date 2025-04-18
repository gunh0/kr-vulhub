import express from 'express';

const app = express();
const PORT = 3000;

app.use(express.json());

app.post('/import', async (req, res) => {
  const { url } = req.body;

  if (!url || !url.startsWith('data:text/javascript')) {
    return res.status(400).send('Invalid or missing data URL');
  }

  try {
    console.log(`[*] Importing: ${url.slice(0, 60)}...`);
    await import(url);
    res.send('✅ Code executed successfully.');
  } catch (err) {
    console.error('[!] Import error:', err);
    res.status(500).send(`❌ Import failed: ${err.message}`);
  }
});

app.listen(PORT, () => {
  console.log(`🚀 CVE-2024-22020 server listening at http://localhost:${PORT}`);
});
