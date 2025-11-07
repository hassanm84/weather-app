# Weather Information Application - Bright Software Group Tech Test


## Setup

### 1. Clone the repository

```shell
git clone https://github.com/hassanm84/weather-app.git
cd weather-app
```

### 2. Build docker image and run the app container

A docker-compose.yml file is included. Run the following command.

```shell
docker compose up -d --build
```

### 3. Install PHP dependencies

```shell
docker exec -it weather-app composer install
```
### 4. Set environment variables

Run the following command.

```bash
docker exec -it weather-app cp .env.example .env
```
You can now edit the .env file to add in your own configuration values.

### 5. Run the app
You can now access the app in your browser on `http://localhost:8000`.




Unit tests were written using [PHPUnit](https://phpunit.de). These tests can be run from within the container:
```shell
docker exec weather-app ./vendor/bin/phpunit --filter WeatherInfoControllerTest --testdox
```
