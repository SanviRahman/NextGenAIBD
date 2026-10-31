@echo off
setlocal
pushd "%~dp0"
echo =====================================================
echo Sunnah Digital Agency - Local Asset Connection Fix
 echo =====================================================
powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%~dp0FIX_LOCAL_ASSETS.ps1"
set "result=%errorlevel%"
popd
if not "%result%"=="0" (
  echo.
  echo Asset connection could not be completed. Read the messages above.
)
echo.
pause
exit /b %result%
