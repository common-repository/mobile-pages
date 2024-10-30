<?php
if ( isset( $_POST['gbmp_nonce'] ) && wp_verify_nonce( $_POST['gbmp_nonce'], 'gbmp-admin' ) ) {
	$fields = [ 'gbmp-mobile-first', 'gbmp-mob1-regex', 'gbmp-mob2-regex', 'gbmp-mob-ex-regex' ];
	foreach ( $fields as $field ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_option( $field, stripslashes( $_POST[ $field ] ) );
		} else {
			delete_option( $field );
		}
	}
	?>
	<div id="setting-error-settings_updated" class="notice notice-success settings-error is-dismissible">
		<p><strong>Settings saved.</strong></p>
	</div>
	<?php
}
?>
<?php wp_enqueue_style( 'gbmp-admin-page' ); ?>
<div class="wrap" style="max-width: 980px">
	<h1>Mobile Pages <a href="#settings" class="button button-primary">Settings</a></h1>

	The Mobile Pages plugin makes it easy to create awesome Mobile Pages for WordPress using the Gutenberg Block
	Editor.

	<h3>The Background</h3>

	In May 2021 Google Search is releasing its Web Vitals update that will make having great mobile pages even more
	important to your website ranking.
	Google Web Vitals will look at a range of factors, including the design and speed of your mobile pages.
	Our plugin makes it easy to make sure your website is fully optimized for mobile and ready for the Google Web
	Vitals
	update.

	<h3>Video introduction</h3>
	<?php echo wp_oembed_get( 'https://vimeo.com/498910228' ) ?>

	<h3>Plugin Benefits</h3>

	<ul class="ul-disc">
		<li>Create 100% unique page content for mobile devices</li>
		<li>Create awesome mobile pages for WordPress</li>
		<li>Create 'mobile first' page designs</li>
		<li>Improve Mobile Page Speed scores on Google</li>
		<li>Improve SEO ranking</li>
	</ul>

	<h3>Plugin Features</h3>

	<ul class="ul-disc">
		<li>A desktop tab and a mobile tab make it easy to create content for specific devices</li>
		<li>Use for the whole page or for specific sections of the page</li>
		<li>Convert existing pages into mobile optimised pages using our '1 click' import</li>
		<li>Duplicate desktop content to the mobile tab for quick editing</li>
		<li>Can be used for any WordPress content that uses the Gutenberg Block Editor</li>
	</ul>

	<h3 id="settings">Settings</h3>

	<form method="post">
		<?php echo wp_nonce_field( 'gbmp-admin', 'gbmp_nonce' ) ?>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><label for="gbmp-mobile-first">Mobile first</label></th>
				<td>
					<input name="gbmp-mobile-first" type="checkbox" id="gbmp-mobile-first" value="1"
						<?php checked( get_option( 'gbmp-mobile-first' ) ) ?>> Check to design for mobile first
					<p class="description">
						If you want to target more of mobile traffic,
						you can toggle mobile first mode for the progressive advancement approach.
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="gbmp-mob1-regex">Mobile regex 1</label></th>
				<td>
					<input name="gbmp-mob1-regex" type="text" id="gbmp-mob1-regex"
								 value="<?php echo Mobile_Pages::regexps()['m1'] ?>" class="widefat">
					<p class="description">
						Keep away unless you really know what you are doing, this is for precise control control over devices
						the mobile content will be displayed. If the user agent matches this regex we show the mobile content.
					</p>
					<p class="description" class="description">Default: </p>
					<div class="description description gbmp-text"><?php echo Mobile_Pages::regexps( 'default' )['m1'] ?></div>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="gbmp-mob2-regex">Mobile regex 2</label></th>
				<td>
					<input name="gbmp-mob2-regex" type="text" id="gbmp-mob2-regex"
								 value="<?php echo Mobile_Pages::regexps()['m2'] ?>" class="widefat">
					<p class="description">
						Keep away unless you really know what you are doing. If first 4 characters of the user agent matches this
						regex we show the mobile content.
					</p>
					<p class="description">Default: </p>
					<div class="description gbmp-text"><?php echo Mobile_Pages::regexps( 'default' )['m2'] ?></div>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="gbmp-mob-ex-regex">Force desktop regex</label></th>
				<td>
					<input name="gbmp-mob-ex-regex" type="text" id="gbmp-mob-ex-regex"
								 value="<?php echo Mobile_Pages::regexps()['mex'] ?>" class="widefat">
					<p class="description">
						Keep away unless you really know what you are doing. User agent matching this regex will be excluded from
						mobile content views even if they match regex above and will have desktop content displayed instead.
					</p>
					<p class="description">Empty by Default</p>
				</td>
			</tr>
		</table>

		<?php submit_button( 'Save' ); ?>
	</form>

</div>
