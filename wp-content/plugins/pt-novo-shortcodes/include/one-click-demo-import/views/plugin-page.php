<?php
/**
 * The plugin page view - the "settings" page of the plugin.
 *
 * @package ocdi
 */

namespace OCDI;

$predefined_themes = $this->import_files;

if ( ! empty( $this->import_files ) && isset( $_GET['import-mode'] ) && 'manual' === $_GET['import-mode'] ) {
	$predefined_themes = array();
}

/**
 * Hook for adding the custom plugin page header
 */
do_action( 'ocdi/plugin_page_header' );

function pt_ocdi_sample($page) {
  return (
    $page == 'blog-samples' || 
    $page == 'project-samples' ||
    $page == 'product-samples' ||
    $page == 'album-samples' ||
    $page == 'footers'
  );
}
?>

<div class="ocdi  wrap  about-wrap">

	<?php ob_start(); ?>
		<h1 class="ocdi__title  dashicons-before  dashicons-upload"><?php esc_html_e( 'One Click Demo Import', 'ocdi' ); ?></h1>
	<?php
	$plugin_title = ob_get_clean();

	// Display the plugin title (can be replaced with custom title text through the filter below).
	echo wp_kses_post( apply_filters( 'ocdi/plugin_page_title', $plugin_title ) );

	// Display warrning if PHP safe mode is enabled, since we wont be able to change the max_execution_time.
	if ( ini_get( 'safe_mode' ) ) {
		printf(
			esc_html__( '%sWarning: your server is using %sPHP safe mode%s. This means that you might experience server timeout errors.%s', 'ocdi' ),
			'<div class="notice  notice-warning  is-dismissible"><p>',
			'<strong>',
			'</strong>',
			'</p></div>'
		);
	}

	// Start output buffer for displaying the plugin intro text.
	ob_start();
	?>

	<div class="ocdi__intro-notice  notice  notice-warning  is-dismissible">
		<p><?php esc_html_e( 'Before you begin, make sure all the required plugins are activated.', 'ocdi' ); ?></p>
	</div>

	<div class="ocdi__intro-text">

		<p><?php esc_html_e( 'When you import the data, the following things might happen:', 'ocdi' ); ?></p>

		<ul>
			<li><?php esc_html_e( 'No existing posts, pages, categories, images, custom post types or any other data will be deleted or modified.', 'ocdi' ); ?></li>
			<li><?php esc_html_e( 'Posts, pages, images, widgets, menus and other theme settings will get imported.', 'ocdi' ); ?></li>
			<li><?php esc_html_e( 'Please click on the Import button only once and wait, it can take a couple of minutes.', 'ocdi' ); ?></li>
		</ul>
		<hr>
	</div>

	<?php
	$plugin_intro_text = ob_get_clean();

	// Display the plugin intro text (can be replaced with custom text through the filter below).
	echo wp_kses_post( apply_filters( 'ocdi/plugin_intro_text', $plugin_intro_text ) );
	?>

	<?php if ( empty( $this->import_files ) ) : ?>
		<div class="notice  notice-info  is-dismissible">
			<p><?php esc_html_e( 'There are no predefined import files available in this theme. Please upload the import files manually!', 'ocdi' ); ?></p>
		</div>
	<?php endif; ?>

	<?php if ( empty( $predefined_themes ) ) : ?>

		<div class="ocdi__file-upload-container">
			<h2><?php esc_html_e( 'Manual demo files upload', 'ocdi' ); ?></h2>

			<div class="ocdi__file-upload">
				<h3><label for="content-file-upload"><?php esc_html_e( 'Choose a XML file for content import:', 'ocdi' ); ?></label></h3>
				<input id="ocdi__content-file-upload" type="file" name="content-file-upload">
			</div>

			<div class="ocdi__file-upload">
				<h3><label for="widget-file-upload"><?php esc_html_e( 'Choose a WIE or JSON file for widget import:', 'ocdi' ); ?></label></h3>
				<input id="ocdi__widget-file-upload" type="file" name="widget-file-upload">
			</div>

			<div class="ocdi__file-upload">
				<h3><label for="customizer-file-upload"><?php esc_html_e( 'Choose a DAT file for customizer import:', 'ocdi' ); ?></label></h3>
				<input id="ocdi__customizer-file-upload" type="file" name="customizer-file-upload">
			</div>

			<?php if ( class_exists( 'ReduxFramework' ) ) : ?>
			<div class="ocdi__file-upload">
				<h3><label for="redux-file-upload"><?php esc_html_e( 'Choose a JSON file for Redux import:', 'ocdi' ); ?></label></h3>
				<input id="ocdi__redux-file-upload" type="file" name="redux-file-upload">
				<div>
					<label for="redux-option-name" class="ocdi__redux-option-name-label"><?php esc_html_e( 'Enter the Redux option name:', 'ocdi' ); ?></label>
					<input id="ocdi__redux-option-name" type="text" name="redux-option-name">
				</div>
			</div>
			<?php endif; ?>
		</div>

		<p class="ocdi__button-container">
			<button class="ocdi__button  button  button-hero  button-primary  js-ocdi-import-data"><?php esc_html_e( 'Import Demo Data', 'ocdi' ); ?></button>
		</p>

	<?php /* elseif ( 1 === count( $predefined_themes ) ) : ?>

		<div class="ocdi__demo-import-notice  js-ocdi-demo-import-notice"><?php
			if ( is_array( $predefined_themes ) && ! empty( $predefined_themes[0]['import_notice'] ) ) {
				echo wp_kses_post( $predefined_themes[0]['import_notice'] );
			}
		?></div>

		<p class="ocdi__button-container">
			<button class="ocdi__button  button  button-hero  button-primary  js-ocdi-import-data"><?php esc_html_e( 'Import Demo Data', 'ocdi' ); ?></button>
		</p>

	<?php  */
	
	else : ?>

		<!-- OCDI grid layout -->
		<div class="ocdi__gl  js-ocdi-gl">
		<?php
			// Prepare navigation data.
			$categories = Helpers::get_all_demo_import_categories( $predefined_themes );
		?>
			<?php if ( ! empty( $categories ) ) : ?>
				<div class="ocdi__gl-header  js-ocdi-gl-header">
					<nav class="ocdi__gl-navigation">
						<ul>
							<li class="active"><a href="#all" class="ocdi__gl-navigation-link  js-ocdi-nav-link"><?php esc_html_e( 'All', 'ocdi' ); ?></a></li>
							<?php foreach ( $categories as $key => $name ) : ?>
								<li><a href="#<?php echo esc_attr( $key ); ?>" class="ocdi__gl-navigation-link  js-ocdi-nav-link"><?php echo esc_html( $name ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					</nav>
					<div clas="ocdi__gl-search">
						<input type="search" class="ocdi__gl-search-input  js-ocdi-gl-search" name="ocdi-gl-search" value="" placeholder="<?php esc_html_e( 'Search demos...', 'ocdi' ); ?>">
					</div>
				</div>
			<?php endif; ?>
			<div class="ocdi__gl-item-container  wp-clearfix  js-ocdi-gl-item-container">
				<?php foreach ( $predefined_themes as $index => $import_file ) : ?>
					<?php
						// Prepare import item display data.
						$img_src = isset( $import_file['import_preview_image_url'] ) ? $import_file['import_preview_image_url'] : '';
						// Default to the theme screenshot, if a custom preview image is not defined.
						if ( empty( $img_src ) ) {
							$theme = wp_get_theme();
							$img_src = $theme->get_screenshot();
						}

					?>
					<div class="ocdi__gl-item js-ocdi-gl-item" data-categories="<?php echo esc_attr( Helpers::get_demo_import_item_categories( $import_file ) ); ?>" data-name="<?php echo esc_attr( strtolower( $import_file['import_file_name'] ) ); ?>">
						<div class="ocdi__gl-item-image-container">
							<div class="builders-logo">
                <?php if(!pt_ocdi_sample($import_file['slug']) && $import_file['categories'][0] != 'Footers' && $import_file['categories'][0] != 'Headers' && $import_file['categories'][0] != 'Contact Forms') { ?>
                  <div class="wp-backery-l"><img src="<?php echo esc_url(YPRM_PLUGINS_HTTP.'/assets/imgs/WPBackery.png') ?>" alt="WP Bakery"></div>
								<?php } if(isset($import_file['has_elementor']) && $import_file['has_elementor']) { ?>
									<div class="elementor-l"><img src="<?php echo esc_url(YPRM_PLUGINS_HTTP.'/assets/imgs/elementor.svg') ?>" alt="Elementor"></div>
								<?php } ?>
							</div>
							<?php if ( ! empty( $img_src ) ) : ?>
								<img class="ocdi__gl-item-image" src="<?php echo esc_url( $img_src ) ?>">
							<?php else : ?>
								<div class="ocdi__gl-item-image  ocdi__gl-item-image--no-image"><?php esc_html_e( 'No preview image.', 'ocdi' ); ?></div>
							<?php endif; ?>
						</div>
						<div class="ocdi__gl-item-footer<?php echo ! empty( $import_file['preview_url'] ) ? '  ocdi__gl-item-footer--with-preview' : ''; ?>">
							<h4 class="ocdi__gl-item-title" title="<?php echo esc_attr( $import_file['import_file_name'] ); ?>"><?php echo esc_html( $import_file['import_file_name'] ); ?></h4>
              <div class="buttons">
                <button class="ocdi__gl-item-button  button  button-primary  js-ocdi-gl-import-data" value="<?php echo esc_attr( $index ); ?>"><?php esc_html_e( 'Import', 'ocdi' ); ?></button>
                <?php if ( ! empty( $import_file['preview_url'] ) ) : ?>
                  <a class="ocdi__gl-item-button  button" href="<?php echo esc_url( $import_file['preview_url'] ); ?>" target="_blank"><?php esc_html_e( 'Preview', 'ocdi' ); ?></a>
                <?php endif; ?>
              </div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<div id="js-ocdi-modal-content"></div>

	<?php endif; ?>

	<p class="ocdi__ajax-loader  js-ocdi-ajax-loader">
		<span class="spinner"></span> <?php esc_html_e( 'Importing, please wait!', 'ocdi' ); ?>
	</p>

	<div class="ocdi__response  js-ocdi-ajax-response"></div>
</div>

<?php
/**
 * Hook for adding the custom admin page footer
 */
do_action( 'ocdi/plugin_page_footer' );
