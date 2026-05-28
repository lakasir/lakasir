#!/bin/bash
# ───────────────────────────────────────────────────────────────
# Lakasir Server Setup Script
# ───────────────────────────────────────────────────────────────
# Run this ONCE on your server to prepare it for deployments.
# This sets up Docker, Cloudflared tunnel, and the app directory.
#
# Usage: bash setup-server.sh
# ───────────────────────────────────────────────────────────────

set -euo pipefail

APP_DIR="${APP_DIR:-/opt/lakasir}"

echo ""
echo "╔══════════════════════════════════════════════════╗"
echo "║    🖥️  Lakasir Server Setup                       ║"
echo "╚══════════════════════════════════════════════════╝"
echo ""

# ───────────────────────────────────────────────────────────────
# Step 1: Install Docker
# ───────────────────────────────────────────────────────────────
if command -v docker &>/dev/null; then
    echo "✅ Docker is already installed: $(docker --version)"
else
    echo "📦 Installing Docker..."
    curl -fsSL https://get.docker.com | sh
    usermod -aG docker "$USER"
    echo "✅ Docker installed."
fi

# ───────────────────────────────────────────────────────────────
# Step 2: Install Docker Compose (V2 plugin)
# ───────────────────────────────────────────────────────────────
if docker compose version &>/dev/null; then
    echo "✅ Docker Compose V2 is available: $(docker compose version)"
else
    echo "📦 Installing Docker Compose plugin..."
    mkdir -p /usr/local/lib/docker/cli-plugins
    COMPOSE_VERSION="2.24.5"
    curl -fsSL "https://github.com/docker/compose/releases/download/v${COMPOSE_VERSION}/docker-compose-linux-$(uname -m)" \
        -o /usr/local/lib/docker/cli-plugins/docker-compose
    chmod +x /usr/local/lib/docker/cli-plugins/docker-compose
    echo "✅ Docker Compose installed."
fi

# ───────────────────────────────────────────────────────────────
# Step 3: Create app directory
# ───────────────────────────────────────────────────────────────
echo "📁 Creating application directory: ${APP_DIR}"
mkdir -p "${APP_DIR}"
cd "${APP_DIR}"

# ───────────────────────────────────────────────────────────────
# Step 4: Login to GHCR (requires GitHub token)
# ───────────────────────────────────────────────────────────────
echo ""
echo "🔐 Docker GHCR Login"
echo "   You'll need a GitHub Personal Access Token with 'read:packages' scope."
echo "   Create one at: https://github.com/settings/tokens"
echo ""
read -rp "GitHub username: " GH_USER
read -rsp "GitHub token (PAT with read:packages): " GH_TOKEN
echo ""
echo "${GH_TOKEN}" | docker login ghcr.io -u "${GH_USER}" --password-stdin
unset GH_TOKEN

# ───────────────────────────────────────────────────────────────
# Step 5: Create .env from template
# ───────────────────────────────────────────────────────────────
if [ ! -f .env ]; then
    echo "📝 Creating .env from .env.production.example..."
    curl -fsSL "https://raw.githubusercontent.com/sheenazien8/lakasir/main/.env.production.example" -o .env
    echo ""
    echo "⚠️  IMPORTANT: Edit ${APP_DIR}/.env and set your values:"
    echo "   - APP_KEY (run: docker compose run --rm app php artisan key:generate)"
    echo "   - APP_URL"
    echo "   - DB_PASSWORD"
    echo "   - Any other production settings"
    echo ""
fi

# ───────────────────────────────────────────────────────────────
# Step 6: Download docker-compose.prod.yml
# ───────────────────────────────────────────────────────────────
if [ ! -f docker-compose.prod.yml ]; then
    echo "📥 Downloading docker-compose.prod.yml..."
    curl -fsSL "https://raw.githubusercontent.com/sheenazien8/lakasir/main/docker-compose.prod.yml" -o docker-compose.prod.yml
fi

# ───────────────────────────────────────────────────────────────
# Step 7: Install Cloudflared tunnel (if not present)
# ───────────────────────────────────────────────────────────────
if command -v cloudflared &>/dev/null; then
    echo "✅ Cloudflared is already installed: $(cloudflared --version 2>&1 || echo 'installed')"
else
    echo "📦 Installing Cloudflared..."
    # Detect OS
    if [ -f /etc/debian_version ]; then
        curl -fsSL https://pkg.cloudflare.com/cloudflare-main.gpg | sudo tee /usr/share/keyrings/cloudflare-main.gpg >/dev/null
        echo "deb [signed-by=/usr/share/keyrings/cloudflare-main.gpg] https://pkg.cloudflare.com/cloudflared $(lsb_release -cs) main" \
            | sudo tee /etc/apt/sources.list.d/cloudflared.list
        apt-get update -qq
        apt-get install -y cloudflared
    elif [ -f /etc/redhat-release ]; then
        curl -fsSL https://pkg.cloudflare.com/cloudflared/rpm/cloudflared.repo -o /etc/yum.repos.d/cloudflared.repo
        yum install -y cloudflared
    else
        # Generic Linux
        curl -fsSL https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64 \
            -o /usr/local/bin/cloudflared
        chmod +x /usr/local/bin/cloudflared
    fi
    echo "✅ Cloudflared installed."
fi

echo ""
echo "╔══════════════════════════════════════════════════╗"
echo "║          ✅ Server Setup Complete!               ║"
echo "╠══════════════════════════════════════════════════╣"
echo "║  Next steps:                                     ║"
echo "║  1. Edit ${APP_DIR}/.env with production values  ║"
echo "║  2. Run: cloudflared tunnel login               ║"
echo "║  3. Create tunnel: cloudflared tunnel create lakasir ║"
echo "║  4. Configure tunnel to route to localhost:80    ║"
echo "║  5. Run: cloudflared tunnel run lakasir          ║"
echo "║  6. Deploy via GitHub Actions release workflow  ║"
echo "╚══════════════════════════════════════════════════╝"
echo ""