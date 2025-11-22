<?php $content = ob_get_clean(); ?>
<?php ob_start(); ?>
    <h1>
        <?= $title ?>
    </h1>
<?php $content = ob_get_clean(); ?>
<?php include base_path('resources/views/layouts/app.php'); ?>