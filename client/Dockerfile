FROM node:16.16.0-alpine3.16 AS base
ARG MODE=development
ENV APP_ENV=$MODE
WORKDIR /app
COPY ./package*.json ./
RUN npm install
COPY . .

FROM base AS serve-stage
ENV PORT=3000
EXPOSE 3000
CMD ["npm", "run", "serve"]

FROM base AS tmp-build-stage
RUN npm run build

FROM node:16.16.0-alpine3.16 AS build-stage
ARG MODE=development
ENV APP_ENV=$MODE
ENV PORT=3000
EXPOSE 3000
WORKDIR /app
COPY --from=tmp-build-stage /app/package*.json /app/server.js ./
COPY --from=tmp-build-stage /app/node_modules ./node_modules
COPY --from=tmp-build-stage /app/dist ./dist
CMD ["node", "server.js"]