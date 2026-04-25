#!/usr/bin/env bash
# Thin shell wrapper that delegates to the Python sync script.
# Kept for backwards compatibility with documented `bash scripts/sync-obsidian-vault.sh`.
set -euo pipefail
exec python3 "$(dirname "$0")/sync-obsidian-vault.py" "$@"
