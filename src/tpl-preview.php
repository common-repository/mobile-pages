<html>
<head>
	<title><?php printf( __( 'Mobile pages preview - %s', 'gbmp' ), get_bloginfo( 'name' ) ); ?></title>
	<style>
		html, body {
			min-width: 100%;
			min-height: 100vh;
			display: flex;
			flex-wrap: wrap;
			align-items: center;
			flex-direction: column;
			font: 1em sans-serif;
			margin: 0 auto;
			box-sizing: border-box;
		}

		body {
			margin-top: 2em;
		}

		body > * {
			margin-bottom: 2em !important;
		}

		p.description {
			max-width: 700px;
			font: 16px sans-serif;
			margin: 0;
		}

		.tabs {
			display: flex;
			text-align: center;
			margin: 0 auto;
			border: 1px solid #007cba;
			border-radius: 2px;
			overflow: hidden;
		}

		.tab {
			color: #007cba;
			font-size: 14px;
			text-decoration: none;
			padding: 11px 9px;
			width: 100px;
			display: inline-block;
			transition: 0.1s;
		}

		.tab:not(:last-of-type) {
			border-right: 1px solid;
		}

		.tab.active {
			color: #ffffff;
			background: #007cba;
		}

		.ipad {
			padding: 23px 23px 23px 23px;
			background: 0 0/740px no-repeat;
			height: 1048px;
			width: 694px;
			border-radius: 53px;
			position: relative;
			box-shadow: 2px 2px 16px #000;
			margin-bottom: 2em;
		}

		.ipad iframe {
			display: block;
			width: 659px;
			height: 877px;
			border-radius: 2px;
			margin-top: 84px;
			margin-left: 18px;
		}

		.iphone {
			padding: 23px 23px 23px 25px;
			background: -1px -1px/427px no-repeat;
			height: 817px;
			width: 377px;
			border-radius: 61px;
			position: relative;
			box-shadow: 2px 2px 16px #000;
			margin-bottom: 2em;
		}

		.iphone iframe {
			display: block;
			width: 378px;
			height: 821px;
			border-radius: 38px;
		}

		.iphone:after {
			content: '';
			background: inherit;
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			border-radius: inherit;
			clip: rect(0px,321px,55px,107px);
		}

		.desktop {
			width: calc( 100% - 7em );
			border: .5em solid #555;
			border-radius: .25em;
			box-shadow: 2px 2px 16px #000;
			position: relative;
		}

		.desktop:after {
			content: '';
			position: absolute;
			top: 100%;
			height: 2.5em;
			background: #555;
			background-clip: content-box;
			border: 2em solid transparent;
			border-top: 0;
			border-bottom: 1em solid #555;
			width: 25vw;
			left: calc( 50% - 12.5vw );
		}

		.desktop iframe {
			width: 100%;
			height: calc( 100vh - 7em );
		}
	</style>
</head>
<body>
<?php
if ( empty( $_GET['p'] ) ) {
	?>
	<h3>Incorrect request, parameter <code>p</code> is required.</h3>
	<?php
} else {
	$desktop_preview = add_query_arg( 'mobile-pages-preview', 'desktop', get_the_permalink( $_GET['p'] ) );
	$preview_url = add_query_arg( 'mobile-pages-preview', 'mobile', $desktop_preview );
	$device_preview_urls = [
		'iphone' => $preview_url,
		'ipad' => $preview_url,
		'desktop' => $desktop_preview,
	];
	$device = filter_input( INPUT_GET, 'device' );
	$device = isset( $device_preview_urls[ $device ] ) ? $device : 'iphone';
	?>

	<p class="description">
		These previews load your website in a frame with correct dimensions letting media queries kick in, giving you a far more realistic preview for the mobile devices than the standard previews.
	</p>

	<div class="tabs">
		<a href="<?php echo add_query_arg( 'device', 'iphone' ) ?>"
			 class="tab <?php echo $device === 'iphone' ? 'active' : ''; ?>">Mobile</a>
		<a href="<?php echo add_query_arg( 'device', 'ipad' ) ?>"
			 class="tab <?php echo $device === 'ipad' ? 'active' : ''; ?>">Tablet</a>
		<a href="<?php echo add_query_arg( 'device', 'desktop' ) ?>"
			 class="tab <?php echo $device === 'desktop' ? 'active' : ''; ?>">Desktop</a>
	</div>



	<div class="<?php echo $device ?>" style="background-image:url(<?php echo plugins_url( $device . '-frame.png', __FILE__ ) ?>)">
		<iframe src="<?php echo $device_preview_urls[ $device ]; ?>" frameborder="0"></iframe>
	</div>
	<?php
}
?>
</body>
</html>
<?php
