FROM node:20.19.5-alpine3.22

ARG JQUERY_VERSION=3.3.1

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --omit=dev --ignore-scripts --no-audit --no-fund \
    && npm cache clean --force

COPY server.mjs ./server.mjs
COPY public ./public
COPY vendor ./vendor

RUN set -eu; \
    case "$JQUERY_VERSION" in \
      3.3.1) expected="160a426ff2894252cd7cebbdd6d6b7da8fcd319c65b70468f10b6690c45d02ef" ;; \
      3.4.0) expected="0497a8d2a9bde7db8c0466fae73e347a3258192811ed1108e3e096d5f34ac0e8" ;; \
      *) echo "지원 버전은 3.3.1 또는 3.4.0입니다." >&2; exit 1 ;; \
    esac; \
    mkdir -p runtime; \
    cp "vendor/jquery-${JQUERY_VERSION}.min.js" runtime/jquery.js; \
    actual="$(sha256sum runtime/jquery.js | awk '{print $1}')"; \
    test "$actual" = "$expected"; \
    printf '%s\n' "$JQUERY_VERSION" > runtime/jquery.version; \
    chown -R node:node /app

ENV NODE_ENV=production

USER node

EXPOSE 3000

CMD ["node", "server.mjs"]
