<?php
//Se crea y agrega un token sencillo a la session.
require('../src/config.php');
require('../src/utils/protectRoute.php');
require('../src/utils/genToken.php');

session_start();
protectRoute('/login.php');
$token = genToken();
$_SESSION['token'] = (string)$token;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/ba42bd5e6b.js" crossorigin="anonymous"></script>
    <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="<?= constant('ROOT_URL'); ?>/public/assets/icono.ico" type="image/x-icon">
    <title>Nuevo evento</title>
    <link rel="stylesheet" href="<?= constant('ROOT_URL'); ?>/public/css/evento-nuevo.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

</head>

<body>
    <!-- cargar navbar -->
    <?php require('../src/components/navbar.component.php') ?>
    <section>
        <div class="contenedor">
            <div class="formulario">
                <form name="formulario" method="post" action="<?= constant('ROOT_URL') ?>/src/procesoNuevo.php">
                    <?php
                    //Si existe un error (se fija en la url)..
                    if (isset($_GET['error']) && $_GET['error'] === 'true') { ?>
                        <div class="error-contenedor">
                            <p>Error al crear el evento, intenta nuevamente</p>
                        </div>
                    <?php } ?>
                    <h2>Crear Nuevo Evento</h2>
                    <div class="input-contenedor"></div>
                    <!-- Input oculto que envía el valor del token a la solicitud POST -->
                    <input type="hidden" name="token" value="<?= $token ?>">

                    <div class="input-contenedor">
                        <h1>Nombre</h1>
                        <input type="text" name="nombre" required id="nombre" placeholder="Nombre del evento">
                    </div>

                    <div class="input-contenedor">
                        <h1>Descripcion</h1>
                        <input type="text" name="descripcion" id="descripcion" placeholder="Descripción del evento">
                    </div>

                    <div class="input-contenedor">
                        <h1>Fecha</h1>
                        <input class="fecha" type="date" name="fecha" required>
                    </div>

                    <div class="input-contenedor">
                        <h1>Hora</h1>
                        <input type="time" name="hora" required>
                        
                    </div>

                    <div>
                        <h1>Visibilidad</h1>
                        <select name="vis" id="vis">
                            <option value="0">Publico</option>
                            <option value="1">Privado</option>
                        </select>
                    </div>

                    <div class="input-contenedor"></div>

                    <div class="input-contenedor">
                        <h1>Contraseña</h1>
                        <input type="text" id="pass" name="pass" placeholder="Contraseña del evento"></input>
                    </div>

                    <h1>Ubicación del Evento</h1>

                    <div class="input-contenedor" id="map-container">
                        
                        <div id="map"></div>
                    </div>

                    
                    <input type="hidden" id="latitud" name="latitud" />
                    <input type="hidden" id="longitud" name="longitud" />

                    <div class="h-captcha" data-sitekey="ca1cb826-038a-462c-afba-8f88601e6002"></div>

                    <div class="button">
                        <input type="submit" value="Crear" />
                    </div>
                </form>
            </div>
        </div>
    </section>
    

    <!-- Coordenadas del marcador -->
    <p id="coordinates"></p>

    <div id="map"></div>

    <script>
        // Coordenadas iniciales
        var latitud = -31.74129446517877;
        var longitud = -60.511320020982474;

        // Inicializar el mapa
        var map = L.map('map').setView([latitud, longitud], 13);

        // Agregar tiles de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Crear un marcador arrastrable
        var marker = L.marker([latitud, longitud], { draggable: true }).addTo(map)
            .bindPopup('Arrastre el pin al lugar del evento')
            .openPopup();

        // Actualizar las coordenadas al mover el marcador
        marker.on('dragend', function (e) {
            var position = marker.getLatLng(); // Obtener nueva posición
            latitud = position.lat;
            longitud = position.lng;
            // Actualizar el popup con las coordenadas actuales
            marker.bindPopup(`Evento: ${document.getElementById('nombre').value || 'Sin nombre'}<br>Descripcion: ${document.getElementById('descripcion').value}`).openPopup();
            updatePinName();
        });

        // Función para actualizar el nombre del pin
        function updatePinName() {
            // Obtener el nuevo nombre del pin desde el input
            var newName = document.getElementById('nombre').value;
            var description = document.getElementById('descripcion').value;

            document.getElementById('latitud').value = latitud;
            document.getElementById('longitud').value = longitud;

            // Validar que no esté vacío
            if (newName.trim() === '') {
                alert('El nombre del pin no puede estar vacío.');
                return;
            }

            // Actualizar el popup del marcador con el nuevo nombre
            marker.bindPopup(`Evento: ${newName}<br>Descripcion: ${document.getElementById('descripcion').value}`).openPopup();
        }

        document.getElementById('nombre').addEventListener('input', function () {
            updatePinName();
        });
        document.getElementById('descripcion').addEventListener('input', function () {
            updatePinName();
        });
        document.getElementById('fecha').addEventListener('input', function () {
            updatePinName();
        });
        document.getElementById('hora').addEventListener('input', function () {
            updatePinName();
        });
    </script>



    <!-- cargar footer -->
    <?php require('../src/components/footer.component.php') ?>
</body>
<?php
//checkea si hay errores
if (isset($_SESSION['nuevoAlert'])) {
    echo "<script>
            Swal.fire({
                title: ' " . $_SESSION['nuevoAlert']['title'] . "',
                text: '" . $_SESSION['nuevoAlert']['text'] . "',
                icon: '" . $_SESSION['nuevoAlert']['icon'] . "',
                confirmButtonText: 'Aceptar'
            });
          </script>";
    $_SESSION['nuevoAlert'] = null;
    session_write_close();
}
?>

</html>