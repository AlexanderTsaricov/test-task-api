<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeDockerCompose extends Command
{
    protected $signature = 'make:docker';
    protected $description = 'Создать docker-compose.yml и Dockerfile для проекта Laravel с данными из .env';

    public function handle()
    {
        // Берём данные из .env
        $dbRootPassword = env('DB_ROOT_PASSWORD', 'rootpass');
        $dbDatabase     = env('DB_DATABASE', 'db');
        $dbUser         = env('DB_USERNAME', 'user');
        $dbPassword     = env('DB_PASSWORD', 'password');
        $dbPort         = env('DB_PORT', '33061');

        $dockerComposeContent = <<<YAML
version: '3.9'

services:
  app:
    build: .
    image: php:8.3-fpm
    container_name: app
    restart: always
    working_dir: /var/www
    volumes:
      - ./:/var/www
    depends_on:
      - mysql
    ports:
      - "19000:9000"

  web:
    image: nginx:latest
    container_name: nginx
    volumes:
      - ./:/var/www
      - ./docker/nginx.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app
    ports:
      - "10080:80"

  mysql:
    image: mysql:8.3
    container_name: data-base
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: {$dbRootPassword}
      MYSQL_DATABASE: {$dbDatabase}
      MYSQL_USER: {$dbUser}
      MYSQL_PASSWORD: {$dbPassword}
      MYSQL_TCP_PORT: {$dbPort}
    ports:
      - "33060:{$dbPort}"
    volumes:
      - db_data:/var/lib/mysql
    command: --port={$dbPort}  

volumes:
  db_data:
YAML;

        $dockerfileContent = <<<DOCKER
FROM php:8.3-fpm

# Установка расширений для работы с MySQL и другими зависимостями Laravel
RUN apt-get update && apt-get install -y \\
    default-mysql-client \\
    libzip-dev \\
    unzip \\
    && docker-php-ext-install pdo pdo_mysql
DOCKER;

        File::put(base_path('docker-compose.yml'), $dockerComposeContent);
        File::put(base_path('Dockerfile'), $dockerfileContent);

        $this->info('Файлы docker-compose.yml и Dockerfile созданы успешно с настройками из .env.');
    }
}
