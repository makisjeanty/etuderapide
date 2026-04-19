FROM oven/bun:latest

WORKDIR /app

# Install python for the analyzer
RUN apt-get update && apt-get install -y python3 python3-pip

COPY package.json bun.lock ./
RUN bun install

COPY . .

EXPOSE 3001

CMD ["bun", "run", "index.ts"]
