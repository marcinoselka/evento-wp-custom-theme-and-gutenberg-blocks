.PHONY: up down stop restart logs ps shell db wp install destroy

up:
	docker compose up -d

down:
	docker compose down

stop:
	docker compose stop

restart:
	docker compose restart

logs:
	docker compose logs -f

ps:
	docker compose ps

shell:
	docker compose exec wordpress bash

db:
	docker compose exec db bash

wp:
	docker compose run --rm cli wp $(ARGS)

destroy:
	docker compose down -v

install:
	docker compose run --rm cli sh -c 'wp core is-installed || wp core install \
		--url="$${WP_URL}" \
		--title="$${WP_TITLE}" \
		--admin_user="$${WP_ADMIN_USER}" \
		--admin_password="$${WP_ADMIN_PASSWORD}" \
		--admin_email="$${WP_ADMIN_EMAIL}" \
		--skip-email'
