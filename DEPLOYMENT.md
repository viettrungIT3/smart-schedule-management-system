# ScheduleFlow Deployment Guide

## 🚀 **Production Deployment**

ScheduleFlow đã được thiết kế với Docker containerization để đảm bảo tính nhất quán giữa các môi trường.

---

## 📋 **Prerequisites**

### **System Requirements:**
- **Docker:** Version 20.10+
- **Docker Compose:** Version 2.0+
- **Git:** Version 2.30+
- **Make:** Version 4.0+ (optional, for Makefile commands)

### **Hardware Requirements:**
- **CPU:** 2 cores minimum, 4 cores recommended
- **RAM:** 4GB minimum, 8GB recommended
- **Storage:** 10GB minimum, 20GB recommended
- **Network:** Internet connection for Docker images

---

## 🏗️ **Deployment Steps**

### **1. Clone Repository**
```bash
git clone <repository-url>
cd smart-schedule-management-system
```

### **2. Environment Configuration**
```bash
# Copy environment template
cp src/env src/.env

# Edit configuration (optional - defaults are provided)
nano src/.env
```

### **3. Production Environment Variables**
```bash
# In src/.env, set production values:
app.baseURL = 'https://your-domain.com'
app.environment = 'production'

# Database configuration
database.default.hostname = 'db'
database.default.database = 'scheduleflow'
database.default.username = 'ssms'
database.default.password = 'your-secure-password'
database.default.DBDriver = 'MySQLi'
database.default.port = 3306
```

### **4. Build and Start Services**
```bash
# Build and start all services
make init

# Or manually:
docker-compose up -d --build
```

### **5. Database Setup**
```bash
# Run migrations
make migrate

# Seed initial data (optional)
make seed
```

### **6. Verify Deployment**
```bash
# Check health
curl http://localhost:8088/health

# Run smoke tests
make test
```

---

## 🔧 **Configuration**

### **Docker Compose Configuration**
```yaml
# docker-compose.yml
services:
  app:
    build: ./docker/app
    ports:
      - "8088:80"
    volumes:
      - ./src:/var/www/html
    depends_on:
      - db
    networks:
      - ssms_net

  db:
    image: mysql:8.0
    ports:
      - "3307:3306"
    environment:
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_DATABASE: scheduleflow
      MYSQL_USER: ssms
      MYSQL_PASSWORD: ssms_pass
    volumes:
      - db_data:/var/lib/mysql
    networks:
      - ssms_net
```

### **Apache Configuration**
```apache
# docker/app/vhost.conf
<VirtualHost *:80>
    DocumentRoot /var/www/html/public
    <Directory /var/www/html/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### **PHP Configuration**
```dockerfile
# docker/app/Dockerfile
FROM php:8.2-apache

# Install PHP extensions
RUN docker-php-ext-install intl pdo pdo_mysql zip gd mysqli

# Enable Apache modules
RUN a2enmod rewrite headers

# Set document root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
```

---

## 🔒 **Security Configuration**

### **1. Environment Security**
```bash
# Generate secure app key
php spark key:generate

# Set secure session configuration
app.sessionDriver = 'CodeIgniter\Session\Handlers\DatabaseHandler'
app.sessionSavePath = 'ci_sessions'
```

### **2. Database Security**
```bash
# Use strong passwords
MYSQL_ROOT_PASSWORD=your-very-secure-root-password
MYSQL_PASSWORD=your-very-secure-user-password

# Limit database access
# Only allow connections from app container
```

### **3. File Permissions**
```bash
# Set proper permissions
chmod -R 755 src/writable/
chown -R www-data:www-data src/writable/
```

---

## 📊 **Monitoring & Logging**

### **1. Application Logs**
```bash
# View application logs
make logs

# Or directly:
docker-compose logs -f app
```

### **2. Database Logs**
```bash
# View database logs
docker-compose logs -f db
```

### **3. Health Monitoring**
```bash
# Health check endpoint
curl http://localhost:8088/health

# Expected response:
{
  "status": "ok"
}
```

---

## 🔄 **Maintenance**

### **1. Database Backup**
```bash
# Create backup
docker-compose exec db mysqldump -u root -p scheduleflow > backup.sql

# Restore backup
docker-compose exec -T db mysql -u root -p scheduleflow < backup.sql
```

### **2. Application Updates**
```bash
# Pull latest changes
git pull origin master

# Rebuild containers
docker-compose up -d --build

# Run migrations if needed
make migrate
```

### **3. Cleanup**
```bash
# Remove unused containers and images
docker system prune -a

# Clean volumes (WARNING: This will delete data)
make clean
```

---

## 🌐 **Reverse Proxy (Nginx)**

### **Nginx Configuration**
```nginx
server {
    listen 80;
    server_name your-domain.com;

    location / {
        proxy_pass http://localhost:8088;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

### **SSL Configuration (Let's Encrypt)**
```bash
# Install certbot
sudo apt install certbot python3-certbot-nginx

# Get SSL certificate
sudo certbot --nginx -d your-domain.com
```

---

## 🚨 **Troubleshooting**

### **Common Issues**

#### **1. Port Already in Use**
```bash
# Check what's using the port
sudo lsof -i :8088

# Kill the process or change port in docker-compose.yml
```

#### **2. Database Connection Failed**
```bash
# Check database status
docker-compose ps db

# Check database logs
docker-compose logs db

# Restart database
docker-compose restart db
```

#### **3. Permission Denied**
```bash
# Fix file permissions
sudo chown -R $USER:$USER src/writable/
chmod -R 755 src/writable/
```

#### **4. Migration Errors**
```bash
# Check migration status
make migrate-status

# Reset migrations (WARNING: This will delete data)
docker-compose exec app php spark migrate:rollback
docker-compose exec app php spark migrate
```

---

## 📈 **Performance Optimization**

### **1. PHP Configuration**
```ini
# In docker/app/php.ini
memory_limit = 256M
max_execution_time = 300
upload_max_filesize = 10M
post_max_size = 10M
```

### **2. MySQL Configuration**
```ini
# In docker/mysql/my.cnf
[mysqld]
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
max_connections = 200
```

### **3. Apache Configuration**
```apache
# In docker/app/apache2.conf
ServerTokens Prod
ServerSignature Off
KeepAlive On
MaxKeepAliveRequests 100
KeepAliveTimeout 5
```

---

## 🔄 **CI/CD Pipeline**

### **GitHub Actions Workflow**
```yaml
# .github/workflows/ci.yml
name: CI
on:
  push:
    branches: [ master, develop ]
  pull_request:
    branches: [ master, develop ]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
    - uses: actions/checkout@v2
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.2'
    - name: Setup MySQL
      uses: mirromutth/mysql-action@v1.1
      with:
        mysql version: '8.0'
        mysql root password: ${{ secrets.CI_DB_ROOT_PASSWORD }}
        mysql database: scheduleflow
        mysql user: ssms
        mysql password: ${{ secrets.CI_DB_PASSWORD }}
    - name: Install dependencies
      run: composer install
    - name: Run migrations
      run: php spark migrate
    - name: Run tests
      run: make test
```

---

## 📞 **Support**

### **Documentation**
- **README.md:** Project overview and quick start
- **API_DOCUMENTATION.md:** Complete API reference
- **SRS.md:** Software Requirements Specification

### **Testing**
- **Health Check:** `curl http://localhost:8088/health`
- **Smoke Tests:** `make test`
- **API Tests:** `bash scripts/tests/health.sh`

### **Logs**
- **Application:** `make logs`
- **Database:** `docker-compose logs db`
- **All Services:** `docker-compose logs`

---

**ScheduleFlow** - Production-ready Smart Schedule Management System
