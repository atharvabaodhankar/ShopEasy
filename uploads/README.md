# Product Images Upload Directory

This directory stores uploaded product images.

## Features
- Automatic file renaming with unique identifiers
- File type validation (JPG, PNG, GIF, WebP)
- File size limit (5MB maximum)
- Secure file handling with .htaccess protection

## File Naming Convention
Uploaded files are automatically renamed using the pattern:
`product_{unique_id}_{timestamp}.{extension}`

Example: `product_64f8a1b2c3d4e_1693920000.jpg`

## Security
- PHP execution is disabled in this directory
- Only image files are allowed
- Direct directory browsing is prevented