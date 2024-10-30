/**
 * Desktop block - Shows content only when user agent is desktop.
 */

//  Import CSS.
import './editor.scss';
import './style.scss';

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks;
const { InnerBlocks } = wp.blockEditor;

registerBlockType( 'gbmp/desktop', {

	title: __( 'Desktop content' ),
	description: 'Shows content only on Desktop.',
	icon: 'desktop',
	category: 'layout',
	parent      : ['gbmp/desktop-mobile'],
	keywords: [
		__( 'Desktop layout' ),
	],

	edit: ( props ) => {
		// Creates a <p class='wp-block-cgb-block-gbmp'></p>.
		return <div data-gbmp-type='desktop' className={'gbmp-desktop ' + props.className}>
			<InnerBlocks
				templateLock={false} className='gbmp-temp'
				renderAppender={() => (
					<InnerBlocks.ButtonBlockAppender/>
				)}
				template={[['core/paragraph', {placeholder: 'Content here only shows for desktop devices...',}]]}/>
		</div>;
	},

	save: ( props ) => {
		return (
			<div className={ 'gbmp-desktop ' + props.className }>
				<InnerBlocks.Content />
			</div>
		);
	},
} );
