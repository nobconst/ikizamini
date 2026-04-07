# 🚗 ProviSor Exam System

A complete, professional-grade driving examination platform for Africa built with PHP, MySQL, HTML, CSS, and JavaScript.

## ✨ Features

### 📚 Core Features
- **20-Question Tests**: Comprehensive exams covering all essential driving topics
- **Timed Tests**: 20-minute realistic exams matching real driving tests
- **Multi-Language Support**: Available in English, French, and Kinyarwanda
- **5 Categories**: Road Signs, Rules, Safety, Vehicle Maintenance, Parking
- **Detailed Results**: View correct/incorrect answers with explanations

### 💰 Payment System
- **Mobile Money Integration**: MTN MoMo, Airtel Money, Equity Bank
- **Flexible Plans**: Test-based (2/6/12 tests) and time-based (daily/weekly/monthly)
- **Instant Activation**: Access tests immediately after payment

### 🔒 Security Features
- **Anti-Cheating System**:
  - Prevent right-click and copy/paste
  - Detect tab switching with warnings
  - Prevent browser developer tools
  - Server-side timer validation
  - Auto-submit on time expiration
  - Prevent multiple test sessions
  
- **Authentication**: Bcrypt password hashing, session management
- **Access Control**: Role-based (user/admin/super_admin)

### 📊 Dashboard & Analytics
- **User Dashboard**: Monitor credits, recent tests, average scores
- **Admin Panel**: Manage users, questions, payments, categories
- **Reports**: Most failed questions, user performance metrics
- **Progress Tracking**: Detailed test history and statistics

### 🎨 User Interface
- **Responsive Design**: Works perfectly on desktop, tablet, mobile
- **Modern CSS**: Professional gradient backgrounds, smooth animations
- **Accessibility**: Clear navigation, intuitive controls
- **Keyboard navigation**: Arrow keys for test navigation

## 🗂️ Project Structure

```
/project-root
├── /app
│   ├── /controllers          # Business logic
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── TestController.php
│   │   ├── PaymentController.php
│   │   ├── AdminController.php
│   │   └── HomeController.php
│   │
│   ├── /models              # Database interactions
│   │   ├── User.php
│   │   ├── Question.php
│   │   ├── Test.php
│   │   └── Payment.php
│   │
│   └── /views               # HTML templates
│       ├── layout.php
│       ├── home.php
│       ├── auth/login.php
│       ├── auth/register.php
│       ├── dashboard/
│       ├── test/
│       ├── payment/
│       ├── admin/
│       └── errors/
│
├── /core
│   ├── Router.php           # URL routing
│   ├── Database.php         # Database connection
│   └── Controller.php       # Base controller class
│
├── /config
│   └── config.php           # Environment configuration
│
├── /public
│   ├── index.php            # Entry point
│   ├── .htaccess            # Clean URLs
│   └── /assets
│       ├── /css/style.css   # Responsive styling
│       ├── /js/main.js      # Client-side logic
│       └── /images/         # Question images
│
└── database.sql             # Database dump
```

## 🚀 Setup Instructions

### Prerequisites
- XAMPP (Apache, MySQL, PHP 7.4+)
- Web browser

### 1. Database Setup

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Create a new database (optional, will be auto-created)
3. Import the SQL file:
   - Click "Import" tab
   - Select `database.sql`
   - Click "Go"

Or run manually in MySQL:
```bash
mysql -u root -p < database.sql
```

### 2. File Setup

1. Navigate to `C:\xampp\htdocs\`
2. Extract/place the project in `ikizamini/` folder
3. Folder structure should be:
```
C:\xampp\htdocs\ikizamini\
├── app/
├── config/
├── core/
├── public/
├── database.sql
└── .gitattributes
```

### 3. Configure Database Connection

Edit `config/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Leave empty if no password
define('DB_NAME', 'provisor_exam');
```

### 4. Start Server

1. Start Apache and MySQL in XAMPP Control Panel
2. Open browser: `http://localhost/ikizamini/public/`

## 📝 Default Users

### Test User Account
- Phone: `0780123456`
- Password: `password`
- Role: User

### Admin Account
First, register a new account, then in MySQL change role:
```sql
UPDATE users SET role = 'admin' WHERE id = 1;
```

## 🔧 Configuration

Edit `config/config.php` to customize:

```php
// Site Settings
define('SITE_NAME', 'ProviSor Exam');
define('SITE_URL', 'http://localhost/ikizamini');

// Test Settings
define('TEST_DURATION', 1200);        // 20 minutes in seconds
define('TOTAL_QUESTIONS', 20);        // Questions per test
define('PASS_SCORE', 16);             // 80% to pass (16/20)

// Payment Settings
define('MOBILE_MONEY_API', 'https://api.example.com');
```

## 🌐 URLs & Routes

### Public Routes
- `/` - Home page
- `/about` - About page
- `/pricing` - Pricing page
- `/auth/login` - Login
- `/auth/register` - Register

### User Routes (Requires Login)
- `/dashboard` - User dashboard
- `/dashboard/profile` - Edit profile
- `/dashboard/history` - Test history
- `/test` - Start test
- `/test/take` - Take test
- `/test/result/{id}` - View results
- `/payment` - View plans
- `/payment/checkout/{id}` - Checkout
- `/payment/history` - Payment history

### Admin Routes (Requires Admin)
- `/admin/dashboard` - Admin home
- `/admin/users` - Manage users
- `/admin/blockUser/{id}` - Block user
- `/admin/questions` - Manage questions
- `/admin/question/add` - Add question
- `/admin/categories` - Manage categories
- `/admin/payments` - View payments
- `/admin/reports` - Analytics

## 🎓 Adding Sample Questions

Login as admin and:
1. Go to `/admin/question/add`
2. Fill in question text (EN, FR, RW)
3. Add 4 answers
4. Mark the correct answer
5. Submit

Or import directly into MySQL:

```sql
-- Add a question
INSERT INTO questions (category_id, image, difficulty) VALUES (1, NULL, 'medium');

-- Add translations
INSERT INTO question_translations (question_id, language, question_text) VALUES
(1, 'en', 'What does this red octagon sign mean?'),
(1, 'fr', 'Que signifie ce panneau octogone rouge?'),
(1, 'rw', 'Iki kinini cyo gikunimba kigende kusobanura iki?');

-- Add answers
INSERT INTO answers (question_id, is_correct) VALUES
(1, FALSE), (1, FALSE), (1, TRUE), (1, FALSE);

-- Add answer text
INSERT INTO answer_translations (answer_id, language, answer_text) VALUES
(1, 'en', 'Speed limit'),
(2, 'en', 'Yield'),
(3, 'en', 'Stop'),
(4, 'en', 'Do not enter');
```

## 🔐 Security Best Practices Applied

✅ **Password Security**
- Bcrypt hashing (12 rounds)
- Secure password storage

✅ **Cheating Prevention**
- Server-side timer (client-side cannot be bypassed)
- Anti-refresh mechanisms
- Tab switching detection
- Developer tools locked
- Copy/paste disabled during tests
- Auto-submit on time expiration

✅ **Access Control**
- Role-based authorization
- Login required for protected routes
- Session validation
- User status (active/blocked)

✅ **Data Protection**
- Prepared SQL statements (prevent SQL injection)
- Input validation
- Output escaping (XSS prevention)
- CSRF protection (via session)

✅ **API Security**
- No sensitive data in URLs
- Server-side validation only
- Payment verification on backend
- Activity logging

## 🚀 Deployment (Production)

### Before Going Live

1. **Security**
   - Change `session.cookie_secure = On` in php.ini
   - Use HTTPS (SSL certificate)
   - Keep dependencies updated

2. **Performance**
   - Enable query caching
   - Add database indexes
   - Minify CSS/JS
   - Use CDN for assets

3. **Backup**
   - Regular database backups
   - File backups
   - Version control (Git)

4. **Monitoring**
   - Check server logs regularly
   - Monitor payment transactions
   - Track failed logins
   - Alert on errors

### Deploy to Hosting

1. Use FTP/SFTP to upload files
2. Import database on hosting server
3. Update `config.php` with hosting details
4. Set file permissions (755 for dirs, 644 for files)
5. Enable mod_rewrite in Apache

## 📊 Database Backup & Restore

### Backup
```bash
mysqldump -u root -p provisor_exam > backup.sql
```

### Restore
```bash
mysql -u root -p provisor_exam < backup.sql
```

## 🐛 Troubleshooting

### "Database Connection Error"
- Ensure MySQL is running
- Verify credentials in `config/config.php`
- Check database exists

### "404 Not Found"
- Ensure `.htaccess` is in `/public/`
- Enable mod_rewrite: `a2enmod rewrite`
- Restart Apache

### "Timer runs out instantly"
- Check server timezone in `php.ini`
- Verify current time on server: `date('Y-m-d H:i:s')`

### "Payment not processing"
- Check payment API credentials
- Verify mobile money account
- Check payment logs in database

## 📱 Mobile Money Integration

To connect real payment providers:

1. **MTN MoMo API**:
   - Register at: https://mtn-uganda.developer.orange.com/
   - Update API endpoint in `PaymentController.php`

2. **Airtel Money**:
   - Register at: https://developer.airtel.com/
   - Configure credentials

3. **Equity Bank**:
   - Register at: https://www.equitybank.co.ke/
   - Integrate payment link

## 📞 Support

For issues or questions:
- Check [database.sql](database.sql) for schema
- Review error logs in `/logs/`
- Check browser console for JavaScript errors
- Test with sample data first

## 📄 License

This project is provided as-is for educational and commercial use.

## 🎉 Features You Can Add

- Leaderboard 🏆
- Certificates 📜
- SMS notifications 💬
- Email receipts 📧
- Dark mode 🌙
- Question explanation AI 🤖
- Video tutorials 📹
- Social sharing 📤
- Referral system 👥

---

Built with ❤️ for African drivers 🚗
