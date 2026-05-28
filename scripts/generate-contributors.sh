#!/bin/bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"
OUTPUT="$PROJECT_DIR/CONTRIBUTORS.md"

cd "$PROJECT_DIR"

BOTS="dependabot\[bot\]|github-actions\[bot\]|copilot-swe-agent\[bot\]|root"

REPO=$(git remote get-url origin | sed 's|.*github.com[:/]||; s|\.git$||')
CONTRIBUTORS=$(git log --format='%aN' | sort -u | grep -vE "$BOTS" | sort -f)

{
  echo "# Contributors"
  echo ""
  echo "[![Contributors](https://contrib.rocks/image?repo=${REPO}&nocache=1)](https://github.com/${REPO}/graphs/contributors)"
  echo ""
  echo "Thanks to everyone who has contributed to this project:"
  echo ""
  while IFS= read -r name; do
    echo "- $name"
  done <<< "$CONTRIBUTORS"
} > "$OUTPUT"

echo "CONTRIBUTORS.md updated with $(echo "$CONTRIBUTORS" | wc -l | tr -d ' ') contributors."
