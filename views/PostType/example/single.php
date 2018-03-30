<?php get_header(); ?>

<?php $Post = get_post_controller(); ?>

<?php
echo <<<HTML
<div class="row">
	<div class="small-12 columns">
		<h1>{$Post->title()}</h1>
		<h2>{$Post->date('F j, Y')}</h2>
		{$Post->content()}

	</div>
</div>
HTML;
?>

<?php get_footer(); ?>
