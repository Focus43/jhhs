<?php 
defined('C5_EXECUTE') or die("Access Denied.");

$form  = Core::make('helper/form');
?>

<div class="form-group">
	<?php 
	$file_set_options = $controller->getFileSetsOptions();
	
    if (count($file_set_options) > 0) {
		echo $form->label('fsID', t('Choose a file set'));
		?>
	
		<div class="input">
			<?php 
			echo $form->select('fsID', $file_set_options, $fsID);
			?>
		</div>
		<?php 
    }
    ?>
</div>

<div class="form-group">
	<?php 
	echo $form->label('max_width', t('Max width'));
	?>

	<div class="input">
		<?php 
		echo $form->number('max_width', $max_width);
		?>
	</div>
</div>

<div class="form-group">
	<?php 
	echo $form->label('max_height', t('Max height'));
	?>

	<div class="input">
		<?php 
		echo $form->number('max_height', $max_height);
		?>
	</div>
</div>

<div class="form-group">
	<label>
		<?php 
		echo $form->checkbox('do_crop', 1, $do_crop);
		?>
		
		<?php 
		echo t("Force image dimensions (crop)");
		?>
	</label>
</div>