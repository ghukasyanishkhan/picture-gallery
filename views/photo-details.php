<h1>Photo details</h1>
<div id="photo-detail">
    <img id="photo-detail-img" src="<?php echo htmlspecialchars($photo->path); ?>" alt="Photo">
    <p id="photo-detail-name"><?php echo htmlspecialchars($photo->name); ?></p>
    <p id="photo-detail-user">by: <?php echo htmlspecialchars($user->firstname); ?></p>
</div>
