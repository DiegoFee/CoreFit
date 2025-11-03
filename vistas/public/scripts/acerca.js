// Previsualizar logo
const logoInput = document.getElementById('logo-input');
const logoImg = document.getElementById('company-logo');
const form = document.querySelector('.datos-empresa');
const restaurarLogo = document.getElementById('restaurar-logo');

// Ruta al logo por defecto (logo-novacorp.jpg)
const defaultLogo = logoImg ? logoImg.dataset.default : '';

// Restablecer los campos a default
if (form && logoImg) {
  form.addEventListener('reset', (e) => {
    setTimeout(() => {
      form.querySelectorAll('input[type="text"], input[type="email"]').forEach(input => input.value = '');
      logoImg.src = defaultLogo;
      if (logoInput) logoInput.value = '';
      if (restaurarLogo) restaurarLogo.checked = false;
    }, 0);
  });
}

// Previsualización de la imagen seleccionada
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
