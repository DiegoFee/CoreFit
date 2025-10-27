// PREVISUALIZAR LOGO
const logoInput = document.getElementById('logo-input');
const logoImg = document.getElementById('company-logo');
const form = document.querySelector('.datos-empresa');
const restaurarLogo = document.getElementById('restaurar-logo');

// RUTA AL LOGO POR DEFECTO
const defaultLogo = logoImg ? logoImg.dataset.default : '';

if (form && logoImg) {
  form.addEventListener('reset', (e) => {
    setTimeout(() => {
      // VACÍA TODOS LOS CAMPOS DE TEXTO
      form.querySelectorAll('input[type="text"], input[type="email"]').forEach(input => input.value = '');
      // CAMBIA EL LOGO AL DE NOVACORP
      logoImg.src = defaultLogo;
      if (logoInput) logoInput.value = '';
      if (restaurarLogo) restaurarLogo.checked = false;
    }, 0);
  });
}

// PREVISUALIZACIÓN DE LA IMAGEN SELECCIONADA
if (logoInput && logoImg) {
  logoInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if(file){
      const reader = new FileReader();
      reader.onload = () => {
        logoImg.src = reader.result;
      };
      reader.readAsDataURL(file);
    }
  });
}
