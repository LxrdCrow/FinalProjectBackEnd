# Final Project: RESTful API with JWT Authentication

This project is a backend implementation of a RESTful API for user management, including user registration, login, and protected routes. It uses JSON Web Tokens (JWT) for authentication and relies on a MySQL database for storing user data.

## Features

- **User Registration**: Allows new users to register by providing a username, email, and password.
- **User Login**: Authenticates users and provides a JWT token for accessing protected routes.
- **Protected Routes**: Routes that require a valid JWT token for access.
- **Profile Update**: Users can update their profile information.
- **Error Logging**: In production mode, errors are logged to a file in the `logs/` directory.

## Technologies Used

- **PHP** (7.4 or 8.0)
- **MySQL** for database
- **PDO** for database interaction
- **Firebase JWT** for authentication
- **PHP dotenv** for environment variable management
- **Composer** for dependency management

## Installation

1. **Clone the repository**:

   ```bash
   git clone https://github.com/username/repository.git
   cd repository
   ```

2. **Install Dependencies**:

   Make sure you have [Composer](https://getcomposer.org/) installed, then run:

   ```bash
   composer install
   ```

3. **Environment Setup**:

   Create a `.env` file in the root directory and set up your environment variables:

   ```env
   DB_HOST=localhost
   DB_NAME=your_database_name
   DB_USER=your_database_user
   DB_PASS=your_database_password
   SECRET_KEY=your_secret_key
   APP_ENV=development  # or production
   ```

4. **Database Setup**:

   Run the SQL migration to create the required tables:

   ```bash
   mysql -u your_database_user -p your_database_name < migrations.sql
   ```

5. **Permissions**:

   Ensure that the `logs/` directory has the correct permissions for logging errors:

   ```bash
   mkdir logs
   chmod 755 logs
   ```

## Usage

- **Run the API locally**:

   Start your local server (e.g., with XAMPP or another PHP development environment) and access the API.

- **Registration**:

   ```http
   POST /api/register
   {
      "username": "user123",
      "email": "user@example.com",
      "password": "password123"
   }
   ```

- **Login**:

   ```http
   POST /api/login
   {
      "email": "user@example.com",
      "password": "password123"
   }
   ```

   A successful login will return a JWT token.

- **Protected Routes**:

   Use the token in the `Authorization` header to access protected routes:

   ```http
   GET /api/protected
   Authorization: Bearer your_jwt_token
   ```

## Testing

You can run unit tests using [PHPUnit](https://phpunit.de/):

```bash
composer test
```

## Code Quality

The project uses [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) for coding standard checks and auto-fixes:

- **Lint the code**:

   ```bash
   composer lint
   ```

- **Fix coding style**:

   ```bash
   composer fix
   ```

## License

This project is licensed under the MIT License.



