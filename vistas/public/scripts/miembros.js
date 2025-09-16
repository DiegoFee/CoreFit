// MOSTRAR FORMULARIO DE REGISTRO Y OCULTAR LISTA
document.querySelector('.register-btn').addEventListener('click', function() {
  document.getElementById('miembrosLista').style.display = 'none';
  document.getElementById('registroMiembro').style.display = 'block';
});

// VOLVER A LA LISTA DE MIEMBROS
document.querySelector('#registroMiembro .back-btn').addEventListener('click', function() {
  document.getElementById('registroMiembro').style.display = 'none';
  document.getElementById('miembrosLista').style.display = 'block';
});