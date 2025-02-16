# Recruitment Task for TravelPlanet
- The docker setup is based on Kévin Dunglas's [Symfony docker](https://github.com/dunglas/symfony-docker).
- The whole REST API is based on API Platform.
- Products and ProductTypes are fully managed by the API Platform OOTB and are stored in database - you need to create some to be able to generate a PaymentSchedule.
- The CQRS pattern is implemented and integrated with symfony/messenger to handle commands and queries synchronously or asynchronously on separate buses.
- Money should be received, stored and returned as an integer to avoid floating-point precision issues. 
- Integration tests setup is using separate test database and has rollback mechanism to keep the database clean. 

## Requirements
- Docker Compose v2.10+

## Installation
1. Run `docker compose build --no-cache` 
2. Run `docker compose up --pull always -d --wait`
3. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)

## URLS
- [https://localhost](https://localhost) - Symfony app
- [https://localhost/api](https://localhost/api) - Swagger/OpenAPI UI

## Running tests
- `make unit-tests` - Run unit tests
- `make setup-tests && make integration-tests` - Run integration tests
