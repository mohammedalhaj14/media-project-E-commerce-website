# 🎬 MediaStream E-Commerce Platform

[![PHP Version](https://img.shields.io/badge/php-%5E7.4%20%7C%20%5E8.0-blue.svg)](https://www.php.net/)
[![Stripe Integration](https://img.shields.io/badge/Payments-Stripe-6772e5.svg)](https://stripe.com/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

A high-performance, full-stack digital marketplace designed for media distribution. This platform integrates secure user authentication, dynamic inventory management, and a robust checkout flow powered by the Stripe API.

---

## 🔗 Live Demo

**[media-shop.infinityfree.io](http://media-shop.infinityfree.io)**

> ⚠️ Running in Stripe **test mode**. Use card `4242 4242 4242 4242`, any future expiry date, any CVC, and any ZIP code to complete a test purchase.

---

## ⚡ Core Functionality

* **Secure Authentication:** Full user lifecycle with session management and password protection.
* **Dynamic Cart System:** Real-time updates to user selections using PHP sessions.
* **Stripe Integration:** Industry-standard secure payment processing for digital goods.
* **Responsive UI:** Built with Bootstrap 5 to ensure a seamless experience on all devices.

---

## 🛠️ Technical Stack

| Component    | Technology                 |
| :----------- | :-------------------------- |
| **Language** | PHP 8.x, JavaScript (ES6+) |
| **Styling**  | Bootstrap 5, CSS3          |
| **Database** | MySQL                      |
| **Payments** | Stripe API                 |

---

## ⚙️ Quick Start

1. **Clone:**

   ```bash
   git clone https://github.com/mohammedalhaj14/media-project-E-commerce-website.git
   ```

2. **Database:**

   * Import `media.sql` into phpMyAdmin.

3. **Configuration:**

   * Update `connection.php` with your database credentials.

4. **Stripe Setup:**

   * Create a `stripe-config.php` file (not tracked in Git) with your Stripe secret and publishable keys.

---

## 📄 License

Distributed under the **MIT License**. This allows for free use, modification, and distribution of the software.
