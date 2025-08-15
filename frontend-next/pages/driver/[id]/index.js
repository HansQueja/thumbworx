import React, { useEffect, useState } from 'react';
import { useRouter } from 'next/router';
import styles from './DriverDashboardPage.module.css';
import Navbar from '../../../components/Navbar';

export default function DriverDashboardPage() {
  const router = useRouter();
  const { id } = router.query; // get the id from the URL

  const [driver, setDriver] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (!id) return; // wait until id is available
    fetch(`http://localhost:8000/api/drivers/${id}/profile`)
      .then(res => res.ok ? res.json() : null)
      .then(data => {
        setDriver(data);
        setLoading(false);
      });
  }, [id]);

  if (loading) return <div className={styles.container}>Loading...</div>;
  if (!driver) return <div className={styles.container}>Driver not found.</div>;

  const averageRating =
    driver.feedback.length > 0
      ? (
          driver.feedback.reduce((sum, fb) => sum + fb.rating, 0) /
          driver.feedback.length
        ).toFixed(2)
      : 'No ratings yet';

  const validCredentials = driver.credentials.filter(c => c.is_valid).length;
  const invalidCredentials = driver.credentials.length - validCredentials;

  return (
    <div>
      <Navbar />
      <div className={styles.container}>
        <h1 className={styles["page-title"]}>
          Driver Dashboard: {driver.name}
        </h1>
        
        <section className={styles.section}>
          <h2>Drug Test History</h2>
          <table className={styles["data-table"]}>
            <thead>
              <tr>
                <th>Date</th>
                <th>Result</th>
              </tr>
            </thead>
            <tbody>
              {driver.drug_test_results.map(result => (
                <tr key={result.id}>
                  <td>{result.test_date}</td>
                  <td>{result.result}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </section>

        <section className={styles.section}>
          <h2>Violations & Infractions</h2>
          <ul className={styles.timeline}>
            {driver.violations.map(v => (
              <li key={v.id}>
                <strong>{v.date}</strong>: {v.type} - {v.description}
              </li>
            ))}
          </ul>
        </section>

        <section className={styles.section}>
          <h2>Performance Rating</h2>
          <p>Average Rating: <strong>{averageRating}</strong></p>
          <p>Number of Reviews: <strong>{driver.feedback.length}</strong></p>
        </section>

        <section className={styles.section}>
          <h2>Uploaded Credentials</h2>
          <p>- Valid: <strong>{validCredentials}</strong></p>
          <p>- Invalid: <strong>{invalidCredentials}</strong></p>
        </section>
      </div>
    </div>
  );
}
