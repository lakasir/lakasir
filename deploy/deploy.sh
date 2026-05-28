#!/bin/bash
# ───────────────────────────────────────────────────────────────
# Lakasir Production Deployment Script
# ───────────────────────────────────────────────────────────────
# Usage: ./deploy.sh <version> <image> <app_dir>
# Example: ./deploy.sh 1.2.3 ghcr.io/sheenazien8/lakasir:1.2.3 /opt/lakasir
#
# This script is designed to run ON the server (called via SSH from CI/CD).
# It handles: pulling the new image, running migrations, and restarting services.
# ───────────────────────────────────────────────────────────────

set -euo pipefail

VERSION="${1:?Version argument is required. Usage: deploy.sh <version> <image> <app_dir>}"
IMAGE="${2:?Image argument is required.}"
APP_DIR="${3:-/opt/lakasir}"

echo ""
echo "╔══════════════════════════════════════════════════╗"
echo "║        🚀 Lakasir Deployment Starting            ║"
echo "╠══════════════════════════════════════════════════╣"
echo "║  Version:  ${VERSION}"
echo "║  Image:    ${IMAGE}"
echo "║  Directory: ${APP_DIR}"
echo "╚══════════════════════════════════════════════════╝"
echo ""

cd "${APP_DIR}"

# ───────────────────────────────────────────────────────────────
# Step 1: Ensure .env file exists
# ───────────────────────────────────────────────────────────────
if [ ! -f .env ]; then
    echo "⚠️  No .env file found. Creating from .env.example..."
    if [ -f .env.example ]; then
        cp .env.example .env
    else
        echo "❌ No .env.example found! Please create a .env file manually."
        exit 1
    fi
fi

# ───────────────────────────────────────────────────────────────
# Step 2: Set the image tag in .env for docker-compose
# ───────────────────────────────────────────────────────────────
# Extract the full image reference (tag included)
IMAGE_TAG=$(echo "${IMAGE}" | sed 's/.*://')
export IMAGE_TAG
echo "🏷️  Image tag: ${IMAGE_TAG}"

# If using docker-compose.prod.yml with a variable substitution for the repo,
# set GITHUB_REPO as well.
GITHUB_REPO=$(echo "${IMAGE}" | sed 's|ghcr.io/||' | sed 's/:.*//')
export GITHUB_REPO
echo "📦 GHCR repo: ${GITHUB_REPO}"

# ───────────────────────────────────────────────────────────────
# Step 3: Pull the latest Docker image
# ───────────────────────────────────────────────────────────────
echo "📥 Pulling Docker image: ${IMAGE}"
docker pull "${IMAGE}"

# ───────────────────────────────────────────────────────────────
# Step 4: Stop existing containers gracefully
# ───────────────────────────────────────────────────────────────
echo "🛑 Stopping existing containers..."
docker compose -f docker-compose.prod.yml down --remove-orphans 2>/dev/null || \
docker-compose -f docker-compose.prod.yml down --remove-orphans 2>/dev/null || true

# ───────────────────────────────────────────────────────────────
# Step 5: Start new containers
# ───────────────────────────────────────────────────────────────
echo "🚀 Starting containers with docker-compose.prod.yml..."

# Try docker compose (V2) first, fall back to docker-compose (V1)
if docker compose version &>/dev/null; then
    COMPOSE_CMD="docker compose"
else
    COMPOSE_CMD="docker-compose"
fi

${COMPOSE_CMD} -f docker-compose.prod.yml up -d

# ───────────────────────────────────────────────────────────────
# Step 6: Wait for app container to be healthy
# ───────────────────────────────────────────────────────────────
echo "⏳ Waiting for app container to start..."
APP_CONTAINER=$(${COMPOSE_CMD} -f docker-compose.prod.yml ps -q app 2>/dev/null | head -1)

if [ -n "${APP_CONTAINER}" ]; then
    echo "🩺 Checking app container health..."
    RETRY=0
    MAX_RETRIES=30
    until docker exec "${APP_CONTAINER}" php artisan --version &>/dev/null || [ ${RETRY} -eq ${MAX_RETRIES} ]; do
        RETRY=$((RETRY + 1))
        echo "   Attempt ${RETRY}/${MAX_RETRIES}..."
        sleep 2
    done

    if [ ${RETRY} -eq ${MAX_RETRIES} ]; then
        echo "❌ App container did not become healthy within timeout!"
        echo "📋 Container logs:"
        docker logs "${APP_CONTAINER}" --tail 50
        exit 1
    fi
    echo "✅ App container is healthy!"
else
    echo "⚠️  Could not find app container, skipping health check."
fi

# ───────────────────────────────────────────────────────────────
# Step 7: Run Laravel deployment commands
# ───────────────────────────────────────────────────────────────
echo "⚙️  Running Laravel deployment commands..."

docker exec "${APP_CONTAINER}" php artisan down --render="errors::503" || true

# Run migrations
echo "📦 Running database migrations..."
docker exec -e CACHE_DRIVER=array "${APP_CONTAINER}" php artisan migrate --force || {
    echo "❌ Migration failed! Rolling back to previous version..."
    ${COMPOSE_CMD} -f docker-compose.prod.yml down
    exit 1
}

# Run tenant migrations (if applicable)
echo "📦 Running tenant migrations..."
docker exec -e CACHE_DRIVER=array "${APP_CONTAINER}" php artisan migrate --force --path=database/migrations/tenant || true

# Cache optimizations
echo "⚡ Optimizing application cache..."
docker exec "${APP_CONTAINER}" php artisan config:cache
docker exec "${APP_CONTAINER}" php artisan route:cache
docker exec "${APP_CONTAINER}" php artisan view:cache
docker exec "${APP_CONTAINER}" php artisan event:cache
docker exec "${APP_CONTAINER}" php artisan filament:cache-components
docker exec "${APP_CONTAINER}" php artisan icons:cache

# Bring app back up
docker exec "${APP_CONTAINER}" php artisan up || true

# ───────────────────────────────────────────────────────────────
# Step 8: Verify deployment
# ───────────────────────────────────────────────────────────────
echo ""
echo "🔍 Verifying deployment..."
APP_VERSION=$(docker exec "${APP_CONTAINER}" php artisan --version 2>/dev/null || echo "unknown")
echo "   Laravel version: ${APP_VERSION}"
echo "   Deployed image:   ${IMAGE}"

# ───────────────────────────────────────────────────────────────
# Step 9: Clean up old images
# ───────────────────────────────────────────────────────────────
echo "🧹 Cleaning up old Docker images..."
docker image prune -f --filter "label=com.docker.compose.project=lakasir" 2>/dev/null || \
docker image prune -f 2>/dev/null || true

# ───────────────────────────────────────────────────────────────
# Done!
# ───────────────────────────────────────────────────────────────
echo ""
echo "╔══════════════════════════════════════════════════╗"
echo "║        ✅ Deployment Successful!                ║"
echo "╠══════════════════════════════════════════════════╣"
echo "║  Version:  ${VERSION}"
echo "║  Image:    ${IMAGE}"
echo "║  Time:     $(date '+%Y-%m-%d %H:%M:%S')"
echo "╚══════════════════════════════════════════════════╝"
echo ""