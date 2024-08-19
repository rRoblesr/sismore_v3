{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Perú</title>
    <!-- Incluir Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 600px;
            width: 100%;
        }
    </style>
</head>

<body>
    <div id="map"></div>

    <!-- Incluir Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Inicializar el mapa centrado en Perú
        var map = L.map('map').setView([-9.19, -75.0152], 6);

        // Agregar la capa de mapa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Opcional: Agregar un marcador en Lima, Perú
        var marker = L.marker([-12.0464, -77.0428]).addTo(map)
            .bindPopup('Lima, Perú')
            .openPopup();
    </script>
</body>

</html> --}}

{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Ucayali</title>
    <!-- Incluir Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 600px;
            width: 100%;
        }
    </style>
</head>

<body>
    <div id="map"></div>

    <!-- Incluir Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Inicializar el mapa centrado en Ucayali
        var map = L.map('map').setView([-8.3803, -74.5200], 7);

        // Agregar la capa de mapa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Opcional: Agregar un marcador en Pucallpa, Ucayali
        var marker = L.marker([-8.3791, -74.5539]).addTo(map)
            .bindPopup('Pucallpa, Ucayali')
            .openPopup();

        // Si tienes un archivo GeoJSON para los límites de Ucayali, puedes cargarlo así:
        // var ucayaliGeoJSON = L.geoJSON(ucayaliData).addTo(map);

        // Ejemplo de cómo cargar un archivo GeoJSON desde una URL
        fetch('path-to-ucayali-geojson/ucayali.geojson')
            .then(response => response.json())
            .then(data => {
                L.geoJSON(data).addTo(map);
            })
            .catch(error => console.error('Error al cargar GeoJSON:', error));
    </script>
</body>

</html> --}}

{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Ucayali</title>
    <!-- Incluir Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 600px;
            width: 100%;
        }
    </style>
</head>

<body>
    <div id="map"></div>

    <!-- Incluir Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Inicializar el mapa centrado en Ucayali
        var map = L.map('map').setView([-8.3803, -74.5200], 7);

        // Agregar la capa de mapa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Cargar y mostrar el GeoJSON de Ucayali
        fetch('/geojson/ucayali.geojson')
            .then(response => response.json())
            .then(data => {
                // Establecer un estilo para el área de Ucayali
                var geojsonLayer = L.geoJSON(data, {
                    style: function(feature) {
                        return {
                            color: "#FF0000", // Color de borde
                            weight: 2, // Grosor del borde
                            fillColor: "#FFAAAA", // Color de relleno
                            fillOpacity: 0.5 // Opacidad del relleno
                        };
                    }
                }).addTo(map);

                // Ajustar el zoom y el centro para que se muestre solo Ucayali
                map.fitBounds(geojsonLayer.getBounds());
            })
            .catch(error => console.error('Error al cargar GeoJSON:', error));
    </script>
</body>

</html> --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Ucayali con Datos de Provincias</title>
    <!-- Incluir Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 600px;
            width: 100%;
        }
    </style>
</head>

<body>
    <div id="map"></div>

    <!-- Incluir Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Datos de PHP a JavaScript
        var provincias = {!! $provinciasJson !!};

        // Inicializar el mapa centrado en Ucayali
        var map = L.map('map').setView([-8.3803, -74.5200], 7);

        // Agregar la capa de mapa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Opcional: Estilo para cada provincia
        var provinceStyles = {
            "ATALAYA": {
                color: "#FF0000",
                fillColor: "#FFAAAA",
                fillOpacity: 0.5
            },
            "CORONEL PORTILLO": {
                color: "#00FF00",
                fillColor: "#AAFFAA",
                fillOpacity: 0.5
            },
            "PADRE ABAD": {
                color: "#0000FF",
                fillColor: "#AAAAFF",
                fillOpacity: 0.5
            },
            "PURUS": {
                color: "#FFFF00",
                fillColor: "#FFFFAA",
                fillOpacity: 0.5
            }
        };

        // Cargar el GeoJSON de Ucayali
        fetch('/geojson/ucayali.geojson')
            .then(response => response.json())
            .then(data => {
                L.geoJSON(data, {
                    style: function(feature) {
                        var provinceName = feature.properties
                        .NOMBRE; // Suponiendo que "NOMBRE" es la propiedad de nombre en GeoJSON
                        return provinceStyles[provinceName] || {
                            color: "#000",
                            fillColor: "#FFF",
                            fillOpacity: 0.5
                        };
                    },
                    onEachFeature: function(feature, layer) {
                        var provinceName = feature.properties.NOMBRE;
                        var provinceData = provincias.find(p => p.nombre === provinceName);
                        if (provinceData) {
                            layer.bindPopup(provinceName + ": " + provinceData.conteo + " habitantes");
                        }
                    }
                }).addTo(map);
            })
            .catch(error => console.error('Error al cargar GeoJSON:', error));
    </script>
</body>

</html>
+
