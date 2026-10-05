# Hostel Room Booking Web Application Documentation

## 1. Installation & Setup Guide

### Requirements
- **Local Server Environment**: XAMPP, WAMP, or similar (Apache & MySQL).
- **PHP Version**: PHP 7.4 or higher.
- **Database**: MySQL.

### Setup Instructions
1. **Move Project**: Place the entire `hostal room booking web application` folder inside your server's root directory.
   - For XAMPP: `C:\xampp\htdocs\hostal room booking web application`
   - For WAMP: `C:\wamp64\www\hostal room booking web application`
2. **Start Server**: Open your XAMPP/WAMP control panel and start the **Apache** and **MySQL** services.
3. **Database Import**:
   - Open your browser and navigate to `http://localhost/phpmyadmin`
   - Create a new database named `hostel_booking`.
   - Select the newly created database, go to the **Import** tab.
   - Choose the file `database/hostel_booking.sql` from the project folder and click **Go** to import all tables and default data.
4. **Configuration Check**: 
   - Open `config/database.php`.
   - Ensure the `$host`, `$db_name`, `$username`, and `$password` variables match your MySQL setup.
   - Ensure `BASE_URL` is set to `'/hostal room booking web application'` (or match your exact folder name).
5. **Access Application**:
   - Public facing website: `http://localhost/hostal room booking web application/`
   - Admin Panel: `http://localhost/hostal room booking web application/admin/login.php`
6. **Default Admin Credentials**:
   - **Username**: admin
   - **Password**: password123

---

## 2. System Workflow

The system is split into three main modules:

### Guest Workflow (Public)
- **Browse**: Guests can visit the Home, About, FAQ, Contact, and Rooms pages without logging in.
- **Search**: Guests can use the live search on the Rooms page to filter rooms.
- **Register**: To make a booking, a guest must create an account by providing basic details.

### User Workflow (Authenticated)
- **Login**: Users authenticate with their email and password.
- **Book a Room**: Users view Room Details, select check-in and check-out dates, and submit a booking request. This calculates the total price dynamically.
- **Dashboard**: Users have a personalized dashboard to track their active stays and total bookings.
- **Booking History**: Users can view all past and present bookings, cancel pending bookings, and check their payment status.
- **Profile**: Users can update their name, phone number, and change their password.

### Admin Workflow (Authenticated)
- **Login**: Admins log in via a dedicated secure portal.
- **Dashboard**: Gives a high-level statistical overview of total rooms, users, bookings, and revenue.
- **Room Management**: Full CRUD capabilities to Add, Edit, and Delete rooms, including uploading images and managing availability status.
- **Booking Management**: Admins can approve or reject pending user bookings.
- **Payment Management**: Track and update payment statuses from Pending to Paid or Failed.
- **User Management**: Monitor registered users and remove inactive accounts if necessary.
- **Messages & Reports**: Read contact form submissions and view monthly revenue trends.

---

## 3. Database Schema & ER Diagram Explanation

The database consists of 7 interconnected tables.

### Tables Breakdown
1. **users**: Stores registered customer data (id, name, email, phone, password, timestamps).
2. **admins**: Stores administrative credentials securely.
3. **rooms**: Holds room inventory (id, room_number, title, type, description, price, capacity, availability, facilities).
4. **bookings**: Links users and rooms (id, user_id, room_id, check_in, check_out, total_price, statuses). Contains Foreign Keys referencing `users(id)` and `rooms(id)`.
5. **payments**: Tracks financial transactions linked to bookings (id, booking_id, amount, method, status). Contains a Foreign Key referencing `bookings(id)`.
6. **feedback**: Allows users to leave ratings and messages (linked to user_id).
7. **contact_messages**: Stores generic inquiries from the public contact form.

### Entity Relationship (ER) Flow
- A **User** can have multiple **Bookings** (1:N relationship).
- A **Room** can have multiple **Bookings** over time, but ideally only one active booking at a given date (1:N relationship).
- A **Booking** has exactly one **Payment** record associated with it initially (1:1 relationship).
- A **User** can leave multiple **Feedbacks** (1:N relationship).

---

## 4. User Manual

1. **Creating an Account**: Navigate to "Register" from the top navigation bar. Fill in your details.
2. **Booking a Room**: Go to the "Rooms" page. Click "Details" on a room. Select your dates. The system will calculate your price. Click "Proceed to Book".
3. **Checking Status**: Go to "Dashboard" -> "Booking History". Here you can see if the admin has approved your booking and if your payment is marked as paid.
4. **Cancelling**: In the "Booking History", you can click "Cancel" on any booking that is still in "Pending" status.
5. **Updating Info**: Go to "My Profile" to update your phone number or "Change Password" to secure your account.

---

## 5. Admin Manual

1. **Accessing the Panel**: Navigate to `/admin/login.php` and use the provided admin credentials.
2. **Adding a New Room**: Click "Manage Rooms" -> "Add New Room". Fill in the form. Upload a clear, high-quality image of the room.
3. **Approving Bookings**: Go to "Manage Bookings". You will see all user requests. Click "Approve" to secure the room for the user.
4. **Managing Payments**: Once a user pays offline or via a third-party gateway, go to "Payments" and click "Mark Paid". This updates the revenue stats.
5. **Reviewing Messages**: Check "Contact Messages" daily to respond to user inquiries.

---

## 6. Testing Guide

To verify the system is fully operational:
1. **Auth Test**: Register a new user account. Log out. Log back in with the new credentials. Test "Forgot Password" to ensure it updates the database.
2. **Admin Test**: Log in as admin. Create a new room with an image. Verify it appears on the public `rooms.php` page.
3. **Booking Flow Test**: As a user, book the newly created room. Ensure the total price calculation is correct based on the dates.
4. **Approval Flow Test**: As an admin, go to bookings and Approve the new booking. Ensure the status updates on both the admin panel and the user's dashboard.
5. **Validation Test**: Try to register with an existing email, try to login with a wrong password, try to add a room with a duplicate room number. Error messages should gracefully handle these scenarios.
