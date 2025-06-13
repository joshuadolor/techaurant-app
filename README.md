# Techaurant - Restaurant Management Platform

A modern web application that enables restaurant owners to manage their menus and generate QR codes for their white-labeled websites. This platform streamlines the process of menu management and customer engagement through digital solutions.

## Features

-   **Menu Management**: Create, read, update, and delete menu items and categories
-   **QR Code Generation**: Generate unique QR codes for each restaurant's digital menu
-   **White-Labeled Websites**: Custom-branded digital presence for each restaurant
-   **Real-time Updates**: Instant menu updates across all platforms
-   **Responsive Design**: Mobile-friendly interface for both restaurant owners and customers

## Tech Stack

-   Laravel (PHP Framework)
-   Vue.js (Frontend Framework)
-   MySQL (Database)
-   Docker (Containerization)
-   Node.js & NPM (Package Management)

## Prerequisites

-   Docker and Docker Compose
-   Git

## Installation

1. Clone the repository:

```bash
git clone [repository-url]
cd techaurant-app
```

2. Set up environment:

```bash
cp .env.example .env
```

3. Build and start the containers:

```bash
docker compose down
docker compose build --no-cache
docker compose up -d
```

4. Install dependencies and set up the application:

```bash
# Install PHP dependencies
docker compose exec app composer install

# Generate application key
docker compose exec app php artisan key:generate

# Run database migrations
docker compose exec app php artisan migrate

# Seed the database with initial data
docker compose exec app php artisan db:seed

# Install and build frontend assets
docker compose exec app npm install
docker compose exec app npm run dev
```

## Usage

## Development

-   Frontend development server: `npm run dev`
-   Backend development server: `php artisan serve`

## Contributing

Please read our contributing guidelines before submitting pull requests.

## License

[License Type] - See LICENSE file for details

### Todo

[ ] -
