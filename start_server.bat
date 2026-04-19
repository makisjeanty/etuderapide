@echo off
set PHPRC=%~dp0php.ini
set PATH=%PATH%;C:\Program Files\PHP\8.5.4\nts\x64
echo Iniciando Makis Digital com Seguranca Maxima...
php -c "%~dp0php.ini" artisan serve
