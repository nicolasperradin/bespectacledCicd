FROM node:lts-alpine
WORKDIR /var/www/app
# RUN npm install -g http-server
COPY package*.json ./
RUN npm install
# COPY . .
# RUN npm run build
EXPOSE 8080
CMD ["npm", "start"]
# CMD [ "http-server", "-P", "http://localhost:8080?", "dist" ]