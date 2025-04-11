install: init-script up logs

init-script:
	@mkdir -p build
	@echo '#!/bin/bash\nollama serve &\nsleep 5\nollama pull llama3.2\nexec "$$@"\ntail -f /dev/null' > build/ollama.sh
	@chmod +x build/ollama.sh
	@echo "ollama.sh successfully created"

up:
	docker compose up -d --build

down:
	docker compose down

restart: down up logs

logs:
	docker compose logs -f

ollama-logs:
	docker compose logs -f ollama

app-logs:
	docker compose logs -f app