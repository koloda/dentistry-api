# Installation

[//]: # (ssh command for copy .env from .env.example)
```bash
cp .env.example .env
```

[//]: # (ssh command for install composer)
```bash
composer install
```

[//]: # (ssh command for generate key)
```bash
php artisan key:generate
```

[//]: # (ssh command for migrate database)
```bash
php artisan migrate
```
    
[//]: # (ssh command for seed database)
```bash
php artisan db:seed
```

[//]: # (make ./storage writable)
```bash
chmod -R 777 storage
```

[//]: # (make ./bootstrap/cache writable)
```bash
chmod -R 777 bootstrap/cache
```

[//]: # (ssh command for create symbolic link)
```bash
php artisan storage:link
```

[//]: # (ssh command for install npm)
```bash