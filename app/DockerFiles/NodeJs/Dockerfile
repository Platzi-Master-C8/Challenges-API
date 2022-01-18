FROM node:latest

WORKDIR usr/src/app

COPY package*.json ./

RUN npm install

EXPOSE 8081
ENTRYPOINT ["tail", "-f", "/dev/null"]
