# Banking Web Application

This project is a simple web-based banking application that allows users to register, log in, view their account balance, and perform transactions like deposits and withdrawals. 
It is built using PHP, MySQL, HTML, CSS, and JavaScript.

[Download the demo video](https://github.com/Nilesh896/Bank_Management-_System/blob/68f3b98b45293c63b5f0bd744c1e9b8d4b28243d/output.mp4)

---

## Features

- **User Registration:**
  - New users can register by providing a username, email, password, and initial balance.

- **User Login:**
  - Existing users can log in using their credentials.

- **Dashboard:**
  - Displays the user's current balance.
  - Allows deposits and withdrawals after password confirmation.
  - Displays a transaction history (withdrawals and deposits).

- **Transaction History:**
  - Shows all past transactions with type (deposit/withdrawal), amount, and date.

- **Security Features:**
  - Passwords are hashed using `password_hash()` for secure storage.
  - Session-based authentication to prevent unauthorized access.

---

## File Structure

```
project-folder/
├── db/
│   ├── db_connect.php       # Database connection file
├── styles/
│   ├── style.css            # CSS for styling the application
├── index.php                # Login page
├── register.php             # Registration page
├── dashboard.php            # User dashboard
├── README.md                # Project documentation
└── transactions_handler.php # Handles deposit and withdrawal logic
```

---

## Installation

1. Clone this repository:
   ```bash
   git clone https://github.com/Nilesh896/your-repository.git
   ```

2. Navigate to the project folder:
   ```bash
   cd Bank_login_system
   ```

3. Set up the database:
   - Import the provided SQL file (`database.sql`) into your MySQL server.
   - Ensure you have a `users` table and a `transactions` table as described in the schema.

4. Update the database connection:
   - Open `db/db_connect.php` and configure your database credentials:
     ```php
     $servername = "localhost";
     $username = "root";
     $password = "";
     $dbname = "bank_system";
     ```

5. Start a local PHP server:
   ```bash
   php -S localhost:3000
   ```

6. Open your browser and visit:
   ```
   http://localhost:3000
   ```

---

## Usage

1. **Register:**
   - Go to the registration page and create a new account.

2. **Login:**
   - Use your registered credentials to log in.

3. **Dashboard:**
   - View your balance, perform deposits or withdrawals, and view transaction history.

4. **Logout:**
   - Click the "Logout" button to end your session.

---

## Database Schema

### `users` Table
| Column      | Type         | Description             |
|-------------|--------------|-------------------------|
| id          | INT (Primary Key) | Unique user ID         |
| username    | VARCHAR(50)  | Username                |
| password    | VARCHAR(255) | Hashed password         |
| email       | VARCHAR(100) | User's email address    |
| balance     | DECIMAL(10,2)| Current account balance |

### `transactions` Table
| Column      | Type         | Description                      |
|-------------|--------------|----------------------------------|
| id          | INT (Primary Key) | Unique transaction ID         |
| user_id     | INT          | Foreign key referencing `users` |
| type        | ENUM('deposit', 'withdraw') | Transaction type |
| amount      | DECIMAL(10,2)| Transaction amount              |
| created_at  | TIMESTAMP    | Timestamp of the transaction    |

---

## Future Enhancements

- Add email notifications for transactions.
- Implement two-factor authentication for enhanced security.
- Add an admin panel for managing users and transactions.
- Enhance UI/UX for a better user experience.

---

## License

This project is licensed under the MIT License. You are free to use, modify, and distribute this project as per the terms of the license.

---

## Acknowledgments

- Thanks to the PHP and MySQL documentation for guidance.
- Inspired by real-world banking systems for functionality design.

---

## Contact

For any queries or suggestions, please contact:
- **Name:** Nilesh Yadav 
- **Email:** ny069656@gmail.com
- **GitHub:** [Your GitHub Profile](https://github.com/Nilesh896)

