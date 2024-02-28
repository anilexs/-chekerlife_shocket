<?php require_once "inc/header.php"; ?>
<link rel="stylesheet" href="asset/css/soutien.css">
<title>nous soutenir</title>
<?php require_once "inc/nav.php"; ?>

<div id="donate-button-container">
<div id="donate-button"></div>
<script src="https://www.paypalobjects.com/donate/sdk/donate-sdk.js" charset="UTF-8"></script>
<script>
PayPal.Donation.Button({
env:'production',
hosted_button_id:'J7SSVMM49KHCU',
image: {
src:'https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donateCC_LG.gif',
alt:'Bouton Faites un don avec PayPal',
title:'PayPal - The safer, easier way to pay online!',
}
}).render('#donate-button');
</script>
</div>

<?php require_once "inc/footer.php"; ?>