const filterSelect = document.getElementById('Tryb-pracy');
const selectedOption = document.getElementById('selected-option');

filterSelect.addEventListener('change', function() {
  selectedOption.innerText = filterSelect.value;
  selectedOption.parentElement.hidden = false;
});
filterSelect.addEventListener('click', function() {
  if (filterSelect.value === 'Tryb pracy') {
    selectedOption.parentElement.hidden = true;
  }
});
