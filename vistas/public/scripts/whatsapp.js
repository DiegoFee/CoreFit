// LÓGICA PRINCIPAL DE LOS MENSAJES
const addBtn = document.getElementById('add-message-btn');
const mensajesContainer = document.getElementById('mensajes-container');

addBtn.addEventListener('click', () => {
  const newCard = document.createElement('div');
  newCard.className = 'mensaje-card';
  newCard.innerHTML = `
    <h3>Nuevo mensaje</h3>
    <textarea placeholder="Escribe tu mensaje..."></textarea>
    <button class="send-btn">Enviar mensaje de prueba</button>
  `;
  mensajesContainer.appendChild(newCard);
});
