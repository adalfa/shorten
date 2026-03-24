# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Running the App

Redis must be running locally before starting the app.

```bash
php -S localhost:8000
```

## Tests

```bash
php test/test.php          # Basic tests with SQLite
php test/testredis.php     # Load 1,000,000 test URLs into Redis
php test/testwip.php       # Test Wipmania geolocation API
```

## Architecture

### Entry Points

- `index.php` — Landing page (Twig-rendered form)
- `short.php` — Core handler: GET with hash → 301 redirect; POST with URL → create short URL
- `urllist.php` — JSON pagination endpoint for the DataTables URL list view

### Storage

Redis is the sole data store. `lib/sqllite.php` is a legacy migration artifact.

**Redis key schema:**
- `counter:id` — auto-incrementing integer; converted to base-66 hash for each new URL
- `urls:{hash}` — stores the original URL
- `stat:{hash}` — hash with stat keys: `count`, `YYYY`, `YYYYM`, `YYYYMDD`, `YYYYMDDh` (h = 0–23), and ISO 3166 country codes
- `blacklist` — set of blacklisted domains

The Redis client is a custom socket-based implementation in `lib/RedisServer.php` (not a Composer package).

### URL Creation Flow

1. Validate URL format (regex)
2. Check against Redis `blacklist`
3. Check against Google Safe Browsing API (`lib/googlesafe.php`)
4. `INCR counter:id` → encode with base-66 (`lib/shorten.php`) → store `urls:{hash}`

### Redirect Flow

1. Decode hash → look up `urls:{hash}` → HTTP 301
2. Increment stats at all granularities in `stat:{hash}`
3. Determine visitor country via Wipmania API (`lib/wipmania.php`); private IPs are filtered via CIDR check

### Key Libraries

- `lib/shorten.php` — base-66 encoding/decoding (alphabet: `0-9a-zA-Z-._~`)
- `lib/stats.php` — stat aggregation helpers (`getDailyDate`, `getMonthlyDate`) + ISO 3166 country array
- `lib/datatables.php` — formats stats for Google Charts (Daily/Monthly/Yearly)
- `lib/wipmania.php` — geolocation (`getCountry()`) and CIDR IP-range check (`isIPIn()`)

### Templates

Twig v1.x templates in `template/`: `base.html`, `index.html`, `error.html`, `google.html`, `list.html`.

### Configuration

- Google Safe Browsing API key: `config/safeapi.key` (also hardcoded in `lib/googlesafe.php` — keep both in sync)
