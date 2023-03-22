# syntax=docker/dockerfile:1.4
FROM node:18-alpine as builder_base
# # Check https://github.com/nodejs/docker-node/tree/b4117f9333da4138b03a546ec926ef50a31506c3#nodealpine to understand why libc6-compat might be needed.
RUN apk add --no-cache libc6-compat
WORKDIR /app
RUN yarn install

FROM builder_base as dev

ENV NODE_ENV=development

COPY --link docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

EXPOSE 5173
ENV PORT 5173

ENTRYPOINT ["docker-entrypoint"]
CMD ["yarn", "dev", "--host"]

FROM builder_base as prod_builder
WORKDIR /app
ENV NODE_ENV=production
COPY --link . .
RUN yarn install --production=false
RUN yarn build

FROM caddy:2-alpine AS prod
WORKDIR /app
COPY --link docker/Caddyfile /etc/caddy/Caddyfile
COPY --from=prod_builder --link /app/dist public/