# 🚀 Lakasir Deployment Guide

This guide covers deploying Lakasir to a production server using **Cloudflared tunnel** + **GitHub Actions CI/CD**.

## Architecture Overview

```
┌──────────────┐     push tag      ┌──────────────┐     build & push     ┌──────────────┐
│   Developer   │ ────────────────▶ │ GitHub Actions│ ──────────────────▶ │  GHCR (Docker) │
│   (local)     │   e.g. v1.2.3     │  (CI/CD)      │   Docker image      │  Registry      │
└──────────────┘                    └──────┬───────┘                     └──────┬───────┘
                                           │                                    │
                                           │ SSH (via Cloudflared               │ docker pull
                                           │  tunnel or direct)                 │
                                           ▼                                    │
                                    ┌──────────────────────────────────────────┐
                                    │            Production Server            │
                                    │  ┌─────────┐ ┌───────┐ ┌─────────┐    │
                                    │  │  App     │ │ MySQL │ │ Redis   │    │
                                    │  │ (Lakasir)│ │       │ │         │    │
                                    │  └────┬─────┘ └───────┘ └─────────┘    │
                                    │       │ :80                           │
                                    │  ┌────┴──────┐                        │
                                    │  │Cloudflared │ ◄── tunnels to CF      │
                                    │  │  tunnel    │                        │
                                    │  └───────────┘                        │
                                    └──────────────────────────────────────────┘
                                             │
                                    ┌────────┴────────┐
                                    │ Cloudflare Edge │
                                    │ (your-domain.com)│
                                    └─────────────────┘
```

## Deployment Flow

1. **Tag push** → GitHub Actions builds Docker image → Pushes to GHCR
2. **GitHub Actions** → SSHs into your server (via Cloudflared tunnel or direct)
3. **Server** → Pulls new Docker image → Runs migrations → Restarts services

---

## Server Setup (One-Time)

### 1. Install Docker and dependencies

```bash
# SSH into your server
ssh root@your-server

# Run the setup script
curl -fsSL https://raw.githubusercontent.com/sheenazien8/lakasir/main/deploy/setup-server.sh | bash
```

Or manually:

```bash
# Install Docker
curl -fsSL https://get.docker.com | sh

# Install Docker Compose V2
mkdir -p /usr/local/lib/docker/cli-plugins
curl -fsSL https://github.com/docker/compose/releases/latest/download/docker-compose-linux-$(uname -m) \
  -o /usr/local/lib/docker/cli-plugins/docker-compose
chmod +x /usr/local/lib/docker/cli-plugins/docker-compose
```

### 2. Set up Cloudflared Tunnel

```bash
# Install cloudflared
curl -fsSL https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64 \
  -o /usr/local/bin/cloudflared
chmod +x /usr/local/bin/cloudflared

# Login to Cloudflare
cloudflared tunnel login

# Create a tunnel
cloudflared tunnel create lakasir

# Note the tunnel ID from the output

# Route your domain to the tunnel
cloudflared tunnel route dns lakasir your-domain.com

# Create config file (see deploy/cloudflared-config.example.yml)
mkdir -p /etc/cloudflared
cp deploy/cloudflared-config.example.yml /etc/cloudflared/config.yml
# Edit config.yml with your tunnel ID and domain

# Run as a service
cloudflared service install
systemctl enable cloudflared
systemctl start cloudflared
```

### 3. Set up the app directory

```bash
mkdir -p /opt/lakasir
cd /opt/lakasir

# Download the production compose file
curl -fsSL https://raw.githubusercontent.com/sheenazien8/lakasir/main/docker-compose.prod.yml \
  -o docker-compose.prod.yml

# Create .env from template
curl -fsSL https://raw.githubusercontent.com/sheenazien8/lakasir/main/.env.production.example \
  -o .env

# Edit .env with your production values
nano .env
```

### 4. Generate APP_KEY

```bash
cd /opt/lakasir
docker compose -f docker-compose.prod.yml run --rm app php artisan key:generate --show
# Copy the output to .env as APP_KEY=...
```

### 5. Login to GHCR on the server

```bash
# You'll need a GitHub PAT with read:packages scope
echo "YOUR_GITHUB_TOKEN" | docker login ghcr.io -u YOUR_GITHUB_USERNAME --password-stdin
```

---

## GitHub Secrets Setup

Go to your repository → **Settings → Secrets and variables → Actions** and add these secrets:

### Required Secrets

| Secret | Description | Example |
|--------|-------------|---------|
| `SSH_PRIVATE_KEY` | Private SSH key for server access | `-----BEGIN OPENSSH PRIVATE KEY-----...` |
| `SERVER_HOST` | Server hostname/IP | `your-server.com` or `192.168.1.100` |
| `SERVER_USER` | SSH username | `root` or `deploy` |
| `SERVER_PORT` | SSH port | `22` |

### Optional Secrets (for Cloudflared SSH)

| Secret | Description | Example |
|--------|-------------|---------|
| `USE_CLOUDFLARED_SSH` | Set to `"true"` to SSH via Cloudflared tunnel | `true` |

### Setting up SSH Keys

```bash
# On your local machine, generate a deploy key
ssh-keygen -t ed25519 -C "github-actions-deploy" -f ~/.ssh/github-actions-deploy

# Copy the public key to your server
ssh-copy-id -i ~/.ssh/github-actions-deploy.pub root@your-server

# Add the private key to GitHub Secrets as SSH_PRIVATE_KEY
cat ~/.ssh/github-actions-deploy
# Copy the entire output including BEGIN/END lines
```

### Cloudflared SSH Setup (Optional)

If your server is behind Cloudflared and you want to SSH through the tunnel:

1. Add SSH service to your Cloudflared tunnel config:

```yaml
# In /etc/cloudflared/config.yml
ingress:
  - hostname: your-domain.com
    service: http://localhost:80
  - hostname: ssh.your-domain.com
    service: ssh://localhost:22
  - service: http_status:404
```

2. Add DNS route: `cloudflared tunnel route dns lakasir ssh.your-domain.com`

3. Set GitHub secret `USE_CLOUDFLARED_SSH` = `true`

4. Set `SERVER_HOST` to `ssh.your-domain.com`

---

## How to Deploy

### Automatic (on Release)

1. Push a tag: `git tag 1.2.3 && git push origin 1.2.3`
2. The **Pre-release** workflow builds and creates a GitHub release ZIP
3. Go to GitHub → Releases → Edit the pre-release → Click **Publish release**
4. The **Deploy** workflow automatically:
   - Builds Docker image → Pushes to GHCR
   - SSHs into your server → Pulls image → Runs migrations → Restarts

### Manual (via GitHub UI)

1. Go to **Actions → Deploy to Production**
2. Click **Run workflow**
3. Enter the version tag (e.g., `1.2.3`)
4. Click **Run workflow**

### Manual (on the server)

```bash
cd /opt/lakasir
docker compose -f docker-compose.prod.yml pull
docker compose -f docker-compose.prod.yml up -d
docker compose -f docker-compose.prod.yml exec app php artisan migrate --force
docker compose -f docker-compose.prod.yml exec app php artisan config:cache
```

---

## Monitoring

### Check container status

```bash
docker compose -f docker-compose.prod.yml ps
docker compose -f docker-compose.prod.yml logs -f app
```

### Check Cloudflared tunnel

```bash
systemctl status cloudflared
cloudflared tunnel info lakasir
```

### Health check endpoint

The app is accessible at `http://localhost:80` on the server. Cloudflared tunnels this to `https://your-domain.com`.

---

## Rollback

If a deployment fails, you can rollback:

```bash
cd /opt/lakasir

# Stop current containers
docker compose -f docker-compose.prod.yml down

# Edit .env to set IMAGE_TAG to the previous version
# e.g., change IMAGE_TAG=1.2.3 to IMAGE_TAG=1.2.2

# Pull the previous image
docker compose -f docker-compose.prod.yml pull

# Start with the previous version
docker compose -f docker-compose.prod.yml up -d
```

---

## Troubleshooting

### Container won't start

```bash
docker compose -f docker-compose.prod.yml logs app
docker compose -f docker-compose.prod.yml exec app php artisan --version
```

### SSH connection issues

```bash
# Test SSH locally first
ssh -i ~/.ssh/deploy_key root@your-server

# If using Cloudflared
cloudflared access ssh --hostname ssh.your-domain.com
```

### Docker login issues

```bash
# Re-login to GHCR
echo "YOUR_TOKEN" | docker login ghcr.io -u YOUR_USERNAME --password-stdin

# Check if you can pull the image
docker pull ghcr.io/sheenazien8/lakasir:latest
```

### Cloudflared tunnel not working

```bash
# Check tunnel status
cloudflared tunnel list
cloudflared tunnel info lakasir

# Test tunnel
cloudflared tunnel run lakasir
```