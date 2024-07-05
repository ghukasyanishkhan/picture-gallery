# PHP Picture Gallery

A web-based picture gallery application where users can view galleries as guests, register and log in to manage their
photos. Registered users can add photos, delete photos, add photos to a wishlist, and view their photos.

## Features

- View galleries as a guest
- User registration and login
- Add, delete, and view personal photos
- Add photos to wishlist

## Installation

Follow these steps to set up the project:

1. **Clone the repository:**

   Open your terminal or command prompt and run the following commands:

git clone project
cd your project

2. **Install Dependencies:**

Ensure you have Composer installed. Then, navigate to the project directory and install dependencies:

composer install

3. **Set up the environment:**
   Create a .env file in the root directory of the project and add the following configuration:

DB_DSN=mysql:host=localhost;port=3306;dbname=
DB_USER=
DB_PASSWORD=

Replace your database name, your database user, and your database password with your actual database credentials.

4. **Run migrations:**

In the project root directory, execute the migration script to set up the database schema:

php migrations.php

5. **Start the server:**

Change to the public directory and start the PHP built-in server:

cd public
php -S localhost:8080

6. **Access the application:**

Open your browser and navigate to http://localhost:8080 to use the application.



# picture-gallery
