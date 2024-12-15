<?php
require('./src/config.php');
require('./src/procesoAltaUsuarioEvento.php');
require('./src/procesoBajaUsuarioEvento.php');
require('./src/utils/protectRoute.php');
session_start();
protectRoute('/login.php');

$conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT ?? null);

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>

<script>
    //Este codigo de JavaScript llama a procesoAltaUsuarioEvento.php  el cual maneja el proceso de inscripcion a un evento
    function inscribirseEvento(idUsuario, idEvento) {
        fetch('src/procesoAltaUsuarioEvento.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'id_usuario': idUsuario,
                    'id_evento': idEvento
                })
            })
            .then(response => response.text()) // Cambiar a .text() temporalmente para ver la salida
            .then(data => {
                console.log('Respuesta del servidor:', data); // Imprimir la respuesta para depurar
                try {
                    const jsonData = JSON.parse(data); // Analizar JSON si la respuesta es válida
                } catch (error) {
                    console.error('Error al analizar JSON:', error);
                    alert('Error en la respuesta del servidor.');
                } finally {
                    window.location.reload();
                }
            })
            .catch(error => {
                alert("Ocurrió un error: " + error);
                window.location.reload();
            });
        document.querySelector(".darsebaja").addEventListener('click', () => {
            this.disabled = true
        })
    }
</script>

<script>
    //Este codigo de JavaScript llama a procesoBajaUsuarioEvento.php el cual maneja el proceso de darse de baja de un evento
    function eliminarInscripcion(idUsuario, idEvento) {
        fetch('src/procesoBajaUsuarioEvento.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'id_usuario': idUsuario,
                    'id_evento': idEvento
                })
            })
            .then(response => response.text()) // Cambiar a .text() temporalmente para ver la salida
            .then(data => {
                console.log('Respuesta del servidor:', data); // Imprimir la respuesta para depurar
                try {
                    const jsonData = JSON.parse(data); // Analizar JSON si la respuesta es válida
                } catch (error) {
                    alert('Error en la respuesta del servidor.');
                } finally {
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error de red:', error);
                // alert("Ocurrió un error: " + error);
                //window.location.reload();
            });
    }
</script>

<?php
// Obtener eventos
$sql = "SELECT id, nombre, fecha, horario, id_creador, inscriptos FROM eventos WHERE (eventos.fecha) >= CURDATE() ORDER BY fecha, horario";
$resultado = $conexion->query($sql);

// Obtener eventos a los que el usuario está inscrito
$id_usuario = $_SESSION['id_usuario'];
$stmt = $conexion->prepare("SELECT id_evento FROM usuarios_eventos WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$eventosInscritos = [];
while ($row = $result->fetch_assoc()) {
    $eventosInscritos[] = $row['id_evento'];
}

// Guardar los datos de los eventos en un arreglo para pasarlos a JavaScript
$eventos = [];
while ($row = $resultado->fetch_assoc()) {
    $id_creador = htmlspecialchars($row['id_creador']);
    $nombreEvento = htmlspecialchars($row['nombre']);
    $fechaEvento = htmlspecialchars($row['fecha']);
    $horarioEvento = htmlspecialchars($row['horario']);
    $id_evento = htmlspecialchars($row['id']);
    $cantidad_inscriptos = htmlspecialchars($row['inscriptos']);

    // Obtener el nombre del creador
    $stmt = $conexion->prepare("SELECT nombre_usuario FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id_creador);
    $stmt->execute();
    $ress = $stmt->get_result();
    if ($fila = $ress->fetch_assoc()) {
        $nombre_usuario = $fila['nombre_usuario'];
    }

    // Crear un arreglo para cada evento
    $eventos[] = [
        'nombre' => $nombreEvento,
        'creador' => $nombre_usuario,
        'fecha' => $fechaEvento,
        'horario' => $horarioEvento,
        'inscriptos' => $cantidad_inscriptos,
        'id_evento' => $id_evento,
        'inscrito' => in_array($id_evento, $eventosInscritos) ? true : false
    ];
}

$eventosJson = json_encode($eventos);
?>

<script>
// Pasar los eventos desde PHP a JavaScript
let eventos = <?php echo $eventosJson; ?>;

// Función para reordenar eventos según la columna seleccionada
function ordenarEventos(columna) {
    const eventosOrdenados = [...eventos]; // Copiar el arreglo original para no modificarlo directamente

    // Ordenar según la columna seleccionada
    eventosOrdenados.sort((a, b) => {
        if (a[columna] < b[columna]) return -1;
        if (a[columna] > b[columna]) return 1;
        return 0;
    });

    // Actualizar la tabla con los eventos ordenados
    actualizarTabla(eventosOrdenados);
}

// Función para actualizar la tabla con los eventos ordenados
function actualizarTabla(eventosOrdenados) {
    const tbody = document.querySelector('tbody');
    tbody.innerHTML = ''; // Limpiar la tabla

    var idUsuario = <?php echo json_encode($id_usuario); ?>;
    eventosOrdenados.forEach(evento => {
        // Crear una nueva fila por cada evento
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${evento.nombre}</td>
            <td>${evento.creador}</td>
            <td>${evento.fecha}</td>
            <td>${evento.horario}</td>
            <td>${evento.inscriptos}</td>
            <td>
                ${evento.inscrito ? 
                    `<button class="darsebaja" onclick="eliminarInscripcion('${idUsuario}','${evento.id_evento}')">Darse de baja</button>` :
                    `<button onclick="inscribirseEvento('${idUsuario}','${evento.id_evento}')">Inscribirse</button>`
                }
            </td>
        `;
        tbody.appendChild(tr);
    });
}

window.onload = function() {
    ordenarEventos('fecha');
};
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/ba42bd5e6b.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="<?= constant('ROOT_URL'); ?>/public/assets/icono.ico" type="image/x-icon">
    <title>Servicio eventos</title>
    <link rel="stylesheet" href="<?= constant('ROOT_URL'); ?>/public/css/home.css">
</head>
<body>
    <?php require('./src/components/navbar.component.php') ?>

    <main class="contenedor">
        <h1>Bienvenido!</h1>

        <h3>Listado de Eventos</h3>
        <table>
            <thead class="eventos">
                <tr>
                    <th><button class="btn" onclick="ordenarEventos('nombre')">Nombre del Evento</button></th>
                    <th><button class="btn" onclick="ordenarEventos('creador')">Creador</button></th>
                    <th><button class="btn" onclick="ordenarEventos('fecha')">Fecha</button></th>
                    <th><button class="btn" onclick="ordenarEventos('horario')">Horario</button></th>
                    <th><button class="btn" onclick="ordenarEventos('inscriptos')">Inscriptos</button></th>
                    <th>Inscripción</th>
                </tr>
            </thead>
            <tbody>
                <!-- Los eventos se insertan dinámicamente con JavaScript -->
            </tbody>
        </table>
    </main>

    <?php require('./src/components/footer.component.php') ?>
</body>
</html>
