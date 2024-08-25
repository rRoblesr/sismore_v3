<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Select Año en Bootstrap</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .custom-select-container {
      position: relative;
    }

    .custom-select-container label {
      position: absolute;
      top: -10px;
      left: 10px;
      background-color: white;
      padding: 0 5px;
      font-size: 12px;
      color: #0d6efd;
    }

    .custom-select-container select {
      padding-left: 10px;
    }
  </style>
</head>
<body>

<div class="container mt-5">
  <div class="custom-select-container">
    <label for="yearSelect">Año</label>
    <select id="yearSelect" class="form-select">
      <option value="2022">2022</option>
      <option value="2023">2023</option>
      <option value="2024" selected>2024</option>
      <option value="2025">2025</option>
      <option value="2026">2026</option>
    </select>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
