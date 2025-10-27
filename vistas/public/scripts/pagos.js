document.addEventListener('DOMContentLoaded', function() {
  const modalPagar = document.getElementById('modalPagar');
  const modalDetalles = document.getElementById('modalDetalles');
  const modalFoto = document.getElementById('modalFoto');
  const pagarBtns = document.querySelectorAll('.pagar-btn');
  const detallesBtns = document.querySelectorAll('.detalles-btn');
  const verFotoBtn = document.getElementById('verFotoBtn');
  const imprimirRegistroBtn = document.getElementById('imprimirRegistroBtn');
  const imprimirHistoriaBtn = document.getElementById('imprimirHistoriaBtn');
  const closeBtns = document.querySelectorAll('.modal-close');

  // Variables para los datos del miembro actual
  let miembroActual = null;

  // Función para mostrar modal
  function mostrarModal(modal) {
    modal.setAttribute('aria-hidden', 'false');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }

  // Función para ocultar modal
  function ocultarModal(modal) {
    modal.setAttribute('aria-hidden', 'true');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
  }

  // Event listeners para botones de cerrar
  closeBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      const modal = this.closest('.modal-window');
      ocultarModal(modal);
    });
  });

  // Event delegation for pagar and detalles buttons (keeps handlers after DOM changes)
  const tablaPagos = document.getElementById('tablaPagos');
  let _asistenciaChart = null; // keep chart reference so we can destroy it when redrawn

  function crearGraficoAsistenciaConDatos(datos) {
    const ctx = document.getElementById('chartPagos');
    if (!ctx) return;

    // Destroy previous chart if exists
    if (_asistenciaChart && typeof _asistenciaChart.destroy === 'function') {
      _asistenciaChart.destroy();
      _asistenciaChart = null;
    }

    const chartData = {
      labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
      datasets: [{
        label: 'Asistencias por día de la semana',
        data: datos,
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }]
    };

    _asistenciaChart = new Chart(ctx, {
      type: 'bar',
      data: chartData,
      options: {
        responsive: true,
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  }

  async function crearGraficoAsistenciaForMember(miembroId) {
    // Try to fetch real data from server. If endpoint isn't available or fails, fall back to simulated data.
    const fallback = [0,0,0,0,0,0,0];
    try {
      const url = `${window.CoreFitBaseUrl}controladores/controladorPagos.php?accion=asistencias_por_semana&id=${encodeURIComponent(miembroId)}`;
      const resp = await fetch(url, { cache: 'no-store' });
      if (!resp.ok) {
        crearGraficoAsistenciaConDatos(fallback);
        return;
      }
      const json = await resp.json();
      // Expecting { data: [lun, mar, mie, jue, vie, sab, dom] }
      if (json && Array.isArray(json.data) && json.data.length === 7) {
        crearGraficoAsistenciaConDatos(json.data.map(v => Number(v) || 0));
      } else {
        // try to use json.asistencias if present
        if (json && Array.isArray(json.asistencias) && json.asistencias.length === 7) {
          crearGraficoAsistenciaConDatos(json.asistencias.map(v => Number(v) || 0));
        } else {
          // fallback simulated data
          crearGraficoAsistenciaConDatos([5,3,7,4,6,2,1]);
        }
      }
    } catch (err) {
      crearGraficoAsistenciaConDatos([5,3,7,4,6,2,1]);
    }
  }

  if (tablaPagos) {
    tablaPagos.addEventListener('click', function(e) {
      const pagarBtn = e.target.closest('.pagar-btn');
      const detallesBtnClicked = e.target.closest('.detalles-btn');

      if (pagarBtn) {
        if (pagarBtn.disabled) return;
        const row = pagarBtn.closest('tr');
        if (!row) return;

        miembroActual = {
          id: row.dataset.id,
          nombre: row.dataset.nombre,
          apellido: row.dataset.apellido,
          telefono: row.dataset.telefono,
          membresia: row.dataset.membresia,
          precio: parseFloat(row.dataset.precio),
          desde: row.dataset.desde,
          hasta: row.dataset.hasta,
          actividad: row.dataset.actividad,
          estado: row.dataset.estado,
          pago: parseFloat(row.dataset.pago),
          debe: parseFloat(row.dataset.debe),
          foto: row.dataset.foto
        };

        // Llenar datos del miembro
        const pagarNombreEl = document.getElementById('pagarNombre');
        if (pagarNombreEl) pagarNombreEl.textContent = miembroActual.nombre + ' ' + miembroActual.apellido;
        const miembroDatosPagoEl = document.getElementById('miembroDatosPago');
        if (miembroDatosPagoEl) miembroDatosPagoEl.innerHTML = `
          <p><strong>Nombre:</strong> ${miembroActual.nombre} ${miembroActual.apellido}</p>
          <p><strong>Teléfono:</strong> ${miembroActual.telefono}</p>
          <p><strong>Membresía:</strong> ${miembroActual.membresia}</p>
          <p><strong>Período:</strong> ${miembroActual.desde} - ${miembroActual.hasta}</p>
          <p><strong>Estado:</strong> ${miembroActual.estado}</p>
        `;

        // Llenar datos del pago
        const miembroIdPagoEl = document.getElementById('miembroIdPago');
        if (miembroIdPagoEl) miembroIdPagoEl.value = miembroActual.id;
        const pagarMembresiaEl = document.getElementById('pagarMembresia');
        if (pagarMembresiaEl) pagarMembresiaEl.value = miembroActual.membresia;
        const pagarPrecioEl = document.getElementById('pagarPrecio');
        if (pagarPrecioEl) pagarPrecioEl.value = miembroActual.precio;
        const pagarDebeEl = document.getElementById('pagarDebe');
        if (pagarDebeEl) pagarDebeEl.value = miembroActual.debe;
        const pagarCantidadEl = document.getElementById('pagarCantidad');
        if (pagarCantidadEl) pagarCantidadEl.max = miembroActual.debe;

        mostrarModal(modalPagar);
        return;
      }

      if (detallesBtnClicked) {
        const row = detallesBtnClicked.closest('tr');
        if (!row) return;

        miembroActual = {
          id: row.dataset.id,
          nombre: row.dataset.nombre,
          apellido: row.dataset.apellido,
          telefono: row.dataset.telefono,
          membresia: row.dataset.membresia,
          precio: parseFloat(row.dataset.precio),
          desde: row.dataset.desde,
          hasta: row.dataset.hasta,
          actividad: row.dataset.actividad,
          estado: row.dataset.estado,
          pago: parseFloat(row.dataset.pago),
          debe: parseFloat(row.dataset.debe),
          foto: row.dataset.foto
        };

        // Llenar datos del miembro
        const detallesNombreEl = document.getElementById('detallesNombre');
        if (detallesNombreEl) detallesNombreEl.textContent = miembroActual.nombre + ' ' + miembroActual.apellido;
        const miembroDatosDetallesEl = document.getElementById('miembroDatosDetalles');
        if (miembroDatosDetallesEl) miembroDatosDetallesEl.innerHTML = `
          <p><strong>Nombre:</strong> ${miembroActual.nombre} ${miembroActual.apellido}</p>
          <p><strong>Teléfono:</strong> ${miembroActual.telefono}</p>
          <p><strong>Membresía:</strong> ${miembroActual.membresia}</p>
          <p><strong>Período:</strong> ${miembroActual.desde} - ${miembroActual.hasta}</p>
          <p><strong>Estado:</strong> ${miembroActual.estado}</p>
          <p><strong>Pagado:</strong> Q${miembroActual.pago}</p>
          <p><strong>Debe:</strong> Q${miembroActual.debe}</p>
        `;

        // Crear gráfico de asistencia real o simulado
        crearGraficoAsistenciaForMember(miembroActual.id);

        mostrarModal(modalDetalles);
        return;
      }
    });
  }


  // Función para crear gráfico de asistencia
  function crearGraficoAsistencia() {
    const ctx = document.getElementById('chartPagos');
    if (ctx) {
      // Datos simulados de asistencia (en un sistema real vendrían de la base de datos)
      const datosAsistencia = {
        labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
        datasets: [{
          label: 'Asistencias por día de la semana',
          data: [5, 3, 7, 4, 6, 2, 1], // Datos simulados
          backgroundColor: 'rgba(54, 162, 235, 0.2)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      };

      new Chart(ctx, {
        type: 'bar',
        data: datosAsistencia,
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    }
  }

  // Event listener para ver foto
  if (verFotoBtn) {
    verFotoBtn.addEventListener('click', function() {
      const fotoImagen = document.getElementById('fotoImagen');
      if (miembroActual && miembroActual.foto && miembroActual.foto !== '' && miembroActual.foto !== '0' && miembroActual.foto !== null) {
        const fotoUrl = `${window.CoreFitBaseUrl}vistas/public/files/miembros/${miembroActual.foto}`;
        // Verificar si la imagen existe
        fetch(fotoUrl)
          .then(response => {
            if (response.ok) {
              fotoImagen.src = fotoUrl;
            } else {
              fotoImagen.src = `${window.CoreFitBaseUrl}vistas/public/icons/usuario.ico`;
            }
          })
          .catch(() => {
            fotoImagen.src = `${window.CoreFitBaseUrl}vistas/public/icons/usuario.ico`;
          });
      } else {
        fotoImagen.src = `${window.CoreFitBaseUrl}vistas/public/icons/usuario.ico`;
      }
      mostrarModal(modalFoto);
    });
  }

  // Event listeners para imprimir PDFs
  if (imprimirRegistroBtn) {
    imprimirRegistroBtn.addEventListener('click', function() {
      if (miembroActual) {
        window.open(`${window.CoreFitBaseUrl}vistas/public/files/pdf/registro_actividad.php?id=${miembroActual.id}`, '_blank');
      }
    });
  }

  if (imprimirHistoriaBtn) {
    imprimirHistoriaBtn.addEventListener('click', function() {
      if (miembroActual) {
        window.open(`${window.CoreFitBaseUrl}vistas/public/files/pdf/historial_pagos.php?id=${miembroActual.id}`, '_blank');
      }
    });
  }

  // Búsqueda en tiempo real
  const searchBar = document.getElementById('searchGlobal');
  if (searchBar) {
    searchBar.addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      const tableRows = document.querySelectorAll('#tablaPagos tbody tr');
      
      tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });
  }

  // Cerrar modal al hacer clic fuera de él
  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-window')) {
      ocultarModal(e.target);
    }
  });
});