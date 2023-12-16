# Triangulation: obtain the coordinates of the desired point using three sets of reference coordinates.

https://en.wikipedia.org/wiki/Trilateration

## Requirements
- PHP@7.4
- Git

## Setup
```
$ cd frontend
$ npm i

```

## Run
```
Frontend
$ cd frontend
$ npm start

Server
$ cd server
$ php -S localhost:4000

```

### Frontend app port: 3000
### Frontend app port: 4000

### EXAMPLE WITH WORKING DATA (NEED TO UPDATE index.php)
```
curl --location 'http://localhost:4000/?distA=0.265710701754&distB=0.234592423446&distC=0.0548954278262'
{
    "status": "success",
    "result": "37.419745911492, -121.96122508852"
}
```
