import express from 'express';
import fetch from 'node-fetch';

const app = express();
const PORT = process.env.PORT || 3000;

const API_BASE_URL = process.env.MAINSTORE_API_URL || 'https://mainstore.co.tz/api';
const USERNAME = process.env.MAINSTORE_API_USERNAME;
const PASSWORD = process.env.MAINSTORE_API_PASSWORD;
const TOKEN_TTL_SECONDS = Number(process.env.MAINSTORE_API_TOKEN_TTL || 50 * 60);

let cachedToken = null;
let tokenExpiresAt = 0;

async function loginAndGetToken() {
  const response = await fetch(`${API_BASE_URL}/auth/login`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body: JSON.stringify({
      username: USERNAME,
      password: PASSWORD
    })
  });

  if (!response.ok) {
    const text = await response.text();
    throw new Error(`MainStore login failed: ${response.status} ${text}`);
  }

  const data = await response.json();
  const token = data?.data?.token || data?.token || data?.access_token || null;

  if (!token) {
    throw new Error('MainStore login response did not contain a token');
  }

  return token;
}

async function getFreshToken() {
  try {
    const token = await loginAndGetToken();
    cachedToken = token;
    tokenExpiresAt = Date.now() + TOKEN_TTL_SECONDS * 1000;
    return token;
  } catch (error) {
    cachedToken = null;
    tokenExpiresAt = 0;
    throw error;
  }
}

async function getToken() {
  if (!cachedToken || Date.now() >= tokenExpiresAt) {
    return getFreshToken();
  }
  return cachedToken;
}

app.use((req, res, next) => {
  res.header('Access-Control-Allow-Origin', '*');
  res.header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
  res.header('Access-Control-Allow-Headers', 'Content-Type');
  if (req.method === 'OPTIONS') {
    return res.sendStatus(204);
  }
  next();
});

app.get('/health', (req, res) => {
  res.json({
    status: 'ok',
    hasToken: Boolean(cachedToken),
    expiresAt: tokenExpiresAt ? new Date(tokenExpiresAt).toISOString() : null
  });
});

app.get('/token', async (req, res) => {
  try {
    const token = await getToken();
    res.json({
      token,
      expiresAt: new Date(tokenExpiresAt).toISOString()
    });
  } catch (error) {
    console.error('Token error:', error);
    res.status(500).json({ error: error.message });
  }
});

app.get('/stock/report', async (req, res) => {
  try {
    const startDate = req.query.start_date;
    const endDate = req.query.end_date;

    if (!startDate || !endDate) {
      return res.status(400).json({ error: 'Missing start_date or end_date' });
    }

    const token = await getToken();

    const reportResponse = await fetch(
      `${API_BASE_URL}/stock/report?start_date=${encodeURIComponent(startDate)}&end_date=${encodeURIComponent(endDate)}`,
      {
        headers: {
          Authorization: `Bearer ${token}`,
          Accept: 'application/json'
        }
      }
    );

    const contentType = reportResponse.headers.get('content-type') || '';
    const body = contentType.includes('application/json')
      ? await reportResponse.json()
      : await reportResponse.text();

    if (reportResponse.status === 401 || reportResponse.status === 403) {
      cachedToken = null;
      tokenExpiresAt = 0;

      const newToken = await getFreshToken();
      const retryResponse = await fetch(
        `${API_BASE_URL}/stock/report?start_date=${encodeURIComponent(startDate)}&end_date=${encodeURIComponent(endDate)}`,
        {
          headers: {
            Authorization: `Bearer ${newToken}`,
            Accept: 'application/json'
          }
        }
      );

      const retryContentType = retryResponse.headers.get('content-type') || '';
      const retryBody = retryContentType.includes('application/json')
        ? await retryResponse.json()
        : await retryResponse.text();

      return res.status(retryResponse.status).send(retryBody);
    }

    res.status(reportResponse.status).send(body);
  } catch (error) {
    console.error('Stock report error:', error);
    res.status(500).json({ error: error.message });
  }
});

app.listen(PORT, () => {
  console.log(`MainStore token service listening on port ${PORT}`);
});
