# ── Build stage ───────────────────────────────────────────────────────────────
FROM node:20-alpine AS builder

WORKDIR /app

# Copy dependency manifests and install production deps only
COPY package*.json ./
RUN npm ci --omit=dev

# ── Production image ───────────────────────────────────────────────────────────
FROM node:20-alpine

WORKDIR /app

# Copy installed node_modules from builder
COPY --from=builder /app/node_modules ./node_modules

# Copy application source
COPY . .

# Render (and most PaaS) sets PORT automatically; default to 3000 locally
ENV PORT=3000

EXPOSE 3000

# Start the server
CMD ["node", "index.js"]
