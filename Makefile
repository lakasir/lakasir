app=lakasir
database=database

build:
	docker build . --progress plain -t ${app}:dev -f infra/docker/Dockerfile

run: build network
	docker compose -f infra/docker/compose.yml up -d ${app}

run-db: network
	docker compose -f infra/docker/compose.yml up -d ${database}

network:
	if [ ! -z "$(docker network ls | grep services)" ]; then \
		docker network create services; \
	fi

install:
	# makesure you have node v20
	npm install
	npm run build

	# setup app
	docker exec -it ${app} php artisan key:generate
	docker exec -it ${app} php artisan migrate --path=database/migrations/tenant --seed
	docker exec -it ${app} php artisan filament:assets