# Harbor General — Hospital Management System

A multi-page hospital/clinic management web application built with HTML, CSS, JavaScript, PHP and MySQL, for **Harbor General**.

---

## 1. Project Structure

```
harbor-general/
├── index.php              Public homepage
├── about.php               About page (mission, vision, values)
├── services.php             Services / departments page
├── doctors.php               Public medical team page
├── contact.php                Contact page + working contact form
├── login.php                   Staff login
├── logout.php                  Ends the staff session
├── dashboard.php                Staff dashboard (stats + today's appointments)
├── patients.php                  Patient list + search
├── patient_add.php                Register a patient
├── patient_edit.php                Edit a patient
├── patient_view.php                 Patient profile + appointment history
├── doctors_manage.php               Doctor list, search/filter, delete
├── doctor_add.php                     Add a doctor
├── doctor_edit.php                     Edit a doctor
├── appointments.php                     Appointment list, filters, status update
├── appointment_add.php                   Book an appointment
├── includes/
│   ├── db.php                              Database connection (PDO)
│   ├── auth.php                             Session/auth helper functions
│   ├── header.php / footer.php               Public site layout
│   └── app_header.php / app_footer.php         Staff dashboard layout (sidebar)
├── css/style.css                               All styling
├── js/script.js                                 Mobile nav, sidebar, alerts, confirm dialogs
└── database.sql                                  Full schema + seed data
```

## 2. Database Setup (XAMPP + phpMyAdmin)

1. Start **Apache** and **MySQL** in the XAMPP Control Panel.
2. Open `http://localhost/phpmyadmin`.
3. Click **Import**, choose `database.sql`, and click **Go**.
   - This creates the `harbor_general` database with all tables (`staff`, `patients`, `doctors`, `appointments`, `contact_messages`) and seed data.
4. Default staff login:
   - **Username:** `admin`
   - **Password:** `admin123`

If your MySQL root user has a password, update `includes/db.php`:
```php
$DB_HOST = 'localhost';
$DB_NAME = 'harbor_general';
$DB_USER = 'root';
$DB_PASS = '';   // <-- set your password here
```

⚠️ Before going live, change the default staff passwords and remove or replace the seed accounts in `database.sql`.

## 3. Running the Project

1. Copy the `harbor-general` folder into your XAMPP `htdocs` directory, e.g.:
   - Windows: `C:\xampp\htdocs\harbor-general`
   - Mac: `/Applications/XAMPP/htdocs/harbor-general`
2. Visit `http://localhost/harbor-general/index.php` in your browser.
3. To access the management system, click **Staff Login** and sign in with the credentials above.

## 4. Core Workflow

**Public website:**
Home → About → Services → Doctors → Contact → Staff Login

**Management system:**
1. Log in as staff.
2. View the Dashboard for live statistics and today's appointments.
3. Register a new patient (Patients → Register Patient).
4. Add a doctor (Doctors → Add Doctor).
5. Book an appointment (Appointments → Book Appointment).
6. Track it under "Today's Appointments" on the Dashboard or Appointments page.
7. Update its status (Scheduled → Completed, etc.) using the dropdown in the Appointments table.
8. Open a patient's profile (Patients → View) to review their appointment history.
9. Log out when finished.

## 5. Notes

- Passwords are hashed with PHP's `password_hash()` / verified with `password_verify()`.
- All database queries use PDO prepared statements.
- Protected pages (`dashboard.php`, `patients*.php`, `doctor*.php`, `appointment*.php`) redirect to `login.php` if not authenticated.
- A doctor cannot be deleted while they still have appointments on record (referential integrity).
- The services/departments list on `services.php` is defined directly in the page for simplicity; it can be moved to a database table later if desired.
