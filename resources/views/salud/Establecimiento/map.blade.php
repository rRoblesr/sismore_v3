<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Google</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=TU_CLAVE_API&callback=initMap" async defer></script>
    <script>
        let map;
        let marker;
        let infoWindow;

        function initMap() {
            const initialPosition = {
                lat: 19.432608,
                lng: -99.133209
            }; // Ejemplo: Ciudad de México
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 13,
                center: initialPosition
            });

            marker = new google.maps.Marker({
                position: initialPosition,
                map: map,
                draggable: true
            });

            infoWindow = new google.maps.InfoWindow();

            // Cuando se haga clic en el botón, obtén la posición del marcador
            document.getElementById('addCoordinates').addEventListener('click', function() {
                const position = marker.getPosition();
                const lat = position.lat();
                const lng = position.lng();
                const description = document.getElementById('description').value;

                // Aquí puedes enviar las coordenadas y la descripción al backend
                fetch('/guardar-coordenadas', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            lat: lat,
                            lng: lng,
                            description: description
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert('Coordenadas guardadas');
                        console.log(data);
                    })
                    .catch(error => console.error('Error:', error));
            });
        }
    </script>
</head>

<body onload="initMap()">
    <h1>Agregar Coordenadas al Mapa</h1>
    <div id="map" style="height: 400px; width: 100%;"></div>
    <br>
    <textarea id="description" placeholder="Escribe una descripción..."></textarea>
    <br>
    <button id="addCoordinates">Guardar Coordenadas</button>
</body>

</html>
