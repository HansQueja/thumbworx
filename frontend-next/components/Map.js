import { MapContainer, TileLayer, Marker, Popup } from 'react-leaflet';
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';
import styles from './Map.module.css';

// Fix default icon issue in Leaflet
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
  iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.3/images/marker-icon-2x.png',
  iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.3/images/marker-icon.png',
  shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.3/images/marker-shadow.png'
});

export default function Map({ positions }) {
  const center = [14.75, 121.01]; // Manila default

  return (
    <MapContainer
      center={center}
      zoom={12}
      className={styles.mapContainer}
    >
      <TileLayer
  url="https://cartodb-basemaps-a.global.ssl.fastly.net/light_all/{z}/{x}/{y}{r}.png"
  attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; <a href="https://www.carto.com/">CARTO</a>'
/>


      {positions.map((p, i) => (
        <Marker key={i} position={[p.latitude, p.longitude]}>
          <Popup>
            <div style={{ fontWeight: 'bold' }}>Device {p.deviceId}</div>
            <div>Speed: {p.speed} km/h</div>
          </Popup>
        </Marker>
      ))}
    </MapContainer>
  );
}
