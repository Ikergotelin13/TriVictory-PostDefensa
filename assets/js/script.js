


/*FORMULARIO DE INSCRIPCIONES*/

document.addEventListener('DOMContentLoaded', function() {
  const formulario = document.getElementById('miFormulario');
  formulario.addEventListener('submit', function(event) {
      event.preventDefault();

      const nombre = document.getElementById('nombre').value;
      const apellidos = document.getElementById('apellidos').value;
      const categoria = document.getElementById('categoria').value;
      const club = document.getElementById('club').value;

      const formData = new FormData();
      formData.append('nombre', nombre);
      formData.append('apellidos', apellidos);
      formData.append('categoria', categoria);
      formData.append('club', club);

      fetch('procesar-formulario.php', {
          method: 'POST',
          body: formData
      })
      .then(response => response.text())
      .then(data => {
          console.log(data);
          // Aquí puedes manejar la respuesta del servidor, por ejemplo, actualizar la tabla
          actualizarTabla();
      })
      .catch(error => {
          console.error('Error al enviar los datos:', error);
      });
  });
});

function actualizarTabla() {
 
}







