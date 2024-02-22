<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://ministryinsights.com/
 * @since      1.0.0
 *
 * @package    Tti_Reporting_Module
 * @subpackage Tti_Reporting_Module/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<p>Select a date range below to extract users who registered within that period.</p>

	<form method="post" action="">
		<label for="start_date">Start Date:</label>
		<input type="date" id="start_date" name="start_date" value="<?php echo date( 'Y-m-01' ); ?>" max="<?php echo date( 'Y-m-d' ); ?>" required>

		<label for="end_date">End Date:</label>
		<input type="date" id="end_date" name="end_date" value="<?php echo date( 'Y-m-t' ); ?>" max="<?php echo date( 'Y-m-d' ); ?>" required>

		<button type="submit" class="button">Submit</button>
	</form>

	<pre>
		<?php print_r($this->all_courses) ?>
	</pre>

</div>


