// Clean, fixed inicio.js: manejo de asistencia y actualizaciones en dashboard
document.addEventListener('DOMContentLoaded', function() {
  const formAsistencia = document.getElementById('formAsistencia');
  const rfidInput = document.getElementById('rfid_input');
  const mensajeAsistencia = document.getElementById('mensajeAsistencia');
  const contenidoMensaje = document.getElementById('contenidoMensaje');

  // Elementos para actualizar tiempo
  const tiempoElements = [
    document.getElementById('tiempoActualizacion'),
    document.getElementById('tiempoActualizacion2'),
    document.getElementById('tiempoActualizacion3'),
    document.getElementById('tiempoActualizacion4')
  ];
  let tiempoInicio = new Date();

  function mostrarMensaje(mensaje, tipo = 'info') {
    if (!contenidoMensaje || !mensajeAsistencia) return;
    contenidoMensaje.innerHTML = mensaje;
    mensajeAsistencia.className = `mensaje-asistencia ${tipo}`;
    mensajeAsistencia.style.display = 'block';
    setTimeout(() => { mensajeAsistencia.style.display = 'none'; }, 5000);
  }

  function actualizarTiempo() {
    const ahora = new Date();
    const minutosTranscurridos = Math.floor((ahora - tiempoInicio) / 60000);
    tiempoElements.forEach(el => { if (el) el.textContent = minutosTranscurridos; });
  }

  function actualizarEstadisticas() {
    fetch(`${window.CoreFitBaseUrl}controladores/controladorInicio.php?ajax=1&_=${Date.now()}`, { method: 'GET', headers: { 'Content-Type': 'application/json' } })
    .then(r => r.json())
    .then(data => {
      if (data && data.success) {
        const cards = document.querySelectorAll('.card-value');
        if (cards[0]) cards[0].textContent = data.estadisticas.total_miembros;
        if (cards[1]) cards[1].textContent = data.estadisticas.asistencias_hoy;
        if (cards[2]) cards[2].textContent = data.estadisticas.miembros_activos;
        if (cards[3]) cards[3].textContent = data.estadisticas.miembros_morosos;
        tiempoInicio = new Date();
        actualizarTiempo();
      }
    })
    .catch(err => console.error('Error al actualizar estadísticas:', err));
  }

  if (formAsistencia) {
    formAsistencia.addEventListener('submit', function(e) {
      e.preventDefault();
      const rfid = (rfidInput && rfidInput.value) ? rfidInput.value.trim() : '';
      if (!rfid) { mostrarMensaje('Por favor ingrese un ID válido', 'error'); return; }

      mostrarMensaje('Procesando asistencia...', 'loading');

      fetch(`${window.CoreFitBaseUrl}controladores/controladorInicio.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `rfid_input=${encodeURIComponent(rfid)}`
      })
      .then(r => r.json())
      .then(data => {
        let tipo = 'info';
        let mensaje = '';
        if (data && data.success) {
          tipo = 'success';
          mensaje = data.message || 'Asistencia procesada';
          if (data.type === 'success') mensaje += '<br><small>Asistencia registrada correctamente</small>';
          else if (data.type === 'already_registered') tipo = 'warning';
        } else {
          tipo = 'error';
          if (data && data.type === 'not_found') mensaje = 'Miembro no encontrado. Verifique el ID de la tarjeta.';
          else if (data && data.type === 'expired') { mensaje = 'Su membresía ha expirado. Por favor renueve su membresía.'; tipo = 'warning'; }
          else mensaje = (data && data.message) ? data.message : 'Error en la solicitud';
        }
        mostrarMensaje(mensaje, tipo);
        if (rfidInput) { rfidInput.value = ''; rfidInput.focus(); }
        // If backend returned updated statistics, use them. Otherwise, optimistically increment.
        try {
          const cards = document.querySelectorAll('.card-value');
          if (data && data.estadisticas && typeof data.estadisticas.asistencias_hoy !== 'undefined') {
            if (cards[1]) cards[1].textContent = data.estadisticas.asistencias_hoy;
          } else if (data && data.success && data.type === 'success') {
            if (cards[1]) {
              const cur = parseInt(cards[1].textContent || '0', 10);
              cards[1].textContent = isNaN(cur) ? '1' : String(cur + 1);
            }
          }
        } catch (e) { /* ignore JS update errors */ }
        // Refresh full stats shortly after to sync with server
        setTimeout(actualizarEstadisticas, 800);
      })
      .catch(err => { console.error('Error:', err); mostrarMensaje('Error al procesar la solicitud', 'error'); });
    });
  }

  if (rfidInput) {
    rfidInput.addEventListener('keypress', function(e) { if (e.key === 'Enter') { e.preventDefault(); if (formAsistencia) formAsistencia.dispatchEvent(new Event('submit')); } });
  }

  setInterval(actualizarTiempo, 60000);
  setInterval(actualizarEstadisticas, 300000);
  if (rfidInput) rfidInput.focus();
});

// Estilos CSS para los mensajes
const style = document.createElement('style');
style.textContent = `
  .mensaje-asistencia {
    margin-top: 15px;
    padding: 15px;
    border-radius: 5px;
    font-weight: bold;
    text-align: center;
  }
  .mensaje-asistencia.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
  .mensaje-asistencia.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
  .mensaje-asistencia.warning { background-color: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
  .mensaje-asistencia.loading { background-color: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
`;
document.head.appendChild(style);
