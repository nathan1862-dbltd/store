# store
building Sephora inspired ecom store

## Data integrity validation (products, variants, attributes)

Run this command to validate connections between `products`, `product_variants`, and `variant_attributes`:

```bash
php validate_product_variant_connections.php
```

You can override database credentials with environment variables:

```bash
DB_HOST=127.0.0.1 DB_USER=root DB_PASS=secret DB_NAME=store php validate_product_variant_connections.php
```
