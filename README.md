# TVS Backend

## Running the app

### Requirements
- Docker (tested on v20.10)
- Docker Compose (tested on v2.10)

### Initial boostrap
- `cp .env.example .env`
- `docker run -w "/app" -v $PWD:/app composer install`

### Running the app stack
- `./sail up -d`

### App init
- `./sail artisan key:generate`
- `./sail artisan migrate`

### Run every time migrations change
- `./sail db:seed`

### Pint
- `./sail bash -c 'vendor/bin/pint'`


### FAQ
Need to start over?
- `./sail artisan mig:fre --seed`

Can be just `./sail artisan mig:fre` if testing data is not needed

### Usage
The application is available on http://localhost/ (or http://0.0.0.0/)
