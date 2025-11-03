document.addEventListener('DOMContentLoaded', () => {
  // Cargar plantillas guardadas desde el controlador (si existen) y receptores seleccionados (si existen)
  (async () => {
    try {
      const resp = await fetch((window.CoreFitBaseUrl || '/') + 'controladores/controladorMensajes.php');
      const json = await resp.json();
      if (json.success) {
        const map = {};
        if (Array.isArray(json.templates)) json.templates.forEach(t => map[t.nombre] = t);

        document.querySelectorAll('.mensaje-card').forEach(card => {
          const title = card.querySelector('h3').textContent.trim();
          if (map[title]) {
            const tpl = map[title];
            card.querySelector('textarea').value = tpl.texto || '';
            const toggle = card.querySelector('.mensaje-toggle');
            if (toggle) toggle.checked = tpl.habilitado == 1;
            // Para diferenciar campos de días: dias_antes y cada_x_dias
            if (title.toLowerCase().includes('aviso') && tpl.dias_antes !== null) {
              const daysInput = card.querySelector('.days-input input[type="number"]');
              if (daysInput) daysInput.value = tpl.dias_antes;
            }
            if (title.toLowerCase().includes('recordatorio') && tpl.cada_x_dias !== null) {
              const daysInput = card.querySelector('.days-input input[type="number"]');
              if (daysInput) daysInput.value = tpl.cada_x_dias;
            }
          }
        });

        // Restaurar receptores seleccionados
        if (Array.isArray(json.receptores) && json.receptores.length) {
          json.receptores.forEach(id => {
            const checkbox = document.querySelector('.member-select[data-id="' + id + '"]');
            if (checkbox) checkbox.checked = true;
          });
        }
      }
    } catch (err) {
      console.debug('No se pudieron cargar plantillas o receptores:', err);
    }
  })();

  // Selección de miembros
  const selectAllCheckbox = document.getElementById('select-all-members');
  const memberCheckboxes = document.querySelectorAll('.member-select');
  
  // Manejo de selección de todos los miembros
  selectAllCheckbox?.addEventListener('change', (e) => {
    memberCheckboxes.forEach(checkbox => {
      checkbox.checked = e.target.checked;
    });
  });

  // Búsqueda de miembros
  const searchInput = document.querySelector('.search-member input');
  searchInput?.addEventListener('input', (e) => {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.receptores-table tbody tr');

    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
  });

  // Manejo de botones de prueba de mensaje
  function handleSendButtonClick(button) {
    button.addEventListener('click', async () => {
      const card = button.closest('.mensaje-card');
      const toggle = card.querySelector('.mensaje-toggle');
      const isEnabled = toggle ? toggle.checked : true;

      if (!isEnabled) {
        alert('Habilite el mensaje para probarlo.');
        return;
      }

      const message = card.querySelector('textarea').value;

      // Recopilar teléfonos de miembros seleccionados
      const checked = Array.from(document.querySelectorAll('.member-select')).filter(c => c.checked);
      if (checked.length === 0) {
        alert('Seleccione al menos un receptor en la tabla de receptores para enviar la prueba.');
        return;
      }
      const phones = checked.map(c => {
        const tr = c.closest('tr');
        // Columna de teléfono que esta en el índice 4
        const tdTelefono = tr.querySelectorAll('td')[4];
        return tdTelefono ? tdTelefono.textContent.trim() : null;
      }).filter(Boolean);

      // Enviar petición al controlador para pruebas (twilio en backend)
      try {
        const resp = await fetch((window.CoreFitBaseUrl || '/') + 'controladores/controladorMensajes.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'enviar_prueba', phones, message })
        });
        const json = await resp.json();
        if (json.success) {
          alert('Mensaje enviado.');
        } else {
          alert('Error al enviar prueba: ' + (json.error || 'desconocido'));
        }
      } catch (err) {
        console.error(err);
        alert('Error en la petición: ' + err.message);
      }
    });
  }

  // Manejadores para los botones de envío existentes
  document.querySelectorAll('.send-btn').forEach(btn => handleSendButtonClick(btn));

  // Guardar configuración
  const saveButton = document.getElementById('save-settings-btn');
  saveButton?.addEventListener('click', () => {
    const config = Array.from(document.querySelectorAll('.mensaje-card')).map(card => {
      const title = card.querySelector('h3').textContent.trim();
      // Determina que días son relevantes el envío de mensajes
      const daysBeforeInput = (title.toLowerCase().includes('aviso')) ? card.querySelector('.days-input input[type="number"]') : null;
      const everyXInput = (title.toLowerCase().includes('recordatorio') || card.dataset.custom === '1') ? card.querySelector('.days-input input[type="number"]') : null;
      return {
        name: title,
        message: card.querySelector('textarea').value,
        enabled: !!card.querySelector('.mensaje-toggle').checked,
        days_before: daysBeforeInput ? daysBeforeInput.value : null,
        every_x_days: everyXInput ? everyXInput.value : null
      };
    });

    // Receptores seleccionados por medio de la tarjeta rfid
    const selected = Array.from(document.querySelectorAll('.member-select')).filter(c => c.checked).map(c => c.getAttribute('data-id'));

    // Enviar al controlador para guardar templates y receptores
    (async () => {
      try {
        const resp = await fetch((window.CoreFitBaseUrl || '/') + 'controladores/controladorMensajes.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'guardar_config', templates: config, receptores: selected })
        });
        const json = await resp.json();
        if (json.success) {
          alert('Configuración guardada correctamente.');
        } else {
          alert('No fue posible guardar la configuración.');
        }
      } catch (err) {
        console.error(err);
        alert('Error al guardar configuración: ' + err.message);
      }
    })();
  });

  // Agregar nuevo mensaje personalizado
  const addBtn = document.getElementById('add-message-btn');
  addBtn?.addEventListener('click', () => {
    const name = prompt('Nombre de la plantilla (ej: Mensaje personalizado)');
    if (!name) return;
    const every = prompt('Enviar cada X días (dejar vacío para no enviar periódicamente)');
    const text = prompt('Contenido del mensaje');
    const container = document.getElementById('mensajes-container');
    const card = document.createElement('div');
    card.className = 'mensaje-card';
    card.dataset.custom = '1';
    card.innerHTML = `
      <div class="mensaje-header">
        <h3>${name}</h3>
        <label class="switch">
          <input type="checkbox" class="mensaje-toggle" checked>
          <span class="slider"></span>
        </label>
      </div>
      <div class="days-input">
        <span>Enviar cada</span>
        <input type="number" value="${every ? every : 7}" min="1" max="365" title="Enviar cada X días">
        <span>días</span>
      </div>
      <textarea rows="4">${text ? text : ''}</textarea>
      <button class="send-btn">Probar mensaje</button>
    `;
    container.appendChild(card);
    // Nuevos manejadores para que el contenedor del nuevo mensaje funcione correctamente
    const newBtn = card.querySelector('.send-btn');
    if (newBtn) handleSendButtonClick(newBtn);
  });
});
