#!/bin/bash

# Tambahkan perintah-perintah Artisan atau tugas lain yang ingin Anda jalankan di sini

    php artisan optimize
    php artisan cache:clear
    php artisan config:cache
    php artisan migrate:fresh
    php artisan db:seed

# Lanjutkan dengan menjalankan perintah yang akan memulai layanan web, misalnya menjalankan Apache atau Nginx
exec "$@"
exit

