## Installation Instructions

1. After downloading repository, we need to execute the following commands.
    composer install
    npm install
2. Go to inside backend directory in the repository and execute the following commands.
    npm install
3. Create a booksdb database and confiture that database credentials in .env file. After that run the database migration by executing the following command in the packt directory.
    php artisan migrate --seed
4. To run the frontend application, execute the follwoing command in packt directory.
    php artisan serve
    npm run dev
5. To run the backend application, execute the following command in backend directory.
    npm run dev
6. To access the backend application, you can use the following credentials.
    Email: virat.gandhi@yopmail.com
    Pasword: 12345678

Frontend application can be visited by the following URL. Check the port number at terminal.

http://localhost:8000

Backend application can be visited by the following URL. Check the port number at terminal.

http://localhost:3000