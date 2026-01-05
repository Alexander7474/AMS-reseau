document.getElementById('test-debit').addEventListener('click', function() {
  const resultDiv = document.getElementById('result-debit');
  resultDiv.innerHTML = `
    <div class="mt-2 mb-2 alert alert-warning alert-dismissible fade show" role="alert">
      <strong>Attention!</strong> Test de débit en cours, ne quittez pas la page.
    </div>
  `;

  fetch('debit.php')
    .then(response => {
      if (!response.ok) throw new Error('Network response was not ok');
      return response.text(); // we expect plain text
    })
    .then(data => {
      const result = JSON.parse(data);
      resultDiv.innerHTML = `
      <div class="mt-2 mb-2 alert alert-info alert-dismissible fade show" role="alert">
        <strong>Test fini.</strong> Vitesse de download: ${result.dlSpeed} MiBps. Vitesse d'upload ${result.upSpeed} MiBps.
      </div>
      `;
    })
    .catch(error => {
      resultDiv.innerHTML = `
      <div class="mt-2 mb-2 alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Erreur.</strong> Ne quittez pas la page pendant le test.
      </div>
      `;
      console.error('Fetch error:', error);
    });
});
