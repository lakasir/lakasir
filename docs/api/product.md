# Product API

Base URL: `https://{tenant-domain}/api/master/product`
Auth: Bearer Token (`Authorization: Bearer {token}`)

---

## List Products

```
GET /api/master/product
```

### Request

```bash
curl -X GET "https://{tenant-domain}/api/master/product?filter[name]=kopi&include=category,images&per_page=15" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

### Query Parameters

| Param | Type | Description |
|---|---|---|
| `filter[name]` | string | Filter by product name |
| `filter[category_id]` | integer | Filter by category ID |
| `filter[sellingPrice]` | numeric | Filter by selling price |
| `filter[initialPrice]` | numeric | Filter by initial price |
| `filter[type]` | string | Filter by type (`product` / `service`) |
| `filter[category.name]` | string | Filter by category name |
| `filter[unit]` | string | Filter by unit |
| `filter[show]` | — | Filter by show status |
| `filter[stock]` | integer | Exact stock match (use operators below for comparisons) |
| `filter[stock:gt]` | integer | Stock greater than |
| `filter[stock:ge]` | integer | Stock greater than or equal |
| `filter[stock:lt]` | integer | Stock less than |
| `filter[stock:le]` | integer | Stock less than or equal |
| `filter[stock:ne]` | integer | Stock not equal |
| `filter[global]` | string | Search across `name`, `sku`, `barcode` |
| `include` | string | Comma-separated: `category`, `images` |
| `per_page` | integer | Items per page (default from `simplePaginate`) |

### Response (200)

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "sku": "PRD-001",
      "barcode": "8991234567890",
      "name": "Kopi Susu",
      "category_id": 1,
      "stock": 50,
      "initial_price": 10000,
      "selling_price": 15000,
      "type": "product",
      "is_non_stock": false,
      "hero_images": [],
      "category": {
        "id": 1,
        "name": "Minuman"
      },
      "images": []
    }
  ],
  "links": {
    "next": "https://{tenant-domain}/api/master/product?page=2",
    "prev": null
  }
}
```

---

## Create Product

```
POST /api/master/product
```

### Request

```bash
curl -X POST "https://{tenant-domain}/api/master/product" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "sku": "PRD-001",
    "barcode": "8991234567890",
    "name": "Kopi Susu",
    "category": 1,
    "stock": 50,
    "initial_price": 10000,
    "selling_price": 15000,
    "type": "product",
    "is_non_stock": false,
    "expired": "2026-12-31",
    "hero_images_uploaded_file_id": null
  }'
```

### Fields

| Field | Type | Required | Constraints |
|---|---|---|---|
| `sku` | string | no | unique |
| `barcode` | string | no | min:3, unique |
| `name` | string | yes | min:3 |
| `category` | integer | yes | valid category ID |
| `stock` | numeric | no* | required if `is_non_stock` is false |
| `initial_price` | numeric | yes | ≤ `selling_price` |
| `selling_price` | numeric | yes | ≥ `initial_price` |
| `type` | string | yes | `product` or `service` |
| `is_non_stock` | boolean | yes | |
| `expired` | date | conditional | required if ProductExpired feature is on |
| `hero_images_uploaded_file_id` | integer | no | valid uploaded file ID |

### Response (201)

```json
{
  "success": true,
  "message": "success creating items"
}
```

---

## Update Product

```
PUT /api/master/product/{product}
```

### Request

```bash
curl -X PUT "https://{tenant-domain}/api/master/product/1" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Kopi Susu Gula Aren",
    "selling_price": 18000
  }'
```

> Partial updates are supported — omitted fields retain their existing values.

### Fields

Same as create. All fields are optional on update.

### Response (200)

```json
{
  "success": true,
  "message": "success updating items"
}
```

---

## Delete Product

```
DELETE /api/master/product/{product}
```

```bash
curl -X DELETE "https://{tenant-domain}/api/master/product/1" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

### Response (200)

```json
{
  "success": true,
  "message": "success deleting items"
}
```
