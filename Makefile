pint:
	docker exec -i -t westside_api ./vendor/bin/pint

pest:
	docker exec -i -t westside_api ./vendor/bin/pest

pest-coverage:
	docker exec -i -t westside_api ./vendor/bin/pest --coverage --coverage-html .coverage

migrate:
	docker exec -i -t westside_api php artisan migrate --seed

migrate-refresh:
	docker exec -i -t westside_api php artisan migrate:refresh --seed

seeder:
	docker exec -i -t westside_api php artisan db:seed

shell:
	docker exec -i -t westside_api sh

ssh:
	ssh -i ssh_key.pem westsidemotorcyc@westsidemotorcycle.com.br

deploy:
	./deploy.sh
