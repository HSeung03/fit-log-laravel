@echo off
:: 한글 깨짐 방지
chcp 65001 > nul

echo [1/2] XAMPP MySQL 실행 중...
start /b "" "C:\xampp\mysql\bin\mysqld.exe"

echo [2/2] 우분투(Ubuntu) 라라벨 서버 실행 중...
:: -d Ubuntu 옵션을 넣어 정확한 배포판을 지정했습니다.
wsl -d Ubuntu -e bash -c "cd /home/lsh232986/LaravelApps/pr_sec_project && (php artisan serve & npm run dev)"

pause