document.addEventListener('DOMContentLoaded', function() {
  const btnRegistrar = document.getElementById('btnRegistrar');
  const btnAtras = document.getElementById('btnAtras');
  const miembrosLista = document.getElementById('miembrosLista');
  const registroMiembro = document.getElementById('registroMiembro');
  const membresiaSelect = document.getElementById('membresia_id');
  const precioInput = document.getElementById('precio_total');
  const fechaDesdeInput = document.getElementById('fecha_desde');
  const fechaHastaInput = document.getElementById('fecha_hasta');

  // Configurar fecha actual por defecto
  const hoy = new Date().toISOString().split('T')[0];
  fechaDesdeInput.value = hoy;

  // Mostrar formulario de registro
  btnRegistrar.addEventListener('click', function() {
    miembrosLista.style.display = 'none';
    registroMiembro.style.display = 'block';
  });

  // Volver a la lista
  btnAtras.addEventListener('click', function() {
    registroMiembro.style.display = 'none';
    miembrosLista.style.display = 'block';
  });

  // Actualizar precio y fecha cuando cambie la membresía
  membresiaSelect.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    if (selectedOption.value) {
      const precio = selectedOption.getAttribute('data-precio');
      const meses = selectedOption.getAttribute('data-meses');
      
      precioInput.value = precio;
      
      // Calcular fecha hasta
      const fechaDesde = new Date(fechaDesdeInput.value);
      const fechaHasta = new Date(fechaDesde);
      fechaHasta.setMonth(fechaHasta.getMonth() + parseInt(meses));
      
      fechaHastaInput.value = fechaHasta.toISOString().split('T')[0];
    } else {
      precioInput.value = '';
      fechaHastaInput.value = '';
    }
  });

  // Actualizar fecha hasta cuando cambie fecha desde
  fechaDesdeInput.addEventListener('change', function() {
    if (membresiaSelect.value) {
      const selectedOption = membresiaSelect.options[membresiaSelect.selectedIndex];
      const meses = selectedOption.getAttribute('data-meses');
      
      const fechaDesde = new Date(this.value);
      const fechaHasta = new Date(fechaDesde);
      fechaHasta.setMonth(fechaHasta.getMonth() + parseInt(meses));
      
      fechaHastaInput.value = fechaHasta.toISOString().split('T')[0];
    }
  });

  // Búsqueda en tiempo real
  const searchBar = document.querySelector('.search-bar');
  if (searchBar) {
    searchBar.addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      const tableRows = document.querySelectorAll('.members-table tbody tr');
      
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
});