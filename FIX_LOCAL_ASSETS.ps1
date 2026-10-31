# Windows 10/11 PowerShell 5.1 compatible. No modifications to Laravel source code.
# Place next to 'artisan', then run FIX_LOCAL_ASSETS.cmd.
Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'
$project = $PSScriptRoot
$public = Join-Path $project 'public'

if (!(Test-Path -LiteralPath (Join-Path $project 'artisan') -PathType Leaf) -or
    !(Test-Path -LiteralPath (Join-Path $public 'index.php') -PathType Leaf)) {
    Write-Host '[ERROR] Put FIX_LOCAL_ASSETS.cmd/.ps1 in your Laravel project ROOT (next to artisan).' -ForegroundColor Red
    exit 1
}

function Copy-Safe([string]$from, [string]$to) {
    if (!(Test-Path -LiteralPath $to)) {
        New-Item -ItemType Directory -Path $to -Force | Out-Null
    }
    # /E = include subdirectories. /XO = do NOT overwrite newer destination files.
    # NO /MIR or /PURGE: never delete any existing uploads or assets.
    & robocopy.exe $from $to /E /XO /R:1 /W:1 /NFL /NDL /NJH /NJS /NP | Out-Null
    if ($LASTEXITCODE -gt 7) {
        throw "Copy failed for $from (robocopy exit code $LASTEXITCODE)"
    }
}

$folders = @('frontend', 'backend', 'media')
$success = $true
foreach ($name in $folders) {
    $source = Join-Path $project $name
    $target = Join-Path $public $name
    if (!(Test-Path -LiteralPath $source -PathType Container)) {
        Write-Host "[ERROR] Source folder not found: $source" -ForegroundColor Red
        $success = $false
        continue
    }
    if (Test-Path -LiteralPath $target) {
        $existing = Get-Item -LiteralPath $target -Force
        if ([bool]($existing.Attributes -band [System.IO.FileAttributes]::ReparsePoint)) {
            Write-Host "[OK] public/$name is already a directory link (not changed)." -ForegroundColor Green
            continue
        }
        if (!$existing.PSIsContainer) {
            Write-Host "[ERROR] public/$name is a FILE; cannot link assets." -ForegroundColor Red
            $success = $false
            continue
        }
        # A previous fix may have created physical copies. Don't delete/move them.
        # Safe fallback: merge newer root assets into public without deleting files.
        Write-Host "[INFO] public/$name already exists as a folder. Safely synchronizing (no deletion)..." -ForegroundColor Yellow
        try { Copy-Safe $source $target }
        catch { Write-Host "[ERROR] $_" -ForegroundColor Red; $success = $false }
        Write-Host "[INFO] For this folder, rerun FIX_LOCAL_ASSETS.cmd after editing assets in $name/." -ForegroundColor Yellow
        continue
    }
    try {
        New-Item -ItemType Junction -Path $target -Target $source -ErrorAction Stop | Out-Null
        Write-Host "[OK] public/$name ==> $name (live link; no duplicate files)" -ForegroundColor Green
    }
    catch {
        Write-Host "[WARN] Could not create a junction for $name. Copying safely instead..." -ForegroundColor Yellow
        try { Copy-Safe $source $target }
        catch { Write-Host "[ERROR] $_" -ForegroundColor Red; $success = $false }
    }
}

# Match original Hostinger webroot for these two small root-level static files.
foreach ($file in @('favicon.ico', 'robots.txt')) {
    $source = Join-Path $project $file
    $target = Join-Path $public $file
    if ((Test-Path -LiteralPath $source -PathType Leaf) -and !(Test-Path -LiteralPath $target)) {
        Copy-Item -LiteralPath $source -Destination $target -ErrorAction Stop
        Write-Host "[OK] public/$file available" -ForegroundColor Green
    }
}

$checks = @(
    'frontend/assets/css/main.css',
    'frontend/assets/js/main.js',
    'frontend/assets/images/logo/top.png',
    'backend/assets/css/theme.min.css',
    'backend/assets/js/theme.min.js',
    'backend/assets/fonts/feather/feather.css',
    'media/logo/frontlogo.png',
    'media/logo/backlogo.png',
    'build/manifest.json'
)
foreach ($relative in $checks) {
    $path = Join-Path $public ($relative.Replace('/', '\'))
    if (Test-Path -LiteralPath $path -PathType Leaf) {
        Write-Host "[PASS] /$relative" -ForegroundColor Green
    }
    else {
        Write-Host "[FAIL] /$relative" -ForegroundColor Red
        $success = $false
    }
}

if ($success) {
    Write-Host ''
    Write-Host 'SUCCESS: Local CSS/JS/images now map to your existing root asset folders.' -ForegroundColor Green
    Write-Host 'Run: php artisan optimize:clear'
    Write-Host 'Run: php artisan serve'
    Write-Host 'Test: http://127.0.0.1:8000/admin/login'
    Write-Host 'Hostinger structure and Laravel source files were NOT modified.'
    exit 0
}
Write-Host '[ERROR] Some assets are missing; inspect the messages above.' -ForegroundColor Red
exit 1
