import React from 'react';
import Link from 'next/link';
import styles from './Navbar.module.css';

const Navbar = () => {
  return (
    <nav className={styles.navbar}>
      <div className={styles["navbar-brand"]}>Thumbworx</div>
      <ul className={styles["navbar-links"]}>
        <li><Link href="/">Dashboard</Link></li>
        <li><Link href="/driver">Drivers</Link></li>
        <li><Link href="/about">About</Link></li>
        <li><Link href="/contact">Contact</Link></li>
      </ul>
    </nav>
  );
};

export default Navbar;
