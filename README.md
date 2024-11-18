
## Installation and Setup

1. **Create a new database** for the application.
2. Install PHP dependencies:
   ```
   composer install
   ```
3. Run database migrations:
   ```
   php artisan migrate
   ```
4. Install Node.js dependencies:
   ```
   npm install
   ```
5. Compile assets:
   ```
   npm run dev
   ```
6. Start the queue worker:
   ```
   php artisan queue:work --queue=products
   ```
7. Do not forget to add the API KEY to the .ENV-file
   ```
   FLORINET_API_KEY=
   ```
