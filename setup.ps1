# Create directories
$folders = @(
  "app",
  "config",
  "database/migrations",
  "resources/views/layouts",
  "routes",
  "tests/Feature",
  "app/Http/Controllers/Api"
)
$folders | ForEach-Object { New-Item -ItemType Directory -Path $_ -Force | Out-Null }

# Create empty files
$files = @(
  "docker-compose.yml",
  "Dockerfile",
  ".env.example",
  "database/migrations/0000_create_users_table.php",
  "app/Http/Controllers/Api/PostGenerationController.php"
)
$files | ForEach-Object { New-Item -ItemType File -Path $_ -Force | Out-Null }

# Dockerfile content
@"
FROM php:8.2-fpm
RUN apt-get update && apt-get install -y `
    git curl libpng-dev libonig-dev libxml2-dev zip unzip `
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
WORKDIR /var/www/html
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .
RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN chown -R www-data:www-data storage bootstrap/cache
CMD php artisan serve --host=0.0.0.0 --port=8000
"@ | Set-Content -Path "Dockerfile"

# docker-compose.yml content
@"
version: "3"
services:
  app:
    build: .
    ports: ["8000:8000"]
    environment:
      DB_HOST: mysql
      DB_DATABASE: saas
      DB_USERNAME: root
      DB_PASSWORD: secret
      OPENAI_API_KEY: \${OPENAI_API_KEY}
    depends_on:
      - mysql
  mysql:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: secret
      MYSQL_DATABASE: saas
"@ | Set-Content -Path "docker-compose.yml"

# Migration file
@"
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create("users", function (Blueprint \$table) {
            \$table->id();
            \$table->string("name");
            \$table->string("email")->unique();
            \$table->string("password");
            \$table->string("brand_name");
            \$table->text("brand_description");
            \$table->string("website")->nullable();
            \$table->timestamps();
        });
    }
}
"@ | Set-Content -Path "database/migrations/0000_create_users_table.php"

# .env.example
@"
OPENAI_API_KEY=
DB_HOST=mysql
DB_DATABASE=saas
DB_USERNAME=root
DB_PASSWORD=secret
"@ | Set-Content -Path ".env.example"

# PostGenerationController
@"
<?php

namespace App\Http\Controllers\Api;

use App\Services\ChatGptService;
use Illuminate\Http\Request;

class PostGenerationController
{
    public function generate(Request \$request, ChatGptService \$chatGpt)
    {
        \$request->validate(["topic" => "required|string|max:255"]);
        \$options = \$chatGpt->generateContent(\$request->topic);
        \$request->user()->generationRequests()->create();
        return response()->json(["options" => \$options]);
    }
}
"@ | Set-Content -Path "app/Http/Controllers/Api/PostGenerationController.php"

# Final message
Write-Host "`n✅ Project structure and files created!"
Write-Host "`nTo get started, run the following commands:"
Write-Host "docker-compose up -d"
Write-Host "docker-compose exec app php artisan migrate"
