# MainStore Token Service

A tiny Render-ready Node.js backend that logs into the MainStore API, caches the token, refreshes it when expired, and exposes endpoints your Laravel app or browser can call.

## Endpoints

- `GET /health` — health check
- `GET /token` — returns a fresh or cached token
- `GET /stock/report?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD` — proxies the MainStore stock report

## Deploy on Render

1. Push this folder to its own GitHub repo.
2. In Render, create a new **Web Service** from that repo.
3. Runtime: Node
4. Build Command: `npm install`
5. Start Command: `npm start`
6. Add these environment variables in Render:
   - `MAINSTORE_API_URL`
   - `MAINSTORE_API_USERNAME`
   - `MAINSTORE_API_PASSWORD`
   - `MAINSTORE_API_TOKEN_TTL`
7. Deploy. Render will give you a public URL like `https://mainstore-token-service.onrender.com`.

## Use from Laravel

Point your Laravel proxy or frontend fetch calls to the Render URL instead of calling MainStore directly.
