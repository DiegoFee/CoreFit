// PREVISUALIZAR LOGO
const logoInput = document.getElementById('logo-input');
const logoImg = document.getElementById('company-logo');
const saveLogoBtn = document.getElementById('save-logo-btn');
const deleteLogoBtn = document.getElementById('delete-logo-btn');

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

saveLogoBtn.addEventListener('click', () => {
  alert('Imagen guardada (simulado).');
});

deleteLogoBtn.addEventListener('click', () => {
  logoImg.src = '/public/images/logo-novacorp.jpg';
  logoInput.value = '';
});
