<?php get_header(); ?>

<?php $Page = get_post_controller(); ?>

<?php
echo <<<HTML
<div class="row">
	<div class="small-12 columns">

		<h1>{$Page->title()}</h1>
		{$Page->content()}

	</div>
</div>
HTML;
?>

<?php get_footer(); ?>
