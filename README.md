# Laravel Wishlist API

A RESTful API for managing wishlists in an e-commerce environment.

## Features

- **User Authentication**: Token-based authentication using Laravel Sanctum
- **Product List**: List available products
- **Wishlist Management**: Add, view, and remove products from user wishlists
- **Authorization**: Users can only modify their own wishlists
- **Validation**: Request validation and error handling
- **Testing**: Feature tests

## Requirements

- PHP >= 8.0
- Composer
- SQLite (default) or MySQL

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd wishlist-api
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   # For SQLite (default)
   touch database/database.sqlite
   ```

5. **Run migrations**
   ```bash
   php artisan migrate
   ```

6. **Seed sample data**
   ```bash
   php artisan db:seed
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

The API will be available at `http://localhost:8000`

## API Documentation

### Authentication

All protected endpoints require a Bearer token in the Authorization header:
```
Authorization: Bearer {your-token}
```

### Endpoints

#### 1. Register User

**POST** `/api/register`

Register a new user account.

**Request Body:**
```json
{
  "name": "Tosin Ezekiel",
  "email": "tosin@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

#### Login

**POST** `/api/login`

Authenticate user and receive access token.

**Request Body:**
```json
{
  "email": "tosin@example.com",
  "password": "password123"
}
```

```


#### Logout

**POST** `/api/logout`

Revoke the current access token.

**Headers:**
```
Authorization: Bearer {token}
```


**Unauthorized (401):**
```json
{
  "message": "Unauthenticated."
}
```

#### List Products

**GET** `/api/products` or `api/products?per_page=20&sort_by=price&sort_direction=desc&search=phone`

Get a list of all available products.
```

#### Get Wishlist

**GET** `/api/wishlist`

Get the authenticated user's wishlist.

**Headers:**
```
Authorization: Bearer {token}
```

#### Add to Wishlist

**POST** `/api/wishlist`

Add a product to the authenticated user's wishlist.

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "product_id": 1
}
```

#### Remove from Wishlist

**DELETE** `/api/wishlist/{product_id}`

Remove a product from the authenticated user's wishlist.

**Headers:**
```
Authorization: Bearer {token}
```

## Testing

```bash
php artisan test
```

## Error Handling

All errors follow a consistent JSON structure:

```json
{
  "message": "Error message",
  "errors": {
    "field": ["Error message for field"]
  }
}
```
