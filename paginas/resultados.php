<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>

<body>
    <header id="header"></header>
    <main id="contenedor-completo">
        <div class="loader" id="loader"></div>
        <div>
            <div id="container-titulo">
                <h1 id="titulo-eventos">Competiciones</h1>
            </div>
            <div id="subtitulo-eventos">
                <p>Aquí podrás ver los resultados de las pruebas ya finalizadas tanto las más recientes como las de hace
                    ya algunos años, podrás filtrar tanto por edad, distancia y el tipo de modalidad para que sea más
                    eficiente la búsqueda y ahorres tiempo</p>
            </div>
            <div>
                <div id="busqueda">
                    <h2>Búsqueda avanzada</h2>
                </div>
            </div>
            <div id="container-filtros">
                <ul>
                    <li>
                        <label for="fecha"></label>
                        <select name="fecha" id="fecha" class="listas-filtros-resultados">
                            <option value="">Selecciona un año</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                            <option value="2022">2022</option>
                            <option value="2021">2021</option>
                            <option value="2020">2020</option>
                            <option value="2019">2019</option>
                            <option value="2018">2018</option>
                        </select>
                    </li>
                </ul>
                <ul>
                    <li>
                        <label for="filtro-modalidad"></label>
                        <select name="modalidad" id="filtro-modalidad" class="listas-filtros-resultados">
                            <option value="Todas las modalidades">Todas las modalidades</option>
                            <option value="Triatlon">Triatlón</option>
                            <option value="Duatlon">Duatlón</option>
                            <option value="Triatlon Cross">Triatlón Cross</option>
                            <option value="Duatlon Cross">Duatlón Cross</option>
                            <option value="Acuatlon">Acuatlón</option>
                            <option value="Aquabike">Aquabike</option>
                        </select>
                    </li>
                </ul>
                <ul>
                    <li>
                        <label for="filtro-distancia"></label>
                        <select name="distancia" id="filtro-distancia" class="listas-filtros-resultados">
                            <option value="Todas las distancias">Todas las distancias</option>
                            <option value="Corta">Corta</option>
                            <option value="Sprint">Sprint</option>
                            <option value="Supersprint">Supersprint</option>
                            <option value="Media distancia">Media distancia</option>
                            <option value="Olimpica">Olímpica</option>
                            <option value="Larga distancia">Larga distancia</option>
                        </select>
                    </li>
                </ul>
                <ul>
                    <li>
                        <label for="filtro-categoria"></label>
                        <select name="categoria" id="filtro-categoria" class="listas-filtros">
                            <option value="Todas las edades">Todas las edades</option>
                            <option value="Menores">Menores</option>
                            <option value="Adultos">Adultos</option>
                        </select>
                    </li>
                </ul>
                <button class="botones-filtros" id="boton-buscar">Buscar</button>
                <button class="botones-filtros" id="boton-reiniciar">Reiniciar filtros</button>
            </div>
        </div>

        <div id="Todo-completos">
            <div id="container-listado-competiciones">
                <?php include '../assets/php/mostrar-resultados.php'; ?>
            </div>
        </div>

        <footer>
            <div id="footer"></div>
        </footer>
    </main>

    <script>
    window.addEventListener('DOMContentLoaded', (event) => {
        const headerContainer = document.getElementById('header');
        const footerContainer = document.getElementById('footer');

        // Obtener la ruta relativa al directorio actual
        const currentPagePath = window.location.pathname;
        const currentPageDirectory = currentPagePath.substring(0, currentPagePath.lastIndexOf('/'));

        // Cargar el encabezado
        fetch(currentPageDirectory + '/header.html')
            .then(response => response.text())
            .then(data => {
                headerContainer.innerHTML = data;
            });

        // Cargar el pie de página
        fetch(currentPageDirectory + '/footer.html')
            .then(response => response.text())
            .then(data => {
                footerContainer.innerHTML = data;
            });

        // Mostrar loader al cargar la página
        const loader = document.getElementById("loader");
        loader.classList.add("hidden");
    });

    /*MENU DESPLEGABLE */
    document.addEventListener('DOMContentLoaded', function() {
        const hamburgerMenu = document.getElementById('hamburger-menu');
        const listaMenu = document.getElementById('lista-menu');

        hamburgerMenu.addEventListener('click', function() {
            listaMenu.classList.toggle('active');
        });
    });

    function toggleMenu() {
        const navMenu = document.getElementById('lista-menu');
        navMenu.classList.toggle('active');
    }

    /*FILTRO COMPETI*/
    document.getElementById('boton-buscar').addEventListener('click', function() {
        buscarCompeticiones();
    });

    // Función para realizar la búsqueda de competiciones
    function buscarCompeticiones() {
        var modalidad = document.getElementById('filtro-modalidad').value;
        var distancia = document.getElementById('filtro-distancia').value;
        var categoria = document.getElementById('filtro-categoria').value;
        var fecha = document.getElementById('fecha').value;

        // Enviar los valores de los filtros incluso si la categoría no está seleccionada
        var formData = new FormData();
        formData.append('modalidad', modalidad);
        formData.append('distancia', distancia);
        formData.append('categoria', categoria);
        formData.append('fecha', fecha);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../assets/php/filtro-competicion.php', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById('container-listado-competiciones').innerHTML = xhr.responseText;
            }
        };
        xhr.send(formData);
    }

    // Event listener para el botón de reiniciar filtros
    document.getElementById('boton-reiniciar').addEventListener('click', function() {
        // Restablecer los valores de los selectores de filtro a sus valores por defecto
        document.getElementById('filtro-modalidad').value = 'Todas las modalidades';
        document.getElementById('filtro-distancia').value = 'Todas las distancias';
        document.getElementById('filtro-categoria').value = 'Todas las edades';
        document.getElementById('fecha').value = '';

        // Realizar la búsqueda nuevamente con los filtros reiniciados
        buscarCompeticiones();
    });
    </script>
</body>

</html>