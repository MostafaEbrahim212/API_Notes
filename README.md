# API_TESTING

## Project Name

A simple project for managing user authentication (login, register, logout) and CRUD operations for notes.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Endpoints](#endpoints)
- [Contributing](#contributing)
- [License](#license)

## Features

- User authentication (login, register, logout)
- CRUD operations for managing notes
- User profile management

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/your-repo.git
   ```

2. Configure environment variables:
   ```bash
   cp .env.example .env
   # Edit .env file and add necessary configurations (database, JWT secret, etc.)
   ```

3. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```

4. Start the server:
   ```bash
   php artisan serve
   ```

## Usage

1. Register a new user.
2. Login with your credentials.
3. Create, read, update, or delete notes.
4. View your profile.
5. Logout when done.

## Endpoints

- **POST http://127.0.0.1:8000/api/register**: Register a new user.
- **POST http://127.0.0.1:8000/api/login**: Login with credentials and obtain an access token.
- **POST http://127.0.0.1:8000/api/logout**: Logout and invalidate the access token.
- **GET http://127.0.0.1:8000/api/user**: Get the user's profile information.
- **GET http://127.0.0.1:8000/api/notes**: Get all notes for the authenticated user.
- **GET http://127.0.0.1:8000/api/notes/{id}**: Get a specific note by ID.
- **POST http://127.0.0.1:8000/api/notes**: Create a new note.
- **PATCH http://127.0.0.1:8000/api/notes/{id}**: Update an existing note.
- **DELETE http://127.0.0.1:8000/api/notes/{id}**: Delete a note.

## Contributing

Contributions are welcome! Feel free to open issues or submit pull requests.

## License

This project is licensed under the MIT License.
