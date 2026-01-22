FROM node:20-alpine

WORKDIR /app

# Copia apenas os arquivos de dependência primeiro para aproveitar o cache do Docker
COPY package*.json ./
RUN npm install

COPY . .

# Expõe a porta padrão do Vite
EXPOSE 5173

CMD ["npm", "run", "dev", "--", "--host"]