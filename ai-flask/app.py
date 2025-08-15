import os, json
from flask import Flask, jsonify, request
import requests
import redis
from datetime import datetime
from apscheduler.schedulers.background import BackgroundScheduler
 
TRACCAR_BASE = os.getenv("TRACCAR_BASE_URL")
TRACCAR_USER = os.getenv("TRACCAR_USER")
TRACCAR_PASS = os.getenv("TRACCAR_PASS")
REDIS_URL = os.getenv("REDIS_URL", "redis://localhost:6379/0")
 
r = redis.from_url(REDIS_URL)
 
app = Flask(__name__)
 
def traccar_auth():
	return (TRACCAR_USER, TRACCAR_PASS)

def fetch_positions_api():
    try:
        res = requests.get(f"{TRACCAR_BASE}/api/positions", auth=traccar_auth(), timeout=10)
        res.raise_for_status()
        items = res.json()

        filtered_items = []
        for item in items:
            new_dict = {}
            new_dict["device_id"] = item["deviceId"]
            new_dict["latitude"] = item["latitude"]
            new_dict["longitude"] = item["longitude"]
            new_dict["speed"] = item["speed"]
            new_dict["device_time"] = item["deviceTime"]
            new_dict["attributes"] = item["attributes"]
            filtered_items.append(new_dict)

        r.set("latest_positions", json.dumps(filtered_items), ex=20)
        return filtered_items
    except requests.RequestException as e:
        print(f"Error fetching Traccar: {e}")
        return cached_positions()

@app.route("/api/traccar/devices")
def devices():
	res = requests.get(f"{TRACCAR_BASE}/api/devices", auth=traccar_auth())
	return jsonify(res.json())

@app.route("/api/traccar/positions")
def positions_api():
    items = fetch_positions_api()
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
    sched.add_job(fetch_positions_api, 'interval', seconds=20)
    sched.start()

start_scheduler()

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)
