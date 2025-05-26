# Social Media SaaS Content Generator

A multi-tenant SaaS platform for brands to generate and manage social media content using ChatGPT.

## Features

- User registration & authentication (brand details included)
- Personalized dashboard with activity stats
- Generate 3 unique social media post suggestions per topic (powered by ChatGPT)
- Save, edit, and delete selected posts
- Secure API (Laravel Sanctum)
- Dockerized for easy local development

## Getting Started

### Prerequisites

- Docker & Docker Compose
- OpenAI API Key

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/social-media-saas.git
   cd social-media-saas
   ```

2. Copy `.env.example` to `.env` and set your environment variables, especially `OPENAI_API_KEY`.

3. Build and start the containers:
   ```bash
   docker-compose up --build
   ```

4. Run migrations:
   ```bash
   docker-compose exec app php artisan migrate
   ```

### API Usage Examples

#### Register

```bash
curl -X POST http://localhost/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"John Doe","email":"john@example.com","password":"secret","brand_name":"BrandX","brand_description":"We sell X.","website":"https://brandx.com"}'
```

#### Generate Posts

```bash
curl -X POST http://localhost/api/posts/generate \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{"topic":"Summer Sale"}'
```

#### Save a Post

```bash
curl -X POST http://localhost/api/posts \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{"title":"Title here","content":"Content here"}'
```

#### List Posts

```bash
curl -X GET http://localhost/api/posts \
  -H "Authorization: Bearer {TOKEN}"
```

#### View, Update, Delete

```bash
# View
curl -X GET http://localhost/api/posts/{id} -H "Authorization: Bearer {TOKEN}"

# Update
curl -X PUT http://localhost/api/posts/{id} \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{"title":"New Title","content":"Updated content"}'

# Delete
curl -X DELETE http://localhost/api/posts/{id} -H "Authorization: Bearer {TOKEN}"
```

### Running Tests

```bash
docker-compose exec app php artisan test
```

## License

MIT