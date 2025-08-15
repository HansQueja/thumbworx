'use client';

import React, { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import styles from './AllDrivers.module.css';
import Navbar from '../../components/Navbar';

export default function AllDriversPage() {
  const [drivers, setDrivers] = useState([]);
  const router = useRouter();

  useEffect(() => {
    fetch('http://localhost:8000/api/drivers')
      .then((res) => res.json())
      .then((data) => setDrivers(data))
      .catch((err) => console.error('Failed to fetch drivers:', err));
  }, []);

  const handleClick = (id) => {
    router.push(`/driver/${id}`);
  };

  return (
    <div>
      <Navbar />
      <div className={styles.container}>
        <h1>All Drivers</h1>
        <div className={styles.grid}>
          {drivers.map((driver) => (
            <div
              key={driver.id}
              className={styles.card}
              onClick={() => handleClick(driver.id)}
            >
              <h3>{driver.name}</h3>
              <p>License: {driver.license_number}</p>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
