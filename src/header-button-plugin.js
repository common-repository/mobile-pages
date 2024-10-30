const { registerPlugin } = wp.plugins;
const { PluginSidebar } = wp.editPost;
const {Card, CardHeader, CardBody, Button} = wp.components;

const WBMPPreviewButton = () => (
	<PluginSidebar
		title='Mobile preview'
		header={<h4>
			Mobile Preview
			<i className="dashicons dashicons-smartphone" style={{}}></i>
			<i className="dashicons dashicons-tablet" style={{margin: '-2px 0 0 -.2em',fontSize: '25px'}}></i>
		</h4>}
		icon={<span>Mobile Preview</span>}>
		<Card>
			<CardBody>
				<p>
					Please make sure you save the page before previewing.
				</p>
				<p>
				<Button isPrimary style={{justifyContent: 'center', width: '100%'}} target='_blank'
					href={gbmpMobilePages.previewUrl + '&device=iphone&p=' + wp.data.select("core/editor").getCurrentPostId() }>
					Preview on mobile</Button>
				</p>
				<p>
				<Button isPrimary style={{justifyContent: 'center', width: '100%'}} target='_blank'
					href={gbmpMobilePages.previewUrl + '&device=ipad&p=' + wp.data.select("core/editor").getCurrentPostId() }>
					Preview on tablet</Button>
				</p>
			</CardBody>
		</Card>
	</PluginSidebar>
);

registerPlugin( 'wbmp-mobile-preview-button', {
	render: WBMPPreviewButton,
} );
