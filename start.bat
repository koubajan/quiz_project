@echo off
set PHP=C:\php\php.exe

echo ================================
echo    Quiz Project - Start
echo ================================
echo.

REM Install dependencies if vendor folder is missing
if not exist "vendor" (
    echo [1/2] Instaluji zavislosti...
    %PHP% -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    %PHP% composer-setup.php --quiet
    del composer-setup.php
    %PHP% composer.phar update --no-interaction
    echo.
)

echo [2/2] Spoustim server...
echo.
echo  Otevri v prohlizeci: http://localhost:8000/quiz.html
echo  Pro ukonceni stiskni Ctrl+C
echo.

%PHP% artisan serve
pause
