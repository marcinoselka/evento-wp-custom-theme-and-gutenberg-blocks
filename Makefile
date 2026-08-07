.PHONY: up down stop restart logs ps shell db wp destroy

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
