<p align="center">
  <img src="./readme-banner.svg" alt="Security-First PHP Authentication banner" width="100%" />
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
  <img src="https://img.shields.io/badge/PDO-2563EB?style=for-the-badge&logo=mysql&logoColor=white" alt="PDO" />
  <img src="https://img.shields.io/badge/Argon2id-0891B2?style=for-the-badge" alt="Argon2id" />
  <img src="https://img.shields.io/badge/Security-First-0EA5E9?style=for-the-badge" alt="Security First" />
</p>

## Security-First PHP Authentication

This is a custom authentication system built with pure PHP and PDO.  
The goal of this project is simple: keep the code clean, fast, and secure.

It is made for a cybersecurity portfolio, so the focus is on real security controls instead of frameworks.

### Quick overview

- Pure PHP (no framework)
- PDO with prepared statements
- Argon2id password hashing
- Hardened sessions
- SQL-based rate limiting for login attempts
- CSRF protection on forms

---

## Security features

| Feature | Why it matters |
| :--- | :--- |
| Argon2id hashing | Protects passwords with a modern, strong hashing method |
| Prepared SQL statements | Helps prevent SQL injection |
| Session ID regeneration on login | Reduces risk of session fixation |
| HttpOnly + SameSite cookies | Makes session cookies harder to steal or abuse |
| Rate limiting by IP | Slows down brute-force attacks |
| CSRF token checks | Blocks fake form submissions from other sites |
| Escaped output (`htmlspecialchars`) | Reduces XSS risk in user-facing messages |

---

## Tech stack

- **Backend:** PHP 8+
- **Database access:** PDO (MySQL)
- **Frontend:** Hand-written HTML/CSS
- **Structure:** `public`, `src`, `config`

---

## Project structure

```text
.
├── docs/
│   └── readme-banner.svg
├── config/
│   └── config.php
├── public/
│   ├── assets/
│   │   └── styles.css
│   ├── index.php
│   ├── login.php
│   ├── register.php
│   ├── dashboard.php
│   └── logout.php
├── src/
│   ├── bootstrap.php
│   ├── Database.php
│   ├── Repositories/
│   │   └── UserRepository.php
│   ├── Security/
│   │   ├── Csrf.php
│   │   ├── RateLimiter.php
│   │   └── SessionManager.php
│   ├── Services/
│   │   └── AuthService.php
│   └── Support/
│       └── Env.php
├── database.sql
├── .env.example
└── README.md
```

---

## Setup

1. Run `database.sql` in MySQL.
2. Copy `.env.example` to `.env`.
3. Fill in your database values in `.env`.
4. Set your web server document root to `public/`.

---

## Recommended production hardening

- Use HTTPS only
- Set `COOKIE_SECURE=true`
- Add security headers (CSP, HSTS, `X-Frame-Options`, `X-Content-Type-Options`)
- Add monitoring for repeated failed logins
- Store secrets outside the repository

---

## Performance

The frontend is intentionally lightweight:

- No UI frameworks
- No large JS bundles
- Fast load time and Lighthouse-friendly structure

---

## Note

This project is a strong secure-auth foundation.  
Before production use, do full testing, threat modeling, and infrastructure hardening.
