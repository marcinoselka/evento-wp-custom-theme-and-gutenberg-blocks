# EVENTO

EVENTO is a modern WordPress Events Portal built from scratch as a portfolio project.

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

Stop containers

```bash
make down
```
