<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

<footer class="pie-pagina">
    <div class="pie">

        <div class="box">
            <h2>Redes sociales</h2>
            <div class="redes-sociales">
                <a
                    href="https://www.instagram.com/ramiroacosta20"
                    target="_blank"
                    rel="noopener"
                    class="bi bi-instagram"></a>
            </div>

            <div class="redes-sociales">
                <a
                    href="https://www.linkedin.com/"
                    target="_blank"
                    rel="noopener"
                    class="bi bi-linkedin"></a>
            </div>
        </div>

    </div>

    <div class="pie2">
        <small>&copy; 2025 Todos los derechos reservados. Ramiro Acosta </small>
    </div>
</footer>

<style>
    .pie-pagina {
        width: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        padding: 15px 0;
        margin-top: auto;
    }

    .pie-pagina .pie {
        width: 100%;
        max-width: 1200px;
        margin: auto;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        padding: 0px;
    }

    .pie-pagina .pie .box figure {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
        padding: 0;
    }

    .pie-pagina .pie .box figure img {
        width: 50px;
        height: auto;
        vertical-align: middle;
    }

    .pie-pagina .pie .box h2,
    h3, .ubicacion ,p {
        margin: 5px, 0;
        text-align: center;
        color: #fff;
        margin-bottom: 5px;
        font-size: 20px;
    }

    .pie-pagina .pie .box h3 {
        color: #2ad308;
    }

    .pie-pagina .pie .redes-sociales {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin: 5px 0;
    }

    .pie-pagina .pie .redes-sociales a {
        display: inline-block;
        text-decoration: none;
        line-height: 45px;
        color: #2ad308;
        margin-right: 10px;
        transition: all 300ms ease;
    }

    .pie-pagina .pie .redes-sociales a:hover {
        color: #1f5315;
    }

    .pie-pagina .pie2 {
        padding: 5px 0;
        text-align: center;
        color: #fff;
    }

    .pie-pagina .pie2 small {
        font-size: 15px;
    }

    @media (max-width: 768px) {
        .pie-pagina .pie {
            width: 100%;
            grid-template-columns: repeat(1, 1fr);
            gap: 10px;
            padding: 35 0px;
        }
    }
</style>