document.addEventListener("DOMContentLoaded", () => {
  const btnRegistrar = document.getElementById("btnRegistrar");
  const registroMembresia = document.getElementById("registroMembresia");
  const btnAtras = document.getElementById("btnAtras");
  const membresiasLista = document.getElementById("membresiasLista");

  // MOSTRAR FORMULARIO DE REGISTRO Y OCULTAR LISTA
  btnRegistrar.addEventListener("click", () => {
    membresiasLista.style.display = "none";
    registroMembresia.style.display = "block";
    window.scrollTo({ top: registroMembresia.offsetTop, behavior: "smooth" });
  });

  // VOLVER A LA LISTA DE MIEMBROS
  btnAtras.addEventListener("click", () => {
    registroMembresia.style.display = "none";
    membresiasLista.style.display = "block";
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
});
