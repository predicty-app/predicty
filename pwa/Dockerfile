FROM node:18-alpine as builder_base
# # Check https://github.com/nodejs/docker-node/tree/b4117f9333da4138b03a546ec926ef50a31506c3#nodealpine to understand why libc6-compat might be needed.
RUN apk add --no-cache libc6-compat
WORKDIR /app

FROM builder_base as dev

RUN yarn install

EXPOSE 5173
ENV PORT 5173

CMD ["yarn", "dev", "--host"]

FROM dev as prod