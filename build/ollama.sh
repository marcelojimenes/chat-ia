#!/bin/bash
ollama serve &
sleep 5
ollama pull llama3.2
exec "$@"
tail -f /dev/null
