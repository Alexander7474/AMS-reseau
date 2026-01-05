<?php
if(isset($error)){
  echo '
        <div class="mt-2 mb-2 alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Erreur!</strong> '.$error.'
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
          </button>
        </div>
';
}
?>
