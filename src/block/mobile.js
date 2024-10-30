/**
 * Mobile block - Shows content only when user agent is mobile.
 */

//  Import CSS.
import './editor.scss';
import './style.scss';

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks;
const { InnerBlocks } = wp.blockEditor;

registerBlockType( 'gbmp/mobile', {

	title: __( 'Mobile content' ),
	description: 'Shows content only on Mobile.',
	icon: 'smartphone',
	category: 'layout',
	parent      : ['gbmp/desktop-mobile'],
	keywords: [
		__( 'Mobile layout' ),
		__( 'iPhone' ),
		__( 'iPad' ),
	],

	edit: ( props ) => {
		// Creates a <p class='wp-block-cgb-block-gbmp'></p>.
		return <div data-gbmp-type='mobile' className={'gbmp-mobile ' + props.className}>
			<InnerBlocks
				templateLock={false} className='gbmp-temp'
				renderAppender={() => (
					<InnerBlocks.ButtonBlockAppender/>
				)}
				template={[['core/paragraph', {placeholder: 'Content here only shows for mobile devices...',}]]}/>
		</div>;
	},

	save: ( props ) => {
		return (
			<div className={ 'gbmp-mobile ' + props.className }>
				<InnerBlocks.Content />
			</div>
		);
	},
} );
