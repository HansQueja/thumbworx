# Full Integration - Thumbworx Mockup

Thumbworx is an integrated logistics tracking platform that connects to the Traccar demo server for live GPS data, processes it via a Flask microservice, orchestrates it with a Laravel backend, and visualizes it with a Next.js frontend dashboard.

It also supports Postgres + Redis persistence, and Metabase analytics for performance insights.

### 🏗️ Architecture
```
Traccar → Flask (poll/cache/persist) → Postgres/Redis → Laravel (API) → Next.js (Dashboard) → Metabase (Analytics)
```

### 📂 Project Layout
```sh
thumbworx/
├─ backend-laravel/     # Laravel API backend
├─ ai-flask/            # Flask microservice (Traccar proxy, ETA)
├─ frontend-next/       # Next.js app (map + dashboard)
├─ infra/               # docker-compose.yml, scripts
└─ docs/                # README, architecture, presentation slides
```

### ⚙️ Local Development (Docker Compose)
Spin and run up the full stack locally:
```bash
cd infra
docker-compose up --build
```
The docker run-up starts the following:
- Postgres at port `5432`
- Redis at port `6379`
- Flask microservice at port `5000`
- Laravel backend at port `8000`
- Next.js frontend at port `3000`

The environment variables are configured in docker-compose.yml.

## 🌍 Live Deployments

Here are the deployed services for Thumbworx:

| Service   | Deployment URL |
|-----------|----------------|
| **Flask (AI Microservice)** | [https://thumbworx-ai-flask.onrender.com](https://thumbworx-ai-flask.onrender.com) |
| **Laravel Backend (API)** | [https://thumbworx-backend-laravel-production.up.railway.app/](https://thumbworx-backend-laravel-production.up.railway.app/) |
| **Next.js Frontend (Dashboard)** | [https://thumbworx-ui.vercel.app/](https://thumbworx-ui.vercel.app/) |

---
## 🐕‍🦺 Services

### 1. Flask Microservice (`ai-flask/`)
- Polls Traccar API for devices & positions.
- Caches latest positions in Redis.
- Provides a simple ETA prediction stub (/api/predict_eta) to be implemented.

### 2. Laravel Backend (`backend-laravel/`)
- Acts as the API and orchestration layer.
- Provides endpoints to proxy Traccar/Flask data.
- Handles DB migrations for positions.
- Persists positions to Postgres.
- Exposes routes to be consumed by the frontend and analytics.

### 3. Next.js Frontend (`frontend-next/`)
- Interactive React Leaflet map with live GPS markers.
- Auto-refresh via SWR polling.
- Dashboard for available drivers and related records (feedback, violations, etc.)
- Connects to the Laravel API via `NEXT_PUBLIC_API_URL`.

### 4. Metabase (Optional for analytics)
- Connects to Postgres for analytics.
- Example dashboards:
  - Heatmap of latest positions
  - Avg. speed by region
  - Driver performance stats

---
## 🪨 Deployment Guide

| Service   | Recommended Host | Deployment Steps / Notes |
|-----------|------------------|--------------------------|
| **Flask (ai-flask)** | Render | 1. Push `ai-flask/` to GitHub.<br>2. Create new **Web Service** in Render.<br>3. Build command: `pip install -r requirements.txt`.<br>4. Start command: `gunicorn app:app -b 0.0.0.0:$PORT`.<br>5. Configure env vars: `TRACCAR_BASE_URL`, `TRACCAR_USER`, `TRACCAR_PASS`, `REDIS_URL`. |
| **Laravel (backend-laravel)** | Railway | 1. Push `backend-laravel/` to GitHub.<br>2. Create new project in Railway / Heroku.<br>3. Railway auto-detects PHP and runs `composer install`.<br>4. Set `APP_KEY` (`php artisan key:generate`).<br>5. Configure env vars: `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `TRACCAR_*`.<br>6. Add PostgreSQL plugin and run `php artisan migrate`. |
| **Next.js (frontend-next)** | Vercel | 1. Push `frontend-next/` to GitHub.<br>2. Import repo in Vercel.<br>3. Set env var: `NEXT_PUBLIC_API_URL` (point to Laravel API).<br>4. Deploy → Vercel provides live URL. |
| **Postgres** | Railway| Use managed Postgres instance. Set credentials in Laravel env vars. |
| **Redis** | Render | Use managed Redis instance. Set `REDIS_URL` in Flask env vars. |
| **Metabase (optional)** | Docker / Railway / Fly.io | 1. Run with `docker run -d -p 3000:3000 metabase/metabase`.<br>2. Connect to Postgres via Railway credentials.<br>3. Build dashboards (heatmap, avg. speed, driver performance). |

---
### ✨ Key Endpoints
#### Flask
- `/api/traccar/devices` → fetch devices from Traccar
- `/api/traccar/positions` → fetch + cache + persist positions
- `/api/positions_cached` → get cached positions
#### Laravel
- `/api/traccar/devices` → proxy to Traccar/Flask
- `/api/traccar/positions` → proxy/persist positions
- `/api/driver/` → fetch drivers from database
- `/api/driver/{id} → fetch individual driver and related records
#### Next.js
- `/` → Dashboard with live map
- `/drivers` → Dashbord of registered drivers on the database
- `/drivers/{id}` → Profile page of individual driver