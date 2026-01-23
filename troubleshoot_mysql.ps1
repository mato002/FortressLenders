# XAMPP MySQL Troubleshooting Script
Write-Host "=== XAMPP MySQL Troubleshooting ===" -ForegroundColor Cyan
Write-Host ""

# 1. Check if port 3306 is in use
Write-Host "1. Checking port 3306..." -ForegroundColor Yellow
$portCheck = netstat -ano | findstr :3306
if ($portCheck) {
    Write-Host "   WARNING: Port 3306 is already in use!" -ForegroundColor Red
    Write-Host "   Details: $portCheck" -ForegroundColor Red
    Write-Host "   Solution: Stop the service using this port or change MySQL port in my.ini" -ForegroundColor Yellow
} else {
    Write-Host "   Port 3306 is available" -ForegroundColor Green
}
Write-Host ""

# 2. Check for running MySQL processes
Write-Host "2. Checking for MySQL processes..." -ForegroundColor Yellow
$mysqlProcesses = Get-Process | Where-Object {$_.ProcessName -like "*mysql*"}
if ($mysqlProcesses) {
    Write-Host "   Found MySQL processes:" -ForegroundColor Yellow
    $mysqlProcesses | ForEach-Object { Write-Host "   - $($_.ProcessName) (PID: $($_.Id))" -ForegroundColor Yellow }
    Write-Host "   Solution: Stop these processes from XAMPP Control Panel or Task Manager" -ForegroundColor Yellow
} else {
    Write-Host "   No MySQL processes running" -ForegroundColor Green
}
Write-Host ""

# 3. Check Windows MySQL Service
Write-Host "3. Checking Windows MySQL Service..." -ForegroundColor Yellow
$mysqlService = Get-Service | Where-Object {$_.Name -like "*mysql*"}
if ($mysqlService) {
    Write-Host "   Found MySQL service(s):" -ForegroundColor Yellow
    $mysqlService | ForEach-Object { 
        Write-Host "   - $($_.Name): $($_.Status)" -ForegroundColor Yellow
        if ($_.Status -eq "Running") {
            Write-Host "     WARNING: Windows MySQL service is running. Stop it to use XAMPP MySQL." -ForegroundColor Red
        }
    }
} else {
    Write-Host "   No Windows MySQL service found" -ForegroundColor Green
}
Write-Host ""

# 4. Check XAMPP MySQL files
Write-Host "4. Checking XAMPP MySQL installation..." -ForegroundColor Yellow
$xamppPath = "C:\xampp\mysql"
if (Test-Path $xamppPath) {
    Write-Host "   XAMPP MySQL path exists: $xamppPath" -ForegroundColor Green
    
    if (Test-Path "$xamppPath\bin\mysqld.exe") {
        Write-Host "   MySQL executable found" -ForegroundColor Green
    } else {
        Write-Host "   ERROR: MySQL executable not found!" -ForegroundColor Red
    }
    
    if (Test-Path "$xamppPath\data") {
        Write-Host "   Data directory exists" -ForegroundColor Green
    } else {
        Write-Host "   ERROR: Data directory not found!" -ForegroundColor Red
    }
    
    if (Test-Path "$xamppPath\bin\my.ini") {
        Write-Host "   Configuration file found" -ForegroundColor Green
    } elseif (Test-Path "$xamppPath\my.ini") {
        Write-Host "   Configuration file found" -ForegroundColor Green
    } else {
        Write-Host "   WARNING: Configuration file not found" -ForegroundColor Yellow
    }
} else {
    Write-Host "   ERROR: XAMPP MySQL not found at $xamppPath" -ForegroundColor Red
}
Write-Host ""

# 5. Check error logs
Write-Host "5. Checking for error logs..." -ForegroundColor Yellow
$errorLogs = Get-ChildItem "$xamppPath\data" -Filter "*.err" -ErrorAction SilentlyContinue
if ($errorLogs) {
    Write-Host "   Found error log(s):" -ForegroundColor Yellow
    $errorLogs | ForEach-Object {
        Write-Host "   - $($_.Name)" -ForegroundColor Yellow
        Write-Host "   Last 10 lines:" -ForegroundColor Cyan
        Get-Content $_.FullName -Tail 10 | ForEach-Object { Write-Host "     $_" -ForegroundColor Gray }
    }
} else {
    Write-Host "   No error logs found (this might mean MySQL hasn't tried to start)" -ForegroundColor Yellow
}
Write-Host ""

# 6. Check permissions
Write-Host "6. Checking data directory permissions..." -ForegroundColor Yellow
try {
    $acl = Get-Acl "$xamppPath\data"
    Write-Host "   Data directory is accessible" -ForegroundColor Green
} catch {
    Write-Host "   ERROR: Cannot access data directory - Permission issue!" -ForegroundColor Red
    Write-Host "   Solution: Run XAMPP Control Panel as Administrator" -ForegroundColor Yellow
}
Write-Host ""

Write-Host "=== Common Solutions ===" -ForegroundColor Cyan
Write-Host "1. Run XAMPP Control Panel as Administrator (Right-click > Run as Administrator)" -ForegroundColor White
Write-Host "2. Stop any Windows MySQL service: net stop MySQL (if exists)" -ForegroundColor White
Write-Host "3. Check if port 3306 is free or change it in my.ini" -ForegroundColor White
Write-Host "4. Try starting MySQL from command line to see detailed errors:" -ForegroundColor White
Write-Host "   cd C:\xampp\mysql\bin" -ForegroundColor Gray
Write-Host "   .\mysqld.exe --console" -ForegroundColor Gray
Write-Host "5. If data directory is corrupted, backup and reinitialize:" -ForegroundColor White
Write-Host "   (Backup C:\xampp\mysql\data first!)" -ForegroundColor Yellow
Write-Host "   cd C:\xampp\mysql\bin" -ForegroundColor Gray
Write-Host "   .\mysqld.exe --initialize-insecure" -ForegroundColor Gray
