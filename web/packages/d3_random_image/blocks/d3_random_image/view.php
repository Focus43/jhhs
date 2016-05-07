<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>

<div class="d3-random-image">
	<?php 
	if (isset($error)) {
		echo $error;
	} else {
		?>
		<img src="<?php  echo $imagePath ?>" />
		<?php 
	}
	?>
</div>