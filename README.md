# EVENTO

EVENTO is a modern WordPress events portal built from scratch with a **custom Block Theme** and **custom Gutenberg blocks**.

## Tech Stack

- WordPress
- Custom Theme
- Custom Gutenberg Blocks
- PHP
- JavaScript
- Docker
- MariaDB
- WP-CLI
- Git

---

## Requirements

- Docker
- Docker Compose
- GNU Make

---

## Installation

```bash
cp .env.example .env
make up
make install
```

Application:

http://localhost:9050

phpMyAdmin:

http://localhost:9051

---

## Development

Show running containers

```bash
make ps
```

View logs

```bash
make logs
```

Open shell

```bash
make shell
```

Run WP-CLI

```bash
make wp ARGS="--info"
```

Open WP-CLI shell

```bash
make wpcli
```

Install theme dependencies

```bash
npm --prefix theme install
```

Build theme assets

```bash
npm --prefix theme run build
```

Stop containers

```bash
make down
```
