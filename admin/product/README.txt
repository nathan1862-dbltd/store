Admin Product Manager (Optimized) — Drop-in Module
==================================================

Aligned with your uploaded SQL dump:
- uwnktxcpef_newecommerce 9.sql
- order_items includes product_id + variant_id, so:
  - products.id maps to order_items.product_id
  - product_variants.id maps to order_items.variant_id

INSTALL (cPanel / File Manager)
-------------------------------
1) Upload folder: V3/admin/products/  -> into your existing /V3/admin/products/
2) Upload CSS:    V3/admin/assets/admin-product-manager.css -> /V3/admin/assets/
3) Ensure folder exists + writable: /V3/uploads/products/

SQL Setup
---------
Run in phpMyAdmin (DB: uwnktxcpef_newecommerce):
- V3/admin/products/sql_admin_product_manager.sql

Entry points
------------
- /V3/admin/products/products.php
- /V3/admin/products/brands.php
- /V3/admin/products/categories.php
- /V3/admin/products/variants.php

Assumptions
-----------
- Your /V3/init.php creates $mysqli and starts session helpers
- Your /V3/admin/auth.php protects admin pages

Notes
-----
- Delete guard checks order_items.product_id to prevent deleting products with history.
- Uses prepared statements and basic file-type validation.
