FROM node:16.16.0-alpine3.16 as base
WORKDIR /app
COPY ./package*.json ./
RUN npm install
COPY . .

FROM base as local
EXPOSE 3000
CMD ["npm", "run", "serve"]

FROM base as build-production
RUN npm run build-production
EXPOSE 3000
CMD ["node", "./server.js"]

FROM base as build-staging
RUN npm run build-staging
EXPOSE 3000
CMD ["node", "./server.js"]