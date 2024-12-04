# News Aggregator API

## Overview

The News Aggregator API is a Laravel-based application that collects and aggregates articles from multiple news providers, including:
- **The Guardian**
- **New York Times**
- **NewsAPI**

This project allows you to fetch, store, and filter articles based on user-defined criteria, such as category, date, source, and author. It supports asynchronous processing and paginated data fetching.

---

## Features

- Fetch articles from multiple providers.
- Filter and search articles by category, date, author, and content.
- Paginate results for efficient browsing.
- Asynchronous processing using events and jobs.
- Predefined and customizable category-based article retrieval.

---

## Setup

### Prerequisites
- PHP >= 8.1
- Composer
- Laravel 10
- MySQL or any supported database
- Redis (for queues)

### Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/MahmoudMontser/newsAggregator.git
   cd newsAggregator
   ```

2. **Install dependencies**:
   ```bash
   composer install
   ```

3. **Set up environment variables**:
   Copy the `.env.example` file and configure it:
   ```bash
   cp .env.example .env
   ```

   Update the following keys:
   - `DB_*` for database connection.
   - `REDIS_*` for queue management.
   - API keys for providers:
     - `SERVICES_THE_GUARDIAN_API_KEY`
     - `SERVICES_NEW_YORK_TIMES_API_KEY`
     - `SERVICES_NEWSAPI_API_KEY`

4. **Run migrations**:
   ```bash
   php artisan migrate
   ```

5. **Set up queues** (if using asynchronous processing):
   ```bash
   php artisan queue:work
   ```

6. **Start the application**:
   ```bash
   php artisan serve
   ```

---

## API Documentation

### Endpoints

#### 1. Retrieve Articles
- **Endpoint**: `GET /api/articles`
- **Description**: Fetch articles with optional filters.
- **Query Parameters**:
  - `q` (string): Search by title or content.
  - `from_date` (date): Filter articles published after this date.
  - `to_date` (date): Filter articles published before this date.
  - `category` (string): Filter by category.
  - `source` (string): Filter by source (e.g., `Guardian`, `New York Times`).
  - `author` (string): Filter by author.
  - `page` (integer): Page number for pagination.
- **Response**:
  ```json
  {
    "data": [
      {
        "id": 1,
        "title": "Sample Article",
        "content": "Content of the article",
        "author": "Author Name",
        "source": "Guardian",
        "category": "Technology",
        "published_at": "2024-12-04",
        "url": "https://example.com/article"
      }
    ],
    "links": { "first": "...", "last": "...", "prev": null, "next": "..." },
    "meta": { "current_page": 1, "last_page": 10, "per_page": 10, "total": 100 }
  }
  ```

#### 2. Fetch Articles by Category
- **Endpoint**: `POST /api/fetch`
- **Description**: Fetch articles for a specific category asynchronously.
- **Request Body**:
  ```json
  {
    "category": "Technology"
  }
  ```
- **Response**:
  ```json
  {
    "message": "Fetch request dispatched for category: Technology."
  }
  ```

---

## Command Line Usage

### Fetch Articles Command

- **Command**:
  ```bash
  php artisan articles:fetch {categories?*}
  ```
- **Description**: Fetch articles for predefined or specified categories.
- **Arguments**:
  - `categories`: Optional. List of categories to fetch (e.g., `Technology`, `Health`).
- **Example**:
  ```bash
  php artisan articles:fetch Technology Health
  ```

---

## Providers

### The Guardian
- **Base URL**: `https://content.guardianapis.com`
- **Fetches articles using the `/search` endpoint**.

### New York Times
- **Base URL**: `https://api.nytimes.com/svc/topstories/v2`
- **Fetches articles by section (e.g., `technology.json`)**.

### NewsAPI
- **Base URL**: `https://newsapi.org/v2`
- **Fetches articles using the `/everything` endpoint**.

---

## Asynchronous Fetching

To process categories asynchronously:
1. Ensure your queue worker is running:
   ```bash
   php artisan queue:work
   ```
2. Dispatch fetch events via the command:
   ```bash
   php artisan articles:fetch
   ```

---

## Contributing

1. **Fork the repository**.
2. **Create a feature branch**: `git checkout -b feature-name`.
3. **Commit changes**: `git commit -m "Add feature"`.
4. **Push to the branch**: `git push origin feature-name`.
5. **Open a pull request**.

---

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

---

## Future Enhancements

- Add more news providers.
- Improve filtering with machine learning for personalized results.
- Provide RSS feed support.
```
