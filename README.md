# 🎬 MediaStream E-Commerce Platform

A high-performance, full-stack digital marketplace designed for media distribution. This platform integrates secure user authentication, dynamic inventory management, and a robust checkout flow powered by the Stripe API.

**[Explore the Repository »](https://github.com/mohammedalhaj14/Media-E-Commerce-Project-PHP)**

---

## 🏛️ System Architecture

The application follows a modular **Client-Server architecture** utilizing:

* **Frontend:** A responsive SPA-like experience using **Bootstrap 5** and **Vanilla JS**.
* **Backend:** Procedural and functional **PHP** for server-side logic and session handling.
* **Data Layer:** **MySQL** relational database for ACID-compliant transactions.
* **Payment Gateway:** **Stripe SDK** for PCI-compliant payment processing.

---

## 🖼️ Interface Gallery

### 🖥️ Desktop Experience

| **Discovery Portal** | **Media Insights** |
| --- | --- |
|  |  |

### 🔐 User Lifecycle & Commerce

| Sign In | Create Account | Shopping Cart |
| --- | --- | --- |
|  |  |  |

---

## ⚡ Core Functionality

### 🛒 Commerce Engine

* **Dynamic Cart Persistence:** Real-time updates to user selections using PHP sessions.
* **Secure Checkout:** Seamless redirection to Stripe-hosted checkout or elements for secure card handling.

### 🔍 Discovery & Navigation

* **Contextual Search:** Efficient querying of media metadata.
* **Responsive Design:** Mobile-first approach ensuring compatibility across all device viewports.

### 🛡️ Security & Performance

* **Authentication Guard:** Password hashing and protected route middleware.
* **Optimized Queries:** Indexed MySQL lookups for fast media retrieval.

---

## 🛠️ Technical Stack

| Component | Technology |
| --- | --- |
| **Language** | PHP 8.x, JavaScript (ES6+) |
| **Styling** | Bootstrap 5, CSS3 Custom Properties |
| **Database** | MySQL |
| **API** | Stripe Payments API |
| **Environment** | Apache/Nginx |

---

## ⚙️ Installation & Deployment

### Prerequisites

* **XAMPP / WAMP** or a standalone PHP/MySQL environment.
* **Stripe API Keys** (Test mode recommended for initial setup).

### Setup Process

1. **Clone & Navigate:**
```bash
git clone https://github.com/mohammedalhaj14/Media-E-Commerce-Project-PHP.git
cd Media-E-Commerce-Project-PHP

```


2. **Database Migration:**
* Create a database named `media_store`.
* Import `database.sql` via phpMyAdmin or CLI:
```sql
mysql -u username -p media_store < database.sql

```




3. **Environment Configuration:**
* Edit `db_config.php` with your local database credentials.
* Add your Stripe Secret/Publishable keys in `checkout.php`.



---

## 🛤️ Roadmap & Future Enhancements

* [ ] **Admin Dashboard:** Full CRUD operations for media inventory.
* [ ] **Review System:** User-generated ratings and feedback for movies.
* [ ] **Email Automation:** Transactional emails via SendGrid for order receipts.
* [ ] **Wishlist:** Ability for users to save media for later purchase.

## 📄 License

Distributed under the MIT License. See `LICENSE` for more information.
