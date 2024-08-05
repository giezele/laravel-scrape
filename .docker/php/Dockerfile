FROM php:8.3-fpm

# Step 1: Install basic utilities
RUN apt-get update && apt-get install -y \
    wget \
    gnupg2 \
    curl \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Step 2: Install development tools
RUN apt-get update && apt-get install -y \
    build-essential \
    vim \
    git \
    procps \
    jq \
    && rm -rf /var/lib/apt/lists/*

# Step 3: Install libraries needed for PHP extensions and other utilities
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    libpq-dev \
    libicu-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && rm -rf /var/lib/apt/lists/*

# Step 4: Install MySQL client
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# Step 5: Install libraries for Google Chrome
RUN apt-get update && apt-get install -y \
    libglib2.0-0 \
    libnss3 \
    libx11-xcb1 \
    libxcomposite1 \
    libxi6 \
    libxrender1 \
    xdg-utils \
    libxrandr2 \
    libatk1.0-0 \
    libatk-bridge2.0-0 \
    libasound2 \
    libdrm2 \
    libgbm1 \
    libnspr4 \
    libxshmfence1 \
    libxcursor1 \
    libxdamage1 \
    libxfixes3 \
    libcups2 \
    libxkbcommon0 \
    libpango-1.0-0 \
    libpangoft2-1.0-0 \
    libpangocairo-1.0-0 \
    && rm -rf /var/lib/apt/lists/*

# Step 6: Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql intl gd zip

# Step 7: Install Redis via PECL and enable it
RUN pecl install redis \
    && docker-php-ext-enable redis

# Step 8: Install Composer
RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Step 9: Node.js and Yarn installation
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get update && apt-get install -y nodejs \
    && npm install -g npm@latest yarn \
    && rm -rf /var/lib/apt/lists/*

# Download and install Chrome for Testing
RUN CHROME_VERSION="115.0.5790.102" \
    && wget -q https://edgedl.me.gvt1.com/edgedl/chrome/chrome-for-testing/$CHROME_VERSION/linux64/chrome-linux64.zip -P /tmp \
    && unzip -o /tmp/chrome-linux64.zip -d /usr/local \
    && ln -sf /usr/local/chrome-linux64/chrome /usr/bin/google-chrome \
    && rm /tmp/chrome-linux64.zip

# Retry logic to ensure ChromeDriver download completes
RUN set -ex; \
    for i in {1..5}; do \
        echo "Attempt $i: Downloading ChromeDriver..."; \
        wget -q https://edgedl.me.gvt1.com/edgedl/chrome/chrome-for-testing/115.0.5790.102/linux64/chromedriver-linux64.zip -P /tmp && break || sleep 5; \
    done \
    && unzip -o /tmp/chromedriver-linux64.zip -d /usr/local/bin \
    && mv /usr/local/bin/chromedriver-linux64/chromedriver /usr/local/bin/chromedriver \
    && chmod +x /usr/local/bin/chromedriver \
    && rm /tmp/chromedriver-linux64.zip \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www
EXPOSE 5173
