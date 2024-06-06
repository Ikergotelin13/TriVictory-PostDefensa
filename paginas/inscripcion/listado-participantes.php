<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado-participantes</title>
    <link rel="stylesheet" href="../../assets/css/estilo.css">
</head>

<body>
    <header id="header"></header>
    <main id="laterales-inscripcion">
        <div id="opa-hijo">
            <div id>
                <div id="container-zona-alta-inscripciones">
                    <div id="zona-alta-inscripciones">
                        <div id="container-titulo-prueba-inscripcion">
                            <div id="titulo-prueba-inscripcion">
                                <h2 id="nombre-prueba-inscripcion">XVII HOTIZON TRIATLON SEVILLLA (DISTANCIA OLIMPICA)
                                </h2>
                            </div>
                        </div>
                        <div id="lista-superior">
                            <ul id="lista-completa">
                                <a href="../../paginas/eventos.php">
                                    <li class="numero lista-apartados-inscripcion">Inicio</li>
                                </a>
                                <li class="numero lista-apartados-inscripcion">Inscripciones
                                    <ul id="lista-desplegable-inscripcion">
                                        <a href="../../paginas/inscripcion/listado-participantes.php">
                                            <li class="enlaces-inscripciones lista-apartados-inscripcion">Listado de participantes</li>
                                        </a>
                                        <a href="">
                                            <a href="../../paginas/inscripcion/formulario-inscripcion.html">
                                                <li class="enlaces-inscripciones lista-apartados-inscripcion">Inscríbete</li>
                                            </a>
                                        </a>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div id="container-lista-participantes-completa">
                    <div id="lista-participantes-completa">
                        <div id="titulo-lista-participantes">
                            <h3>Listado de participantes</h3>
                        </div>
                        <div id="container-filtros-listado-participantes">
                            <div id="filtros-listado-participantes">
                                <!-- Buscador por palabras -->
                                <section id="buscador-palabras">
                                    <input type="text" id="buscador" placeholder="Buscar...">
                                    <button id="buscar">Buscar</button>
                                </section>
                                <!-- Filtros distintos -->
                                <section id="filtros-participantes">
                                    <label for="filtro-genero"></label>
                                    <select id="filtro-genero" class="filtros">
                                        <option value="todos">Todos</option>
                                        <option value="Mujer">Mujer</option>
                                        <option value="Hombre">Hombre</option>
                                    </select>
                                    <label for="filtro-edad"></label>
                                    <select id="filtro-edad" class="filtros">
                                        <option value="Todos">Agrupar por</option>
                                        <option value="Categoria">Categoría</option>
                                    </select>
                                </section>

                                <section>
                                    <!-- Texto que muestra cuántos registros hay -->
                                    <p id="num-registros">Registros encontrados: XX</p>
                                </section>
                            </div>
                        </div>
                        <!-- Ocultamos la tabla de datos de los participantes por defecto -->
                        <div id="container-tabla-lista-participantes" style="display: none;">
                            <!-- Aquí se insertará la tabla de participantes generada por PHP -->
                            <?php
    include('../../assets/php/procesar-formulario.php');
    ?>
                        </div>
                        <nav id="navegacion-paginas-listado-participantes">

                        </nav>

                        <!-- Agregamos la tabla para mostrar el conteo de participantes por categoría -->
                        <div id="conteo-categorias-container">

                            <table id="conteo-categorias-tabla">
                                <!-- Aquí se mostrará el conteo de participantes por categoría -->
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <div id="footer"></div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const paginationLinks = document.querySelectorAll('.pagination a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault(); // Evita el comportamiento predeterminado del enlace
                const pageNumber = parseInt(this
                .textContent); // Obtiene el número de página del texto del enlace
                loadPage(pageNumber);
            });
        });
    });

    window.addEventListener('DOMContentLoaded', (event) => {
        const headerContainer = document.getElementById('header');
        const footerContainer = document.getElementById('footer');
        const tablaContainer = document.getElementById('container-tabla-lista-participantes');

        // Obtener la ruta relativa al directorio actual
        const currentPagePath = window.location.pathname;
        const currentPageDirectory = currentPagePath.substring(0, currentPagePath.lastIndexOf('/'));

        // Cargar el encabezado
        fetch(currentPageDirectory + '/header-inscripciones.html')
            .then(response => response.text())
            .then(data => {
                headerContainer.innerHTML = data;
            });

        // Cargar el pie de página
        fetch(currentPageDirectory + '/../footer.html')
            .then(response => response.text())
            .then(data => {
                footerContainer.innerHTML = data;
            });

        // Cargar la tabla de participantes generada por PHP con la página actual
        fetch('../../assets/php/procesar-formulario.php?pagina=1')
            .then(response => response.text())
            .then(data => {
                tablaContainer.innerHTML = data;
                // Mostrar la tabla después de cargar los datos
                tablaContainer.style.display = '';
                // Obtener el número total de registros y actualizar el paginador
                fetch('../../assets/php/contar-registros.php')
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('num-registros').textContent =
                            "Registros encontrados: " + data;
                        updatePager(parseInt(
                        data)); // Actualiza el paginador con el número total de registros
                    });
            });
    });

    function updatePager(totalRecords) {
        var pagerHTML = '';
        // Construye la paginación
        if (totalRecords > 0) {
            var maxPerPage = 5; // Número máximo de registros por página
            var totalPages = Math.ceil(totalRecords / maxPerPage);

            for (var i = 1; i <= totalPages; i++) {
                pagerHTML += '<li><a href="#" onclick="loadPage(' + i + ')">' + i + '</a></li>';
            }
        }
        document.getElementById('pager').innerHTML = pagerHTML;
    }

    // Función para cargar una página específica
    function loadPage(pageNumber) {
        fetch('../../assets/php/procesar-formulario.php?pagina=' + pageNumber)
            .then(response => response.text())
            .then(data => {
                document.getElementById('container-tabla-lista-participantes').innerHTML = data;
                document.getElementById('container-tabla-lista-participantes').style.display = '';
            });
    }

    // Función para buscar registros
    document.addEventListener('DOMContentLoaded', function() {
        const btnBuscar = document.getElementById('buscar');
        const inputBuscador = document.getElementById('buscador');
        const tablaContainer = document.getElementById('container-tabla-lista-participantes');

        btnBuscar.addEventListener('click', function() {
            const valorBusqueda = inputBuscador.value.trim();

            fetch('../../assets/php/buscar-registros.php?palabra=' + encodeURIComponent(valorBusqueda))
                .then(response => response.text())
                .then(data => {
                    tablaContainer.innerHTML = data;
                    updatePagination(parseInt(
                    data)); // Actualiza la paginación con el número total de registros
                    tablaContainer.style.display = '';
                })
                .catch(error => console.error('Error al realizar la búsqueda:', error));
        });

        const filtroGenero = document.getElementById('filtro-genero');
        filtroGenero.addEventListener('change', filterData);

        const filtroCategoria = document.getElementById('filtro-edad'); // Selección del filtro de categoría
        filtroCategoria.addEventListener('change', filterDataByCategory);
    });

    function filterData() {
        const selectedGender = document.getElementById('filtro-genero').value;
        const rows = document.getElementById('container-tabla-lista-participantes').getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName('td');
            const genderCell = cells[4];
            if (!genderCell) continue; // Si no hay celda de género, pasamos a la siguiente fila

            const gender = genderCell.textContent.trim();

            if (selectedGender === 'todos' || gender === selectedGender) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }


    /*FILTRAR POR CATEGORIA*/

    function filterDataByCategory() {
        const selectedCategory = document.getElementById('filtro-edad').value;
        const rows = document.getElementById('container-tabla-lista-participantes').getElementsByTagName('tr');
        const conteoCategoriasTabla = document.getElementById('conteo-categorias-tabla');

        // Objeto para almacenar el conteo de participantes por categoría
        const conteo = {};

        // Conteo inicial
        for (let i = 1; i < rows.length; i++) {
            const categoryCell = rows[i].getElementsByTagName('td')[
            2]; // Suponemos que la columna de categoría es la tercera
            if (!categoryCell) continue; // Si no hay celda de categoría, pasamos a la siguiente fila
            const category = categoryCell.textContent.trim();
            conteo[category] = (conteo[category] || 0) + 1;
        }

        // Actualizar la tabla de conteo de participantes por categoría
        conteoCategoriasTabla.innerHTML = `
        <tr>
            <th>Categoría</th>
            <th>Cantidad</th>
        </tr>
    `;
        for (const category in conteo) {
            conteoCategoriasTabla.innerHTML += `
            <tr>
                <td>${category}</td>
                <td>${conteo[category]}</td>
            </tr>
        `;
        }

        // Mostrar u ocultar la tabla de datos de los participantes según si se realiza una búsqueda por categoría o no
        const tablaParticipantes = document.getElementById('container-tabla-lista-participantes');
        const pager = document.getElementById('navegacion-paginas-listado-participantes');

        if (selectedCategory === 'Categoria') {
            tablaParticipantes.style.display = 'none';
            pager.style.display = 'none';
        } else {
            tablaParticipantes.style.display = '';
            pager.style.display = '';
        }

        // Filtrar las filas según la categoría seleccionada
        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const categoryCell = row.getElementsByTagName('td')[2]; // La columna de categoría es la tercera
            if (!categoryCell) continue; // Si no hay celda de categoría, pasamos a la siguiente fila
            const category = categoryCell.textContent.trim();

            if (selectedCategory === 'Todos' || category === selectedCategory) {
                row.style.display = ''; // Mostrar la fila si coincide con la categoría seleccionada
            } else {
                row.style.display = 'none'; // Ocultar la fila si no coincide con la categoría seleccionada
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Función para cargar una página específica
        function loadPage(pageNumber) {
            fetch(`../../assets/php/procesar-formulario.php?pagina=${pageNumber}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('container-tabla-lista-participantes').innerHTML = data;
                    document.getElementById('container-tabla-lista-participantes').style.display = '';
                });
        }

        loadPage(1);

        // Manejar la búsqueda y los filtros
        document.getElementById('buscar').addEventListener('click', function() {
            const valorBusqueda = document.getElementById('buscador').value.trim();
            fetch(`../../assets/php/buscar-registros.php?palabra=${encodeURIComponent(valorBusqueda)}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('container-tabla-lista-participantes').innerHTML = data;
                    document.getElementById('container-tabla-lista-participantes').style.display =
                        '';
                });
        });

        document.getElementById('filtro-genero').addEventListener('change', function() {
            // Lógica para filtrar por género
        });

        document.getElementById('filtro-edad').addEventListener('change', function() {
            // Lógica para filtrar por categoría o club
        });
    });
    </script>
    <script src="../../assets/js/script.js"></script>
</body>

</html>