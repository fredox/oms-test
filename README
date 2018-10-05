## Little oms test

Steps to get running:

execute composer in your local machine:
```
 composer install
```

execute docker-compose:
```
 docker-compose up --build
```

If everything went ok at **localhost:4242** is the root endpoint of the API. If you make a GET
will see something like this:
```json
{
    "author": "Alfredo Galiana Mora",
    "description": "Little Api in Json to manage orders"
}
```
The endpoint for add orders to the system is at **localhost:4242/order** and must be called with **POST** method, passing a json raw body like this:
```json
{
  "order": {
    "id": 2,
    "store_id": 4,
    "lines": [
      {
    	"line_number": 1,
        "sku": "blue_sock"
      },
      {
        "line_number": 2,
        "sku": "red_sock"
      }
    ]
  }
}
```
## Execute tests
From the fpm docker machine or in your local machine:
```
./vendor/phpunit/phpunit/phpunit tests
```
