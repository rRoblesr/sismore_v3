param(
  [Parameter(Mandatory = $true)]
  [string]$EnvPath
)

$c = Get-Content $EnvPath -Raw

if ($c -match "(?m)^APP_ENV=") {
  $c = $c -replace "(?m)^APP_ENV=.*$", "APP_ENV=production"
} else {
  $c = $c.TrimEnd() + "`r`nAPP_ENV=production`r`n"
}

if ($c -match "(?m)^APP_DEBUG=") {
  $c = $c -replace "(?m)^APP_DEBUG=.*$", "APP_DEBUG=false"
} else {
  $c = $c.TrimEnd() + "`r`nAPP_DEBUG=false`r`n"
}

Set-Content -Path $EnvPath -Value $c -Encoding ASCII

