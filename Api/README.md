# Cách cài đặt dự án để chạy

        composer install hoac composer install  --ignore-platform-reqs --no-scripts --no-plugins

        cp .env.example .env

        php artisan key:generate

        composer require tymon/jwt-auth

        composer require doctrine/dbal
        
        php artisan queue:table 

        Thêm: QUEUE_CONNECTION=database trong file .env

        php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

        php artisan jwt:secret

        php artisan migrate:fresh --seed 

        chay route testFaker để tạo dữ liệu cho bảng project_service

        Nhớ xem các file trong folder config là mail.php, auth.php sao cho đúng khi pull về

        Nếu muốn test queue và job thì mở 1 tab terminal riêng và chạy php artisan schedule:run

        Chạy cronjob php artisan schedule:work

        Nếu dùng queue thì nhớ luôn để lên trước schedule

## Chạy dự án :

         php artisan serve
## Bật xampp hoặc docker trước khi chạy 
## Cách chạy docker:

        docker compose up              

# Tài khoản mặc định:
    
    email: lechuhuuha@gmail.com
    password: password
