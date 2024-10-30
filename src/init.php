<?php

/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */
// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class Mobile_Pages
{
    /** @var self Instance */
    private static  $_instance ;
    /** @var bool Is mobile? */
    private  $_is_mobile = null ;
    private static  $_regexps ;
    /**
     * Get regex for user agent matching
     * @param string $default
     * @return array|string[]
     */
    public static function regexps( $default = '' )
    {
        $defs = [
            'm1'  => '/(android|bb\\d+|meego).+mobile|avantgo|bada\\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',
            'm2'  => '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\\-(n|u)|c55\\/|capi|ccwa|cdm\\-|cell|chtm|cldc|cmd\\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\\-s|devi|dica|dmob|do(c|p)o|ds(12|\\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\\-|_)|g1 u|g560|gene|gf\\-5|g\\-mo|go(\\.w|od)|gr(ad|un)|haie|hcit|hd\\-(m|p|t)|hei\\-|hi(pt|ta)|hp( i|ip)|hs\\-c|ht(c(\\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\\-(20|go|ma)|i230|iac( |\\-|\\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\\/)|klon|kpt |kwc\\-|kyo(c|k)|le(no|xi)|lg( g|\\/(k|l|u)|50|54|\\-[a-w])|libw|lynx|m1\\-w|m3ga|m50\\/|ma(te|ui|xo)|mc(01|21|ca)|m\\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\\-2|po(ck|rt|se)|prox|psio|pt\\-g|qa\\-a|qc(07|12|21|32|60|\\-[2-7]|i\\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\\-|oo|p\\-)|sdk\\/|se(c(\\-|0|1)|47|mc|nd|ri)|sgh\\-|shar|sie(\\-|m)|sk\\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\\-|v\\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\\-|tdg\\-|tel(i|m)|tim\\-|t\\-mo|to(pl|sh)|ts(70|m\\-|m3|m5)|tx\\-9|up(\\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\\-|your|zeto|zte\\-/i',
            'mex' => '',
        ];
        if ( $default === 'default' ) {
            return [
                'm1'  => $defs['m1'],
                'm2'  => $defs['m2'],
                'mex' => '',
            ];
        }
        
        if ( !self::$_regexps ) {
            self::$_regexps = [
                'm1'  => get_option( 'gbmp-mob1-regex', $defs['m1'] ),
                'm2'  => get_option( 'gbmp-mob2-regex', $defs['m2'] ),
                'mex' => get_option( 'gbmp-mob-ex-regex', '' ),
            ];
            if ( !self::$_regexps['m1'] ) {
                self::$_regexps['m1'] = $defs['m1'];
            }
            if ( !self::$_regexps['m2'] ) {
                self::$_regexps['m2'] = $defs['m2'];
            }
        }
        
        return self::$_regexps;
    }
    
    /**
     * Returns instance of current calss
     * @return self Instance
     */
    public static function instance( $file = '' )
    {
        if ( !self::$_instance ) {
            self::$_instance = new self( $file );
        }
        return self::$_instance;
    }
    
    /**
     * Mobile_Pages constructor.
     * Hooks action and filters
     */
    protected function __construct( $file )
    {
        add_filter( 'plugin_action_links_' . plugin_basename( $file ), array( $this, 'plugin_links' ) );
        add_action( 'init', [ $this, 'init' ] );
        add_action( 'wp_ajax_mobile_pages_preview', [ $this, 'mobile_pages_preview' ] );
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
        $this->fs();
    }
    
    public function mobile_pages_preview()
    {
        include 'tpl-preview.php';
        die;
    }
    
    public function plugin_links( $links )
    {
        $deactivate = $links['deactivate'];
        $links['deactivate'] = '<a href="#gbmp-deactivate" id="deactivate-gutenberg-mobile-pro" aria-label="Deactivate Gutenberg Mobile Pro">Deactivate</a>';
        ?>
		<style>
			#gbmp-deactivate {
				position: fixed;
				width: 500px;
				padding: 0 16px;
				right: 50%;
				top: 34%;
				margin-right: -250px;
				background: #fff;
				box-shadow: 0 0 0 9999px rgba(0,0,0,0.7);
				display: none;
			}

			#gbmp-deactivate > header {
				margin: 0 -16px;
				padding: 16px;
				border-bottom: 1px solid #ccc;
				background: #eee;
			}

			#gbmp-deactivate > header h2 {
				margin: 0;
			}

			#gbmp-deactivate > footer {
				margin: 20px -16px 0;
				padding: 11px 16px 16px;
				border-top: 1px solid #ccc;
				text-align: right;
			}

			#gbmp-deactivate:target {
				display: block;
			}
		</style>
		<div id='gbmp-deactivate'>
			<header>
				<h2><?php 
        _e( 'Deactivating Gutenberg Mobile Pro', 'gbmp' );
        ?></h2>
			</header>

			<p>
				<?php 
        _e( 'Deactivating Gutenberg Mobile Pro will make your site display both mobile and desktop content.', 'gbmp' );
        ?>
				<?php 
        _e( 'You can change this behaviour by putting some CSS in Customizer > Custom CSS.', 'gbmp' );
        ?>
			</p>

			<dl>
				<dt><h4><?php 
        _e( 'To keep mobile content and hide desktop content, use this CSS.', 'gbmp' );
        ?></h4></dt>
				<dd>
					<pre>
.wp-block-gbmp-desktop {
	display: none;
}
					</pre>
				</dd>
				<dt><h4><?php 
        _e( 'To keep desktop content and hide mobile content, use this CSS.', 'gbmp' );
        ?></h4></dt>
				<dd>
					<pre>
.wp-block-gbmp-mobile {
	display: none;
}
					</pre>
				</dd>
			</dl>

			<p><?php 
        _e( 'When you are ready, click Deactivate below to deactivate the plugin.', 'gbmp' );
        ?></p>
			<footer>
				<?php 
        echo  $deactivate ;
        ?>
				or
				<a href="#"><?php 
        _e( 'Keep active', 'gbmp' );
        ?></a>
			</footer>
		</div>
		<?php 
        return $links;
    }
    
    public function fs()
    {
        global  $gbmp_fs ;
        
        if ( !isset( $gbmp_fs ) ) {
            // Include Freemius SDK.
            require_once 'wp-sdk/start.php';
            $gbmp_fs = fs_dynamic_init( array(
                'id'             => '7545',
                'slug'           => 'mobile-pages',
                'premium_slug'   => 'mobile-pages-pro',
                'type'           => 'plugin',
                'public_key'     => 'pk_192fb36df551f8be54719b579cac6',
                'is_premium'     => false,
                'premium_suffix' => 'Pro',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'trial'          => array(
                'days'               => 7,
                'is_require_payment' => true,
            ),
                'menu'           => array(
                'slug'    => 'mobile-pages',
                'support' => false,
                'parent'  => array(
                'slug' => 'plugins.php',
            ),
            ),
                'is_live'        => true,
            ) );
        }
        
        return $gbmp_fs;
    }
    
    function admin_menu()
    {
        add_plugins_page(
            __( 'Gutenberg Mobile', 'gbmp' ),
            __( 'Gutenberg Mobile', 'gbmp' ),
            'manage_options',
            'mobile-pages',
            function () {
            include dirname( __FILE__ ) . '/tpl-settings-page.php';
        }
        );
    }
    
    /**
     * Enqueue Gutenberg block assets for both frontend + backend.
     *
     * Assets enqueued:
     * 1. blocks.style.build.css - Frontend + Backend.
     * 2. blocks.build.js - Backend.
     * 3. blocks.editor.build.css - Backend.
     *
     * @uses {wp-blocks} for block type registration & related functions.
     * @uses {wp-element} for WP Element abstraction — structure of blocks.
     * @uses {wp-i18n} to internationalize the block's text.
     * @uses {wp-editor} for WP editor styles.
     * @since 1.0.0
     */
    function init()
    {
        // phpcs:ignore
        
        if ( isset( $_GET['mobile-pages-preview'] ) ) {
            show_admin_bar( false );
            add_filter( 'show_admin_bar', '__return_false' );
        }
        
        $dist_url = plugins_url( '/dist', dirname( __FILE__ ) );
        $src_url = plugins_url( '/src', dirname( __FILE__ ) );
        $blocks_js_deps = array(
            'wp-blocks',
            'wp-i18n',
            'wp-element',
            'wp-editor'
        );
        // Register block editor script for backend.
        wp_register_script(
            'gbmp-block-js',
            "{$dist_url}/blocks.build.js",
            $blocks_js_deps,
            null,
            true
        );
        // Register block editor styles for backend.
        wp_register_style(
            'gbmp-block-editor-css',
            "{$dist_url}/blocks.editor.build.css",
            array( 'wp-edit-blocks' ),
            null
        );
        // Register admin page styles.
        wp_register_style( 'gbmp-admin-page', "{$src_url}/admin-page.css" );
        // WP Localized globals. Use dynamic PHP stuff in JavaScript via `cgbGlobal` object.
        wp_localize_script( 'gbmp-block-js', 'gbmpMobilePages', [
            'previewUrl'   => admin_url( 'admin-ajax.php?action=mobile_pages_preview' ),
            'pluginDirUrl' => plugin_dir_url( __DIR__ ),
            'mobileFirst'  => !!get_option( 'gbmp-mobile-first', false ),
        ] );
        register_block_type( 'gbmp/mobile', array(
            'render_callback' => [ $this, 'render_mobile_content' ],
            'editor_script'   => 'gbmp-block-js',
            'editor_style'    => 'gbmp-block-editor-css',
        ) );
        register_block_type( 'gbmp/desktop', array(
            'render_callback' => [ $this, 'render_desktop_content' ],
            'editor_script'   => 'gbmp-block-js',
            'editor_style'    => 'gbmp-block-editor-css',
        ) );
    }
    
    public function is_mobile()
    {
        if ( isset( $_GET['mobile-pages-preview'] ) ) {
            return $_GET['mobile-pages-preview'] !== 'desktop';
        }
        $ua = $_SERVER['HTTP_USER_AGENT'];
        
        if ( null === $this->_is_mobile ) {
            $this->_is_mobile = preg_match( self::regexps()['m1'], $ua ) || preg_match( self::regexps()['m2'], substr( $ua, 0, 4 ) );
            if ( self::regexps()['mex'] && preg_match( self::regexps()['mex'], $ua ) ) {
                // Force mobile false is mex (mobile exclude) matches.
                $this->_is_mobile = false;
            }
        }
        
        return $this->_is_mobile;
    }
    
    function render_mobile_content( $props, $content )
    {
        if ( !$this->is_mobile() ) {
            return '';
        }
        return $content;
    }
    
    function render_desktop_content( $props, $content )
    {
        if ( $this->is_mobile() ) {
            return '';
        }
        return $content;
    }

}