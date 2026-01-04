
<button type="submit" class="mybtn mybtn-primary" id="test-debit">Tester le débit</button>
<button type="button" class="mybtn mybtn-info" onclick='infoBox("Permet de connaitre le debit moyen en Mebibyte entre votre box et les server de votre FAI. ", "test-debit", "https://fr.wikipedia.org/wiki/D%C3%A9bit_binaire")'>?</button>

<div id="result-debit">
</div>

<script src="<?php echo $racine_path;?>src/js/test-debit.js"></script>
