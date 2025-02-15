# Recruitment Task for TravelPlanet
The docker setup is based on Kévin Dunglas's [Symfony docker](https://github.com/dunglas/symfony-docker).
The whole REST API is based on API Platform.

## Requirements
- Docker Compose v2.10+

## Installation
1. Run `docker compose build --no-cache` 
2. Run `docker compose up --pull always -d --wait`
3. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)

## URLS
- [https://localhost](https://localhost) - Symfony app
- [https://localhost/api](https://localhost/api) - Swagger UI
- [https://localhost/api/docs](https://localhost/api/docs) - (alternatively) ReDoc UI
