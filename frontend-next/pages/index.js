import dynamic from "next/dynamic";
import useSWR from "swr";
import styles from './Home.module.css';
import Navbar from '../components/Navbar';

const fetcher = (url) => fetch(url).then(r => r.json());
const MapWithNoSSR = dynamic(() => import("../components/Map"), { ssr: false });

export default function Home() {
  const api = process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000";
  const { data, error } = useSWR(`${api}/api/traccar/positions`, fetcher, { refreshInterval: 5000 });

  return (
    <>
      <Navbar />
      <div className={styles.container}>
        <header className={styles.header}>
          <h1>Thumbworx Live Trackings</h1>
        </header>

        {error && <p className={styles.message}>Failed to load positions.</p>}
        {!data && !error && <p className={styles.message}>Loading positions...</p>}

        {data && <MapWithNoSSR positions={data} />}
      </div>
      <div className={styles["dashboard-frame-container"]}>
          <iframe
            src="http://localhost:3001/public/dashboard/8c0a97bc-b281-4826-9694-7ef9886ed237"
            title="Metabase Dashboard"
          ></iframe>
        </div>
    </>
  );
}