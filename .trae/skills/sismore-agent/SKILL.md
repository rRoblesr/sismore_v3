---
name: "sismore-agent"
description: "Sismore V5 Project Expert. specialized in Laravel 8, Modular Architecture (Educacion, Salud, Presupuesto, vivienda, trabajo), DataTables, and Excel/ETL workflows. Invoke for all project-specific tasks."
---

# Sismore V5 Project Agent

This agent is specialized for the Sismore V5 codebase located at `c:\xampp\htdocs\aaapanel007\sismore_v5`.

## Project Architecture
- **Framework**: Laravel 8.40 (PHP 7.3/8.0)
- **Frontend**: Blade Templates + jQuery + Bootstrap 4 + DataTables.
- **Modules**:
  - `Administracion`: Users, Roles, Menus.
  - `Educacion`: Schools, Students, Nexus.
  - `Presupuesto`: SIAF, Income, Expenses.
  - `Salud`: Hospitals, Patients, Anemia.
  - `Trabajo`: PEA, Employment.
  - `Vivienda`: Water/Sanitation.

## Key Patterns & Conventions

### 1. DataTables (Yajra)
- **Controller**: Returns `DataTables::of($query)->make(true)`.
- **View**: Initializes via `$('#table').DataTable({ serverSide: true, ajax: '...' })`.
- **Note**: Always verify column names in JS match the JSON response.

### 2. Excel Imports/Exports (Maatwebsite)
- **Imports**: Located in `app/Imports/<Module>/`.
- **Exports**: Located in `app/Exports/<Module>/`.
- **Workflow**: Controller -> Import Class -> Model.

### 3. ETL & External Scripts
- Python scripts (e.g., `etl_siaf_scheduler.py`) are used for heavy data processing.
- Triggered via `.bat` files (e.g., `ejecutar_etl_web.bat`).
- **Best Practice**: Use `base_path()` in Controllers to reference these scripts.

### 4. File Locations
- **Models**: `app/Models/<Module>/<ModelName>.php`
- **Controllers**: `app/Http/Controllers/<Module>/<ControllerName>.php`
- **Views**: `resources/views/<module>/<view>.blade.php`

## Common Tasks & Solutions

- **"Update Table Columns"**:
  1. Check View for `columns` definition in JS.
  2. Check Controller for `json` or `listar` method.
  3. Check Model for `fillable` or relations.

- **"Fix Import Issue"**:
  1. Check `app/Imports/<Module>/...`.
  2. Verify Excel column mapping.
  3. Check Controller handling `store` or `import` method.

- **"Run ETL"**:
  1. Check `ejecutar_etl_web.bat`.
  2. Verify Python script execution logic.
