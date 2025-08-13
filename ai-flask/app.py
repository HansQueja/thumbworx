import os, json, time
from flask import Flask, jsonify, request
import requests
import redis
from sqlalchemy import create_engine, MetaData, Table, Column, Integer, Float, String, DateTime
from datetime import datetime
from apscheduler.schedulers.background import BackgroundScheduler
 
TRACCAR_BASE = os.getenv("TRACCAR_BASE_URL")
TRACCAR_USER = os.getenv("TRACCAR_USER")
TRACCAR_PASS = os.getenv("TRACCAR_PASS")
REDIS_URL = os.getenv("REDIS_URL", "redis://localhost:6379/0")
DB_URL = os.getenv("DATABASE_URL", "postgresql://thumb_user:thumb_pass@localhost/thumbworx")
 
r = redis.from_url(REDIS_URL)
engine = create_engine(DB_URL)
metadata = MetaData()
 
positions = Table('positions', metadata,
    Column('id', Integer, primary_key=True),
    Column('device_id', Integer),
    Column('latitude', Float),
    Column('longitude', Float),
    Column('speed', Float),
    Column('timestamp', DateTime),
    Column('attributes', String),
)
 
metadata.create_all(engine)
 
app = Flask(__name__)
 
def traccar_auth():
	return (TRACCAR_USER, TRACCAR_PASS)

def fetch_and_store_positions_api():
    # Query traccar positions
    res = requests.get(f"{TRACCAR_BASE}/api/positions", auth=traccar_auth())
    items = res.json()

    r.set("latest_positions", json.dumps(items), ex=30)
    # persist latest N positions (optional)
    with engine.begin() as conn:
        for p in items:
            conn.execute(positions.insert().values(
                device_id=p.get("deviceId"),
                latitude=p.get("latitude"),
                longitude=p.get("longitude"),
                speed=p.get("speed"),
                timestamp=datetime.fromtimestamp(p.get("deviceTime")/1000.0) if p.get("deviceTime") else datetime.utcnow(),
                attributes=json.dumps(p.get("attributes", {}))
            ))

    return items

@app.route("/api/traccar/devices")
def devices():
	res = requests.get(f"{TRACCAR_BASE}/api/devices", auth=traccar_auth())
	return jsonify(res.json())

@app.route("/api/traccar/positions")
def positions_api():
    items = fetch_and_store_positions_api()
    return jsonify(items)

@app.route("/api/positions_cached")
def cached_positions():
    data = r.get("latest_positions")
    if data:
        return jsonify(json.loads(data))
    return jsonify([])
 
# lightweight ETA endpoint stub
@app.route("/api/predict_eta", methods=["POST"])
def predict_eta():
    payload = request.json  # {current_lat, current_lng, dropoff_lat, dropoff_lng}
    # replace with your model call or simple heuristic
    # this example returns simple ETA = distance(km) * 3 (min per km)
    from geopy.distance import geodesic
    a = (payload['current_lat'], payload['current_lng'])
    b = (payload['dropoff_lat'], payload['dropoff_lng'])
    km = geodesic(a, b).km
    eta_minutes = km * 3
    return jsonify({"eta_minutes": round(eta_minutes,2)})

def start_scheduler():
    sched = BackgroundScheduler()
    sched.add_job(fetch_and_store_positions_api, 'interval', seconds=10)
    sched.start()

# Start the scheduler when Flask starts
start_scheduler()
 
if __name__ == "__main__":
	app.run(host="0.0.0.0", port=5000, debug=True)
