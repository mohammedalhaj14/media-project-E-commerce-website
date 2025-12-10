# --------------------------------------------------------------------------
# المرحلة الأولى: بناء بيئة PHP-FPM (لإعداد الكود والمكتبات)
# --------------------------------------------------------------------------
FROM php:8.2-fpm-alpine AS builder

# تثبيت متطلبات نظام التشغيل (Git, unzip) وإضافة مكتبات PostgreSQL
RUN apk update && apk add --no-cache \
    git \
    unzip \
    libpq \
    postgresql-dev

# تثبيت إضافة PDO PostgreSQL
RUN docker-php-ext-install pdo_pgsql

# تعيين دليل العمل
WORKDIR /var/www/html

# نسخ الكود إلى الدليل
COPY . /var/www/html

# تثبيت Composer (إذا لم يكن مثبتاً)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# تشغيل Composer (إذا كان لديك ملف composer.json)
# إذا لم يكن لديك ملف composer.json، يمكنك حذف السطر التالي
RUN composer install --no-dev --optimize-autoload --no-interaction


# --------------------------------------------------------------------------
# المرحلة الثانية: إضافة Nginx وتشغيل الخدمة
# --------------------------------------------------------------------------
FROM nginx:alpine

# تثبيت أدوات التشغيل المشترك (مثل باش)
RUN apk update && apk add --no-cache bash

# نسخ ملف إعداد Nginx إلى المسار الصحيح
# يجب أن يكون المسار: ./nginx/default.conf
COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf

# نسخ الكود والملفات من مرحلة البناء
COPY --from=builder /var/www/html /var/www/html

# نسخ ملف PHP-FPM التنفيذي من المرحلة الأولى (لضمان وجوده)
COPY --from=builder /usr/local/sbin/php-fpm /usr/local/sbin/php-fpm

# تعيين دليل العمل
WORKDIR /var/www/html

# كشف المنفذ 80 (لـ Nginx)
EXPOSE 80

# الأمر الافتراضي للتشغيل (استبدال أمر CMD في Render)
# سنعتمد على أمر Docker Command في Render للحصول على مرونة أكبر، ولكن يمكن استخدامه كاحتياطي
# CMD sh -c "php-fpm -D && nginx -g 'daemon off;'"# --------------------------------------------------------------------------
# المرحلة الأولى: بناء بيئة PHP-FPM (لإعداد الكود والمكتبات)
# --------------------------------------------------------------------------
FROM php:8.2-fpm-alpine AS builder

# تثبيت متطلبات نظام التشغيل (Git, unzip) وإضافة مكتبات PostgreSQL
RUN apk update && apk add --no-cache \
    git \
    unzip \
    libpq \
    postgresql-dev

# تثبيت إضافة PDO PostgreSQL
RUN docker-php-ext-install pdo_pgsql

# تعيين دليل العمل
WORKDIR /var/www/html

# نسخ الكود إلى الدليل
COPY . /var/www/html

# تثبيت Composer (إذا لم يكن مثبتاً)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# تشغيل Composer (إذا كان لديك ملف composer.json)
# إذا لم يكن لديك ملف composer.json، يمكنك حذف السطر التالي
RUN composer install --no-dev --optimize-autoload --no-interaction


# --------------------------------------------------------------------------
# المرحلة الثانية: إضافة Nginx وتشغيل الخدمة
# --------------------------------------------------------------------------
FROM nginx:alpine

# تثبيت أدوات التشغيل المشترك (مثل باش)
RUN apk update && apk add --no-cache bash

# نسخ ملف إعداد Nginx إلى المسار الصحيح
# يجب أن يكون المسار: ./nginx/default.conf
COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf

# نسخ الكود والملفات من مرحلة البناء
COPY --from=builder /var/www/html /var/www/html

# نسخ ملف PHP-FPM التنفيذي من المرحلة الأولى (لضمان وجوده)
COPY --from=builder /usr/local/sbin/php-fpm /usr/local/sbin/php-fpm

# تعيين دليل العمل
WORKDIR /var/www/html

# كشف المنفذ 80 (لـ Nginx)
EXPOSE 80

# الأمر الافتراضي للتشغيل (استبدال أمر CMD في Render)
# سنعتمد على أمر Docker Command في Render للحصول على مرونة أكبر، ولكن يمكن استخدامه كاحتياطي
# CMD sh -c "php-fpm -D && nginx -g 'daemon off;'"