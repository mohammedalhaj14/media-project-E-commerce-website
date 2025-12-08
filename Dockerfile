# المرحلة 1: البناء (Build Stage) - استخدام أحدث إصدار من PHP FPM مع Alpine Linux (خفيف الوزن)
FROM php:8.2-fpm-alpine

# تثبيت متطلبات نظام التشغيل وحزم PHP الضرورية:
# - git: لسحب الكود (إذا لزم الأمر)
# - unzip: لفك ضغط تبعيات Composer
# - postgresql-dev: لتمكين درايفر PDO PostgreSQL (الاتصال بقاعدة البيانات)
# - libpq: مكتبة Postgres
# - pdo_pgsql: درايفر PDO Postgres
RUN apk update && apk add \
    git \
    unzip \
    postgresql-dev \
    libpq \
    && docker-php-ext-install pdo_pgsql

# تثبيت Composer (مدير حزم PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# تعيين دليل العمل داخل الحاوية
WORKDIR /var/www/html

# نسخ الكود من المستودع إلى دليل العمل
COPY . /var/www/html

# تشغيل أمر البناء: تثبيت تبعيات Composer (إذا كانت موجودة)
# إذا لم يكن لديك تبعيات في composer.json، هذا الأمر لن يفعل شيئاً.
RUN composer install --no-dev --optimize-autoloader --no-interaction

# تعريض المنفذ 9000 (الافتراضي لـ PHP-FPM)
EXPOSE 9000

# أمر التشغيل (Start Command) الذي يستخدمه Render لخدمة الويب
# نستخدم PHP-FPM لتشغيل التطبيق
CMD ["php-fpm"]