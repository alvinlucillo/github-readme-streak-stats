#!/usr/bin/env bash

set -euo pipefail

if [[ -z "${TOKEN:-}" ]]; then
    echo "Missing TOKEN. Add a GitHub personal access token to the Netlify environment variables." >&2
    exit 1
fi

if [[ -z "${STREAK_USER:-}" ]]; then
    echo "Missing STREAK_USER. Add the GitHub username whose card Netlify should publish." >&2
    exit 1
fi

encoded_user="$(php -r 'echo rawurlencode((string) getenv("STREAK_USER"));')"
options="${STREAK_OPTIONS:-}"
if [[ -n "$options" ]]; then
    options="${options}&user=${encoded_user}"
else
    options="user=${encoded_user}"
fi

rm -rf dist
mkdir -p dist

php bin/generate-card.php --path=dist/streak.svg --options="$options"
cp netlify/site/index.html dist/index.html
