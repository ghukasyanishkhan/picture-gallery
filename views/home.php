
<?php if (isset($header)): ?>
<h1><?php echo $header?></h1>
 <?php endif; ?>

<div id="photos-container"></div>
<div id="pagination-container"></div>

<script src="scripts.js"></script>
<script>
    const photosJson = <?= $photosJson ?>;
    pagination(photosJson);
</script>