/**
 * BLOCK: gbmp
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './editor.scss';
import './style.scss';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { InnerBlocks } = wp.blockEditor;
const { useState, Fragment } = wp.element;
const { select } = wp.data;

function moveBlocksInside( wrappingBlockID, parentID ) {
	// Get all blocks
	const allBlocks = wp.data.select( "core/block-editor" ).getBlockOrder().filter( id => id !== parentID );

	// Move all blocks inside wrappingBlockID
	wp.data.dispatch( "core/block-editor" ).moveBlocksToPosition( allBlocks , '', wrappingBlockID );
}

function copyFromOneBlockToOther( sourceBlock, targetBlock ) {
	const sourceBlocks = wp.data.select( "core/block-editor" ).getBlockOrder( sourceBlock );
	wp.data.dispatch( "core/block-editor" ).duplicateBlocks( sourceBlocks )
		.then( blocks => {
			wp.data.dispatch( "core/block-editor" ).moveBlocksToPosition( blocks, sourceBlock, targetBlock );
		} );
}

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType( 'gbmp/desktop-mobile', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title      : __( 'Gutenberg Mobile' ), // Block title.
	description: <div>Shows content only on Mobile devices. Check out <a href='https://www.pootlepress.com/mobile-pages-pro'>Mobile Pages Pro</a> for more flexible layouts.</div>,
	icon       : 'welcome-view-site', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category   : 'layout', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords   : [
		__( 'Mobile layout' ),
		__( 'Tablet Layout' ),
		__( 'Desktop Layout' ),
		__( 'iPhone' ),
		__( 'iPad' ),
	],

	edit: ( props ) => {
		let activeTabDefault = 'desktop';

		if ( gbmpMobilePages.mobileFirst ) {
			activeTabDefault = 'mobile';
			blockSeq = [ 'mobile', 'desktop', ];
		}

		const [ tabActive, tabToggle ] = useState( activeTabDefault );
		const [ desktopId, mobId ] = select("core/block-editor").getBlockOrder( props.clientId )
		let blockSeq = [ 'desktop', 'mobile', ];
		const innerBlkProps = {
			template    : [
				["gbmp/desktop"],
				["gbmp/mobile"]
			],
			templateLock: 'all',
		};

		window.props = props;
		window.desktopId = desktopId;
		window.mobId = mobId;
		return (
			<div data-gbmp-type='desktop-mobile-split' data-gbmp-active={tabActive} className={'gbmp-split ' + props.className}>
				<div className="components-panel__header interface-complementary-area-header edit-post-sidebar__panel-tabs">
					{gbmpSplitBlockTabs( {tabActive, tabToggle, activeTabDefault} )}
					<a href={gbmpMobilePages.previewUrl + '&p=' + wp.data.select("core/editor").getCurrentPostId() }
						 target='_blank' className="components-button is-pressed ml1e">
						Preview mobile
					</a>
				</div>
				<InnerBlocks {...innerBlkProps} />
			</div>
		);
	},

	save: ( props ) => {
		return (
			<div className={props.className}>
				<div className="gbmp-mobile-desktop">
					<InnerBlocks.Content/>
				</div>
			</div>
		);
	},
} );

function gbmpSplitBlockTabs( {tabToggle, tabActive, activeTabDefault} ) {
	const tabBtnBaseClass = "components-button edit-post-sidebar__panel-tab ";
	const toggle = tab => e => tabToggle( tab );

	const tabLabels = {
		desktop: 'Desktop',
		mobile: 'Mobile',
	}

	const inactiveTabDefault = activeTabDefault === 'mobile' ? 'desktop' : 'mobile';

	const tabLabel = tab => tabLabels[tab] || tab;

	const renderTab = tab => <li>
			<button type="button" aria-label={tabLabel( tab )} data-label={tabLabel( tab ) + " content"} onClick={toggle( tab )}
							className={tabBtnBaseClass + (tabActive === tab ? 'is-active' : '')}>
				{tabLabel( tab ) + " content"}
			</button>
		</li>;

	return <Fragment>
		<ul className="gbmp-tabs">
			{renderTab( activeTabDefault )}
			{renderTab( inactiveTabDefault )}
		</ul>
		{ activeTabDefault === 'desktop' && <Fragment>
			{tabActive === 'desktop' &&
			 <button type="button" aria-disabled="false" onClick={e => moveBlocksInside( desktopId, props.clientId )}
							 className="components-button is-secondary ml-auto">
				 Import page content
			 </button>}
			{tabActive === 'mobile' &&
			 <button type="button" aria-disabled="false" onClick={e => copyFromOneBlockToOther( desktopId, mobId )}
							 className="components-button is-secondary ml-auto">
				 Copy from Desktop content
			 </button>}
		</Fragment>}

		{ activeTabDefault === 'mobile' && <Fragment>
			{tabActive === 'mobile' &&
			 <button type="button" aria-disabled="false" onClick={e => moveBlocksInside( mobId, props.clientId )}
							 className="components-button is-secondary ml-auto">
				 Import page content
			 </button>}
			{tabActive === 'desktop' &&
			 <button type="button" aria-disabled="false" onClick={e => copyFromOneBlockToOther( mobId, desktopId )}
							 className="components-button is-secondary ml-auto">
				 Copy from Mobile content
			 </button>}
		</Fragment>}
	</Fragment>;
}
