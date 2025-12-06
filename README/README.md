Media E-Commerce Platform


📌 Overview
This project is a media-based e-commerce website that allows users to browse, search, and purchase digital media content. The platform includes user authentication (login/registration), a shopping cart, and payment integration using Stripe. Built with HTML, CSS, JavaScript, Bootstrap, and PHP, it delivers a seamless shopping experience for media enthusiasts.

🚀 Features
✅ User Authentication – Secure login and registration system.
✅ Search & Browse – Users can search for movies and browse by category.
✅ Add to Cart – Users can add media items to their cart before purchasing.
✅ Stripe Payment Integration – Secure online payments for media purchases.
✅ Responsive Design – Optimized for mobile and desktop users.
✅ Dynamic Content – Media items dynamically loaded from the database.

🛠️ Technologies Used
Frontend: HTML5, CSS3, JavaScript, Bootstrap
Backend: PHP (Handles authentication, cart management, and payments)
Database: MySQL (Stores user data, media items, and transactions)
Payment Gateway: Stripe API
📂 Project Structure
graphql
Copy
Edit
/media-ecommerce
│── index.html          # Homepage  
│── style.css           # Custom CSS styles  
│── script.js           # JavaScript logic  
│── login.php           # User login functionality  
│── register.php        # User registration  
│── cart.php            # Shopping cart page  
│── checkout.php        # Stripe payment processing  
│── db_config.php       # Database connection settings  
│── assets/             # Images and other assets  
│── bootstrap/          # Bootstrap library files  
│── stripe/             # Stripe API integration files  
🎯 How to Run the Project
Clone the Repository

bash
Copy
Edit
https://github.com/mohammedalhaj14/Media-E-Commerce-Project-PHP
Set Up the Database

Import the provided database.sql file into MySQL.
Update db_config.php with your database credentials.
Configure Stripe API

Sign up on Stripe and get your API keys.
Update the Stripe API keys in checkout.php.
Run the Project Locally

Start a local server using XAMPP or WAMP.
Place the project files inside htdocs/ (for XAMPP).
Open http://localhost/Media-E-Commerce-Project-PHP/ in your browser.
