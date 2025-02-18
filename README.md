# Recruitment Task for TravelPlanet
## Requirements
- Docker Compose v2.10+
- OpenSSL

## Installation
1. Run `docker compose build --no-cache` 
2. Run `docker compose up --pull always -d --wait`
3. Run `make setup-database`
3. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
4. Generate keys for JWT authentication by running `make generate-keys`
5. Run `make load-fixtures` to load User fixture

## URLS
- [https://localhost](https://localhost) - Symfony app
- [https://localhost/api](https://localhost/api) - Swagger/OpenAPI UI

## Running tests
- `make unit-tests` - Run unit tests
- `make setup-tests && make integration-tests` - Run integration tests
- `make setup-tests && make application-tests` - Run application tests

## Usage
- `POST /login` - Generate JWT token. Use default credentials: 
    ```
    {
        "email": "admin@example.com",
        "password": "password"
    }
    ```
- Use the generated token in the `Authorization` header in the following format:
    ```
    Authorization: Bearer {token}
    ```
- `POST /api/v1/schedule/generate` - Generates a payment schedule. See [Swagger/OpenAPI UI](https://localhost/api) for more details.
    Generated schedule URI is returned in Location header of the response.
- `GET /api/v1/schedule/{scheduleId}` - Retrieves a payment schedule by ID. URI to gene scheu of previous request. See [Swagger/OpenAPI UI](https://localhost/api) for more details.

## Business logic

### Available product types:
- `Car` - A car product type.
- `Furniture` - A furniture product type.
- `Electronics` - An electronics product type.

### Available currencies:
- `PLN` - Polish Zloty
- `EUR` - Euro
- `USD` - US Dollar

### PaymentScheduleStrategies:
- `StandardPaymentScheduleStrategy` - Generates a payment schedule with one payment.
- `JunePaymentScheduleStrategy` - Generates a payment schedule with two payments. 
The first instalment is 30% of the total amount and the second payment is the remaining 70% of the total amount.
- `DecemberYearlyPaymentScheduleStrategy` - Generates a payment schedule with twelve payments. 
- `CarProductTypeTwoEqualPaymentsScheduleStrategy` - Generates a payment schedule with two payments for a car product type.

If the amount cannot be divided into equal parts, the last instalment will be the remaining amount.

## Metrics and logs
- Every request time is logged in `var/log/performance.log`
- Every request is logged in `var/log/request.log`
- Application logs are available in `var/log/app.log`
- General logs are available in `var/log/dev.log`
- Metrics for prometheus are available at [https://localhost/metrics/prometheus](https://localhost/metrics/prometheus)
- Health check is available at [https://localhost/monitor/health](https://localhost/monitor/health)
