# Final Project BackEnd

This is the back-end implementation of the Final Project for managing user authentication, registration, and protected routes. The project uses PHP, MySQL, and JWT for secure authentication.

## Features

- **User Registration**: Users can register by providing a username, email, and password.
- **User Login**: Users can log in and receive a JWT token for secure access.
- **Protected Routes**: Routes that require authentication are protected using JWT tokens.
- **Error Handling**: Configurable error handling based on the environment (development/production).
- **Logs**: Errors are logged in production mode to `logs/error.log`.

## Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/LxrdCrow/FinalProjectBackEnd.git
   ```

2. **Navigate to the project directory**:
   ```bash
   cd FinalProjectBackEnd
   ```

3. **Install the required dependencies**:
   ```bash
   composer install
   ```

4. **Set up your environment variables**:
   Create a `.env` file in the root of your project and configure your database and other settings:
   ```
   DB_HOST=localhost
   DB_NAME=your_database_name
   DB_USER=your_database_user
   DB_PASS=your_database_password
   SECRET_KEY=your_secret_key
   APP_ENV=development # or production
   ```

5. **Run the database migrations**:
   Make sure you have created the necessary database, then run the SQL migration:
   ```bash
   mysql -u your_username -p your_database_name < migrations.sql
   ```

6. **Set file permissions for the logs directory**:
   ```bash
   chmod 755 logs
   ```

## Usage

- **Starting the server**: Ensure your PHP server is running (e.g., XAMPP or another local PHP environment).

- **API Endpoints**:
   - `POST /register`: Register a new user.
   - `POST /login`: Log in and receive a JWT token.
   - `GET /protected`: Access a protected route (requires JWT).

### Example Requests

#### Registration

```bash
curl -X POST http://localhost/register -d '{"username": "john", "email": "john@example.com", "password": "your_password"}' -H 'Content-Type: application/json'
```

#### Login

```bash
curl -X POST http://localhost/login -d '{"email": "john@example.com", "password": "your_password"}' -H 'Content-Type: application/json'
```

#### Access Protected Route

```bash
curl -X GET http://localhost/protected -H 'Authorization: Bearer your_jwt_token'
```

## Testing

To run the tests:

```bash
composer test
```

## Code Quality

To check for coding standards and automatically fix issues:

```bash
composer lint
composer fix
```

## License

This project is licensed under the MIT License.
