
### Installation

1. Clone repo

2. Change to directory

````
cd dienmay
````   

3. Install dependencies

````
composer install
````

4. Copy .env file

```
cp .env.example .env
```

5. Sua DB trong .env cho phu` hop voi local

6. Generate application key:

````
php artisan key:generate
````

7. Migrate
````
php artisan migrate
````

8. Install Node modules
````
npm install
````

9. Build

````
npm run dev OR npm run watch
````