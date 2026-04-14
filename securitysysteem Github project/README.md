# Security-First PHP Authentication System

A custom-built, framework-free authentication system engineered in pure PHP + PDO with defensive controls designed for real-world abuse scenarios.

## Why this project exists

This repository demonstrates practical cybersecurity engineering, not template code.  
The architecture and implementation emphasize secure defaults, low attack surface, and clean maintainability.

## Core Security Features

- Argon2id password hashing using `password_hash()` with tuned memory/time cost parameters.
- Strict PDO usage with prepared statements for all database reads/writes to prevent SQL injection.
- Hardened session handling:
  - Cookie-only sessions
  - HttpOnly cookies
  - SameSite policy support
  - Optional Secure flag for HTTPS deployments
  - Session ID regeneration immediately after successful login
- SQL-backed brute-force protection:
  - Failed authentication attempts logged per source IP
  - Sliding window attempt counting
  - Temporary IP block after threshold is exceeded
  - Automatic cleanup of failed attempts on successful login
- CSRF token generation and validation for all credential submission forms.
- Output escaping with `htmlspecialchars()` to reduce reflected XSS risk in UI responses.

## Project Structure

```text
.
|-- config/
|   `-- config.php
|-- public/
|   |-- assets/
|   |   `-- styles.css
|   |-- index.php
|   |-- login.php
|   |-- register.php
|   |-- dashboard.php
|   `-- logout.php
|-- src/
|   |-- Database.php
|   |-- bootstrap.php
|   |-- Repositories/
|   |   `-- UserRepository.php
|   |-- Security/
|   |   |-- Csrf.php
|   |   |-- RateLimiter.php
|   |   `-- SessionManager.php
|   `-- Services/
|       `-- AuthService.php
|-- database.sql
|-- .env.example
`-- README.md
```

## Setup Instructions

1. Create a MySQL database and schema:
   - Run `database.sql`.
2. Copy environment values:
   - Create `.env` from `.env.example`.
3. Configure your web server document root to `public/`.
4. Run PHP with your preferred server setup (Apache/Nginx + PHP-FPM recommended).

## Recommended Production Hardening

- Force HTTPS and set `COOKIE_SECURE=true`.
- Add strict HTTP security headers (CSP, HSTS, X-Frame-Options, X-Content-Type-Options).
- Put the app behind a WAF or reverse proxy with request anomaly detection.
- Add centralized logging + alerting for repeated blocked login events.
- Store secrets in an external secrets manager instead of local files.

## Performance and Front-End Notes

- Front-end is intentionally hand-coded HTML/CSS with no UI frameworks or JS libraries.
- Pages are lightweight and render minimal assets, supporting excellent Lighthouse performance metrics.
- Accessibility and semantic markup choices are optimized for speed and clarity.

## Security Disclaimer

This project is intentionally scoped to authentication foundations and secure coding demonstrations.  
For production rollout, perform full threat modeling, penetration testing, dependency audits, and environment-specific hardening.
