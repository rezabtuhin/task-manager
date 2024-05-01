# Task Manager

Task Manager is a web application designed to manage tasks assigned to different users with task priority and email confirmation.

# Features

1. **Basic Authentication**:
   - Basic Auth for Web & APIs are implemented, API call needs those token to access the resources.

2. **Task Management**:
   - Users can create tasks and assign to different users even to themselves.
   - Each tasks contain `title`, `description`, `priority`, `assignee`, `image` (Multiple), and `status`.
   - Status Options: `New`, `In Progress`, `Testing`, `Deployed`.
   - Once a ticket is created, the initial status is set to New and can be immediately changed to the next status by editing the task.
   - Once a task moves to a status other than `New`, users can't change the status for 15 minutes.
   - Once the status is `Deployed` state, users can no longer change the status.
   - Delete operation uses Soft Delete.

3. **Caching**:
   - Page content or Task model is cached to reduce DB calls using Redis.

4. **Email Notification**:
   - Users receive an email when a task is assigned to them. The email sending mechanism is implemented separately to update the task creator instantly without waiting for response.

5. **Logging**:
   - User information of who/when created/updated/deleted a task via web/api is logged, including their IP address and user-agent record.

# Additional Features
- API makes use of REST API Standards.
- A single token can be used to make a maximum of 200 API calls in 24 hours.
- Create API does not accept more than 3 concurrent requests within 5 minutes from the same user/token.

- Status Change API behaves like the web, allowing immediate changes only when the status is New.
- For any other status, changes are not allowed within 15 minutes, and once the status is Deployed, no further changes are allowed.

# Technologies Used

- PHP
- Laravel
- MySQL
- TailwindCSS
- Redis
- Mailtrap
- jQuery
- Nodejs
- Composer

# Project Directory Structure
1. **/app:** Core code including models, controllers, middleware.
2. **/config:** Configuration files (database, caching).
3. **/database:** Migration, seed, factory files.
4. **/public:** Entry point (`index.php`), assets.
5. **/resources:** Views, language files, frontend assets.
6. **/routes:** URL-to-action mappings (Both web & api).
7. **/storage:** Generated files (logs, cached views, uploads).
8. **/vendor:** Composer dependencies.
9. **.env:** Environment-specific settings.

# MVC Architecture Overview
The architecture of this project follows the Model-View-Controller (MVC) pattern, which helps organize code into logical components for better maintainability and scalability.

**Controllers**
 - **API Controllers:** Responsible for providing resources via API calls.

- **Auth Controllers:** Handle user authentication.

- **Pages Controllers:** Serve basic resource pages.

**Models**
- Models interact with the database and encapsulate business logic.

**Views**
- **Components Views:** Provide universal layouts for all pages.

- **Auth Views:** Render pages for authentication.

- **Email Views:** Contain email templates.

- **Pages Views:** Render basic pages.

This architecture separates concerns and promotes code organization, making it easier to maintain and extend the application.

**Dependencies are manages by composer `(php)` and npm `(Javascript)`**

# Database Schema Overview

The database schema consists of the following tables:

1. **users:**  Stores user information. Each user can have multiple tokens.

2. **tokens:** Stores authentication tokens, each linked to a user.

3. **tasks:**
   - Stores task details like title, description, priority, and status.
   - Includes relationships with the users table for assignee, created_by, and updated_by fields.

4. **task_logs:** Records task-related events, including task ID, user ID, event type, IP address, and user agent.

5. **task_images:** Stores paths to images associated with tasks, linked via task ID.


# Server Requirements
- PHP 8.1 or higher.
- Mysql 8 or higher.
- Composer 2.7.4 or higher.
- Node 20.12.2 or higher.
- npm 10.5.0 or higher.
- Redis 7.0.15 or higher.
- Apache or Nginx.
- PHP extension like (`pdo-mysql`, `ext-xml`, `gzip`, `php-mysql`).

# Set-up Development Environment Locally

Your machine must have installed `PHP`, `Composer`, `MySQL`, `git`, `node`, `npm`, `Redis`, `Apache or Nginx`, and Required php extension mentioned above in the `Server Requirements` section.

```sh
git clone https://github.com/rezabtuhin/task-manager.git
cd task-manager
```
```sh
composer install
```

```sh
npm install
```
```sh
npm run build
```

```sh
cp .env.example .env
```

Set up the database in the `.env` file and your `MySQL` server and other configurations.

**Setup the database**
```sh
DB_CONNECTION=mysql
DB_HOST={{ YOUR HOST }}
DB_PORT={{ YOUR PORT }}
DB_DATABASE={{ YOUR DATABASE NAME }}
DB_USERNAME={{ YOUR DATABASE USERNAME }}
DB_PASSWORD={{ YOUR DATABASE PASSWORD }}
```
**Now Create a database in your mysql server as per the `DB_DATABASE`.**

**Setup the Mail configuration (We suggest you to use Mailtrap for development purpose)**
```sh
MAIL_MAILER=smtp
MAIL_HOST={{ YOUR MAIL HOST }}
MAIL_PORT={{ YOUR MAIL PORT }}
MAIL_USERNAME={{ YOUR MAIL USERNAME }}
MAIL_PASSWORD={{ YOUR MAIL PASSWORD }}
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="{{ YOUR MAIL FROM ADDRESS }}"
MAIL_FROM_NAME="{{ YOUR MAIL FROM NAME }}"
```

**Setup the Redis**

```sh
REDIS_HOST={{ YOUR REDIS HOST }}
REDIS_PASSWORD={{ YOUR REDIS PASSWORD }}
REDIS_PORT={{ YOUR REDIS PORT }}
```

**Generate Secret Key**
```sh
php artisan key:generate
```

**Database Migration**
```sh
php artisan migrate:fresh --seed
```
After this migration command some data will be stored in the users table. Which includes the User email and password.
you can login with the any of those emails and the initial password is `password`.

**Now you have to run these three command in three separate shell at a time**

```sh
npm run dev
```
```sh
php artisan serve
```

```sh
php artisan queue:work
```

**Now you can access the application.**