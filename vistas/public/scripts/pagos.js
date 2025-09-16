// pagos.js - comportamiento modales, chart, búsquedas e impresión
document.addEventListener('DOMContentLoaded', () => {

  /* ---------- helpers ---------- */
  const $ = sel => document.querySelector(sel);
  const $$ = sel => Array.from(document.querySelectorAll(sel));

  // abrir modal (muestra y configura aria)
  function openModal(modalEl){
    if(!modalEl) return;
    modalEl.style.display = 'flex';
    modalEl.setAttribute('aria-hidden','false');
    document.body.style.overflow = 'hidden'; // evitar scroll de fondo
  }

  // cerrar modal
  function closeModal(modalEl){
    if(!modalEl) return;
    modalEl.style.display = 'none';
    modalEl.setAttribute('aria-hidden','true');
    document.body.style.overflow = '';
  }

  /* ---------- referencia a modales ---------- */
  const modalPagar = $('#modalPagar');
  const modalDetalles = $('#modalDetalles');
  const modalFoto = $('#modalFoto');

  // botones cerrar en modales
  $$('.modal-close').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const modal = btn.closest('.modal-window');
      closeModal(modal);
    });
  });

  // clic fuera del contenido -> cerrar modal (mejora UX)
  $$('.modal-window').forEach(mw => {
    mw.addEventListener('click', (ev) => {
      if(ev.target === mw) closeModal(mw);
    });
  });

  /* ---------- POBLADO Y APERTURA DE MODALES ---------- */

  // cuando click en Pagar: toma la fila más cercana y lee data-*
  $$('.pagar-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const row = btn.closest('tr');
      if(!row) return;
      populatePagoModal(row);
      openModal(modalPagar);
    });
  });

  // cuando click en Detalles
  $$('.detalles-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const row = btn.closest('tr');
      if(!row) return;
      populateDetallesModal(row);
      openModal(modalDetalles);
    });
  });

  // extrae atributos data de la fila y llena modal de pagar
  function populatePagoModal(row){
    const id = row.dataset.id || '';
    const nombre = row.dataset.nombre || '';
    const apellido = row.dataset.apellido || '';
    const telefono = row.dataset.telefono || '';
    const membresia = row.dataset.membresia || '';
    const precio = row.dataset.precio || '0';
    const desde = row.dataset.desde || '';
    const hasta = row.dataset.hasta || '';
    const actividad = row.dataset.actividad || '';
    const estado = row.dataset.estado || '';
    const pago = row.dataset.pago || '';

    $('#pagarNombre').textContent = `${nombre} ${apellido}`;
    const container = $('#miembroDatosPago');
    container.innerHTML = `
      <p><strong>Nombre:</strong> ${nombre}</p>
      <p><strong>Apellido:</strong> ${apellido}</p>
      <p><strong>Teléfono:</strong> ${telefono}</p>
      <p><strong>Estado:</strong> ${estado}</p>
      <p><strong>Periodo:</strong> ${desde} → ${hasta}</p>
      <p><strong>Pago actual:</strong> ${pago}</p>
    `;

    $('#pagarMembresia').value = membresia;
    $('#pagarPrecio').value = precio;
    $('#pagarDebe').value = precio;
    $('#pagarCantidad').value = ''; // limpiar
    // opcional: guardar id actual en dataset del formulario para submit
    $('#formPago').dataset.memberId = id;
  }

  // llenar modal detalles y graficar
  function populateDetallesModal(row){
    const id = row.dataset.id || '';
    const nombre = row.dataset.nombre || '';
    const apellido = row.dataset.apellido || '';
    const telefono = row.dataset.telefono || '';
    const membresia = row.dataset.membresia || '';
    const precio = parseFloat(row.dataset.precio || '0');
    const desde = row.dataset.desde || '';
    const hasta = row.dataset.hasta || '';
    const actividad = row.dataset.actividad || '';
    const estado = row.dataset.estado || '';
    const pago = row.dataset.pago || '';

    $('#detallesNombre').textContent = `${nombre} ${apellido}`;
    const container = $('#miembroDatosDetalles');
    container.innerHTML = `
      <p><strong>Nombre:</strong> ${nombre}</p>
      <p><strong>Apellido:</strong> ${apellido}</p>
      <p><strong>Teléfono:</strong> ${telefono}</p>
      <p><strong>Membresía:</strong> ${membresia}</p>
      <p><strong>Periodo:</strong> ${desde} → ${hasta}</p>
      <p><strong>Actividad:</strong> ${actividad}</p>
      <p><strong>Estado:</strong> ${estado}</p>
      <p><strong>Último pago:</strong> ${pago}</p>
    `;

    // preparar botón ver foto (simulamos con imagen placeholder)
    $('#verFotoBtn').onclick = () => {
      $('#fotoImagen').src = '/public/images/placeholder-user.png'; // reemplazar por URL real si está disponible
      openModal(modalFoto);
    };

    // imprimir registro (solo datos del miembro)
    $('#imprimirRegistroBtn').onclick = () => {
      const html = `
        <html><head><title>Registro ${nombre} ${apellido}</title>
        <style>
          body{font-family:Arial,sans-serif;padding:20px;color:#111}
          .box{border:1px solid #ddd;padding:12px;border-radius:6px}
          h1{font-size:18px;margin-bottom:8px}
          p{margin:6px 0}
        </style>
        </head><body>
          <h1>Registro del miembro: ${nombre} ${apellido}</h1>
          <div class="box">
            ${container.innerHTML}
          </div>
        </body></html>
      `;
      printInNewWindow(html);
    };

    // graficar (ejemplo con datos aleatorios representativos)
    renderChart(priceToData(precio));

    // opcional: cargar transacciones de ese socio en la tabla (aquí demo estática)
    // Si tuvieras API: fetch(`/api/transacciones?member=${id}`).then(...)
  }

  /* ---------- FORM SUBMIT: Guardar pago (demo) ---------- */
  $('#formPago').addEventListener('submit', (ev) => {
    ev.preventDefault();
    const memberId = ev.target.dataset.memberId || '0';
    const amount = parseFloat($('#pagarCantidad').value || 0);
    if(isNaN(amount) || amount <= 0){
      alert('Ingrese una cantidad válida a pagar.');
      return;
    }
    // Aquí: enviar a backend (fetch / POST). Demo: actualizar fila localmente
    const row = document.querySelector(`tr[data-id="${memberId}"]`);
    if(row){
      // ejemplo simple: marcar como "Al día" si paga >= debe
      const debe = parseFloat($('#pagarDebe').value||0);
      const newPaid = amount >= debe ? 'Q' + debe.toFixed(2) : 'Q' + amount.toFixed(2);
      row.dataset.pago = newPaid;
      row.querySelector('td:nth-child(8) .badge')?.classList?.remove('badge-danger');
      row.querySelector('td:nth-child(8)') && (row.querySelector('td:nth-child(8)').innerHTML = '<span class="badge badge-ok">Al día</span>');
      alert('Pago guardado (demo). Integra tu API para persistir datos.');
    }
    closeModal(modalPagar);
  });


  /* ---------- BÚSQUEDAS ---------- */
  // filtro global tabla principal
  $('#searchGlobal').addEventListener('input', (e) => {
    const q = e.target.value.trim().toLowerCase();
    filterTable('#tablaPagos tbody tr', q);
  });

  // filtro transacciones dentro del modal (búsqueda simple)
  $('#searchTrans')?.addEventListener('input', (e) => {
    const q = e.target.value.trim().toLowerCase();
    filterTable('#tablaTrans tbody tr', q);
  });

  function filterTable(selector, query){
    const rows = document.querySelectorAll(selector);
    rows.forEach(r => {
      const text = r.textContent.toLowerCase();
      r.style.display = text.includes(query) ? '' : 'none';
    });
  }

  /* ---------- IMPRESIÓN DE HISTORIAL (PDF) ---------- */
  $('#imprimirHistoriaBtn').addEventListener('click', () => {
    // tomar la tabla de transacciones y generar una vista para imprimir
    const tabla = $('#tablaTrans').outerHTML;
    const title = $('#detallesNombre').textContent || 'Historial de pagos';
    const html = `
      <html><head><title>${title}</title>
      <style>
        body{font-family:Arial,sans-serif;padding:20px;color:#111}
        h1{font-size:18px;margin-bottom:8px}
        table{width:100%;border-collapse:collapse}
        th,td{padding:8px;border:1px solid #ccc;text-align:center}
        th{background:#f2f2f2}
      </style>
      </head><body>
        <h1>${title} — Historial de pagos</h1>
        ${tabla}
      </body></html>
    `;
    printInNewWindow(html);
  });

  // helper que abre nueva ventana, escribe HTML y llama print
  function printInNewWindow(html){
    const w = window.open('','_blank','noopener,noreferrer');
    if(!w) {
      alert('Bloqueador de ventanas impide abrir la vista de impresión. Permite ventanas emergentes para este sitio.');
      return;
    }
    w.document.open();
    w.document.write(html);
    w.document.close();
    w.focus();
    // dar tiempo a que cargue recursos
    setTimeout(()=>{ w.print(); /* w.close(); optionally close */ }, 600);
  }

  /* ---------- GRAFICO (Chart.js) ---------- */
  let chartInstance = null;
  function renderChart(data){
    const ctx = document.getElementById('chartPagos').getContext('2d');
    if(chartInstance) chartInstance.destroy();
    chartInstance = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: data.labels,
        datasets: [{
          label: 'Pagos (Q)',
          data: data.values,
          borderRadius: 6,
          barThickness: 18,
          backgroundColor: function(context){
            const i = context.dataIndex;
            return i % 2 === 0 ? 'rgba(46,148,255,0.9)' : 'rgba(178,255,0,0.85)';
          }
        }]
      },
      options: {
        responsive:true,
        maintainAspectRatio:false,
        scales:{
          y:{beginAtZero:true,grid:{display:true,color:'#f2f4f7'}},
          x:{grid:{display:false}}
        },
        plugins:{legend:{display:false}}
      }
    });
  }

  // convierte un precio en datos de ejemplo (simulación)
  function priceToData(price){
    // ejemplo: 6 meses (labels) con variaciones aleatorias cerca del precio
    const labels = ['Ene','Feb','Mar','Abr','May','Jun'];
    const values = labels.map((_,i) => Math.max(0, Math.round(price * (0.6 + Math.random()*0.9))));
    return {labels, values};
  }

  /* ---------- inicializar comportamiento ---------- */
  // accesibilidad: cerrar modales con ESC
  document.addEventListener('keydown', (e) => {
    if(e.key === 'Escape'){
      $$('.modal-window').forEach(m => {
        if(m.style.display === 'flex') closeModal(m);
      });
    }
  });

  // inicial render del chart vacío (opcional)
  // renderChart(priceToData(200));

}); // DOMContentLoaded

  // inicial render del ch
