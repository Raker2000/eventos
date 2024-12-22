<header>
    <div class="logo-header">
        <img src="<?= constant('ROOT_URL') ?>/public/assets/escudo.png" alt="logo del club" />
        <h2 class="nombre-club-header">Sistema de eventos</h2>
    </div>

    <nav>
        <?php if (!isset($_SESSION['userLogged'])) { ?>
            <a href="<?= constant('ROOT_URL') ?>/login.php" class="mav-link">Ingresar</a>
            <a href="<?= constant('ROOT_URL') ?>/register.php" class="mav-link">Registrarse</a>
        <?php } ?>

        <?php
        if (isset($_SESSION['userLogged']) && isset($_SESSION['username'])) { ?>
            <div class="user-info">
                <a href="<?= constant('ROOT_URL') ?>/">Inicio</a>
                <a href="<?= constant('ROOT_URL') ?>/map.php">Mapa</a>
                <a href="<?= constant('ROOT_URL') ?>/evento/creados.php">Mis Eventos</a>
                <a href="<?= constant('ROOT_URL') ?>/user.php"> <?= $_SESSION['username']; ?> <i class="fa fa-user-circle-o" aria-hidden="true"></i> </a>
            </div>
        <?php } ?>

    </nav>
</header>

<style>
    header {
        display: flex;
        background-color: rgba(0, 0, 0, 0.8);
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        font-family: "Space Mono", monospace;
        text-transform: uppercase;
    }

    .logo-header {
        display: flex;
        align-items: center;
    }

    .logo-header img {
        height: 50px;
        margin-right: 10px;
    }

    .logo-header h2 {
        color: #2ad308;
        padding: 5px;
    }

    .mav-link,
    .cerrar-sesion {
        text-decoration: none;
        color: #2ad308;
    }

    .mav-link {
        margin-right: 20px;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #2ad308;
    }

    .user-info a.cerrar-sesion {
        text-decoration: none;
        font-weight: 600;
        color: #2ad308;
        text-transform: uppercase;
    }

    nav a,
    .user-info {
        color: #2ad308;
        margin-right: 10px;
        font-family: "Space Mono", monospace;
        font-weight: 600;
    }

    nav a:hover,
    .cerrar-sesion:hover {
        color: greenyellow;
    }



    nav a:hover {
        color: greenyellow;
    }

    @media (max-width: 768px) {
        header {
            flex-direction: column;
            height: fit-content;
        }

        nav {
            padding: 10px 0px;
        }
    }
</style>