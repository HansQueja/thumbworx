# Base image
FROM node:18-alpine

# Set working directory
WORKDIR /app

# Install dependencies
COPY package.json package-lock.json ./
RUN npm install

# Copy app
COPY . .

# Expose dev server port
EXPOSE 3000

# Start development server
CMD ["npm", "run", "dev"]
