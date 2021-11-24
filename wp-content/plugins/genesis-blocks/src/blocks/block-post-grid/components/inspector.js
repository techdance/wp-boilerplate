/**
 * Inspector Controls
 */

// Setup the block
const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;

import compact from 'lodash/compact';
import map from 'lodash/map';
import RenderSettingControl from '../../../utils/components/settings/renderSettingControl';
import Select from 'react-select';

// Import block components
const { InspectorControls } = wp.blockEditor;

// Import Inspector components
const {
	PanelBody,
	QueryControls,
	RangeControl,
	SelectControl,
	TextControl,
	ToggleControl,
} = wp.components;

const { addQueryArgs } = wp.url;

const { apiFetch } = wp;

const MAX_POSTS_COLUMNS = 4;

/**
 * Create an Inspector Controls wrapper Component
 */
export default class Inspector extends Component {
	constructor() {
		super( ...arguments );
		this.state = { categoriesList: [] };
	}

	componentDidMount() {
		this.stillMounted = true;
		this.fetchRequest = apiFetch( {
			path: addQueryArgs( '/wp/v2/categories', { per_page: -1 } ),
		} )
			.then( ( categoriesList ) => {
				if ( this.stillMounted ) {
					this.setState( { categoriesList } );
				}
			} )
			.catch( () => {
				if ( this.stillMounted ) {
					this.setState( { categoriesList: [] } );
				}
			} );
		
		// If we are showing pages, and the posts_per_page value is different than the number of pages chosen, set it to match. 
		if ( this.props.attributes.postType === 'page' && this.props.attributes.selectedPages.length !==  this.props.attributes.selectedPages.postsToShow ) {
			this.props.setAttributes( {
				postsToShow: this.props.attributes.selectedPages.length
			} );	
		}
	}

	componentWillUnmount() {
		this.stillMounted = false;
	}

	/* Get the available image sizes */
	imageSizeSelect() {
		const getSettings = wp.data.select( 'core/editor' ).getEditorSettings();

		return compact(
			map( getSettings.imageSizes, ( { name, slug } ) => {
				return {
					value: slug,
					label: name,
				};
			} )
		);
	}

	/* Get the page list */
	pageSelect() {
		const getPages = wp.data.select( 'core' ).getEntityRecords( 'postType', 'page', { per_page: -1 } )

		return compact( map( getPages, ({ id, title }) => {
			return {
				value: id,
				label: title.raw
			};
		}) );
	}

	render() {
		// Setup the attributes
		const { attributes, setAttributes, latestPosts } = this.props;

		const { order, orderBy } = attributes;

		const { categoriesList } = this.state;

		// Post type options
		const postTypeOptions = [
			{ value: 'post', label: __( 'Post', 'genesis-blocks' ) },
			{ value: 'page', label: __( 'Page', 'genesis-blocks' ) },
		];

		// Section title tags
		const sectionTags = [
			{ value: 'div', label: __( 'div', 'genesis-blocks' ) },
			{ value: 'header', label: __( 'header', 'genesis-blocks' ) },
			{ value: 'section', label: __( 'section', 'genesis-blocks' ) },
			{ value: 'article', label: __( 'article', 'genesis-blocks' ) },
			{ value: 'main', label: __( 'main', 'genesis-blocks' ) },
			{ value: 'aside', label: __( 'aside', 'genesis-blocks' ) },
			{ value: 'footer', label: __( 'footer', 'genesis-blocks' ) },
		];

		// Section title tags
		const sectionTitleTags = [
			{ value: 'h2', label: __( 'H2', 'genesis-blocks' ) },
			{ value: 'h3', label: __( 'H3', 'genesis-blocks' ) },
			{ value: 'h4', label: __( 'H4', 'genesis-blocks' ) },
			{ value: 'h5', label: __( 'H5', 'genesis-blocks' ) },
			{ value: 'h6', label: __( 'H6', 'genesis-blocks' ) },
		];

		// Check for posts
		const hasPosts = Array.isArray( latestPosts ) && latestPosts.length;

		// Check the post type
		const isPost = 'post' === attributes.postType;

		// Add instruction text to the select
		const abImageSizeSelect = {
			value: 'selectimage',
			label: __( 'Select image size', 'genesis-blocks' ),
		};

		// Add the landscape image size to the select
		const abImageSizeLandscape = {
			value: 'gb-block-post-grid-landscape',
			label: __( 'GB Grid Landscape', 'genesis-blocks' ),
		};

		// Add the square image size to the select
		const abImageSizeSquare = {
			value: 'gb-block-post-grid-square',
			label: __( 'GB Grid Square', 'genesis-blocks' ),
		};

		// Get the image size options
		const imageSizeOptions = this.imageSizeSelect();

		// Combine the objects
		imageSizeOptions.push( abImageSizeSquare, abImageSizeLandscape );
		imageSizeOptions.unshift( abImageSizeSelect );

		const imageSizeValue = () => {
			for ( let i = 0; i < imageSizeOptions.length; i++ ) {
				if ( imageSizeOptions[ i ].value === attributes.imageSize ) {
					return attributes.imageSize;
				}
			}
			return 'full';
		};

		// Setup the page select options
		const pageOptions = this.pageSelect();

		return (
			<InspectorControls>
				<PanelBody
					title={ __(
						'Post and Page Grid Settings',
						'genesis-blocks'
					) }
					className={ isPost ? null : 'genesis-blocks-hide-query' }
				>
					<RenderSettingControl id="gb_postgrid_postType">
						<SelectControl
							label={ __( 'Content Type', 'genesis-blocks' ) }
							options={ postTypeOptions }
							value={ attributes.postType }
							onChange={ ( value ) =>
								this.props.setAttributes( { postType: value } )
							}
						/>
					</RenderSettingControl>
					{ 'page' === attributes.postType &&
					<RenderSettingControl id="gb_postgrid_selectedPages">
						<div className="components-base-control select2-page">
							<div className="components-base-control__field">
								<label className="components-base-control__label" htmlFor="inspector-select-control">{ __( 'Pages To Show', 'genesis-blocks') }</label>
								<Select
									options={ pageOptions }
									value={ attributes.selectedPages }
									onChange={ ( value ) => {
										this.props.setAttributes( {
											selectedPages: value,
											postsToShow: value.length 
										} );
									}}
									isMulti={ true }
									closeMenuOnSelect={ false }
								/>
							</div>
						</div>
					</RenderSettingControl>
					}
					{ 'post' === attributes.postType &&
					<Fragment>
						<RenderSettingControl id="gb_postgrid_queryControls">
							<QueryControls
								{ ...{ order, orderBy } }
								numberOfItems={ attributes.postsToShow }
								categoriesList={ categoriesList }
								selectedCategoryId={ attributes.categories }
								onOrderChange={ ( value ) => setAttributes({ order: value }) }
								onOrderByChange={ ( value ) => setAttributes({ orderBy: value }) }
								onCategoryChange={ ( value ) => setAttributes({ categories: '' !== value ? value : undefined }) }
								onNumberOfItemsChange={ ( value ) => setAttributes({ postsToShow: value }) }
							/>
						</RenderSettingControl>
						<RenderSettingControl id="gb_postgrid_offset">
							<RangeControl
								label={ __( 'Number of items to offset', 'genesis-blocks' ) }
								value={ attributes.offset }
								onChange={ ( value ) => setAttributes({ offset: value }) }
								min={ 0 }
								max={ 20 }
							/>
						</RenderSettingControl>
					</Fragment>
					}
					{ 'grid' === attributes.postLayout && (
						<RenderSettingControl id="gb_postgrid_columns">
							<RangeControl
								label={ __( 'Columns', 'genesis-blocks' ) }
								value={ attributes.columns }
								onChange={ ( value ) =>
									setAttributes( { columns: value } )
								}
								min={ 1 }
								max={
									! hasPosts
										? MAX_POSTS_COLUMNS
										: Math.min(
												MAX_POSTS_COLUMNS,
												latestPosts.length
										  )
								}
							/>
						</RenderSettingControl>
					) }
				</PanelBody>
				<PanelBody
					title={ __(
						'Post and Page Grid Content',
						'genesis-blocks'
					) }
					initialOpen={ false }
				>
					<RenderSettingControl id="gb_postgrid_displaySectionTitle">
						<ToggleControl
							label={ __(
								'Display Section Title',
								'genesis-blocks'
							) }
							checked={ attributes.displaySectionTitle }
							onChange={ () =>
								this.props.setAttributes( {
									displaySectionTitle: ! attributes.displaySectionTitle,
								} )
							}
						/>
					</RenderSettingControl>
					{ attributes.displaySectionTitle && (
						<RenderSettingControl id="gb_postgrid_sectionTitle">
							<TextControl
								label={ __( 'Section Title', 'genesis-blocks' ) }
								type="text"
								value={ attributes.sectionTitle }
								onChange={ ( value ) =>
									this.props.setAttributes( {
										sectionTitle: value,
									} )
								}
							/>
						</RenderSettingControl>
					) }
					<RenderSettingControl id="gb_postgrid_displayPostImage">
						<ToggleControl
							label={ __(
								'Display Featured Image',
								'genesis-blocks'
							) }
							checked={ attributes.displayPostImage }
							onChange={ () =>
								this.props.setAttributes( {
									displayPostImage: ! attributes.displayPostImage,
								} )
							}
						/>
					</RenderSettingControl>
					{ attributes.displayPostImage && (
						<RenderSettingControl id="gb_postgrid_imageSizeValue">
							<SelectControl
								label={ __( 'Image Size', 'genesis-blocks' ) }
								value={ imageSizeValue() }
								options={ imageSizeOptions }
								onChange={ ( value ) =>
									this.props.setAttributes( {
										imageSize: value,
									} )
								}
							/>
						</RenderSettingControl>
					) }
					<RenderSettingControl id="gb_postgrid_displayPostTitle">
						<ToggleControl
							label={ __( 'Display Title', 'genesis-blocks' ) }
							checked={ attributes.displayPostTitle }
							onChange={ () =>
								this.props.setAttributes( {
									displayPostTitle: ! attributes.displayPostTitle,
								} )
							}
						/>
					</RenderSettingControl>
					{ isPost && (
						<RenderSettingControl id="gb_postgrid_displayPostAuthor">
							<ToggleControl
								label={ __(
									'Display Author',
									'genesis-blocks'
								) }
								checked={ attributes.displayPostAuthor }
								onChange={ () =>
									this.props.setAttributes( {
										displayPostAuthor: ! attributes.displayPostAuthor,
									} )
								}
							/>
						</RenderSettingControl>
					) }
					{ isPost && (
						<RenderSettingControl id="gb_postgrid_displayPostDate">
							<ToggleControl
								label={ __( 'Display Date', 'genesis-blocks' ) }
								checked={ attributes.displayPostDate }
								onChange={ () =>
									this.props.setAttributes( {
										displayPostDate: ! attributes.displayPostDate,
									} )
								}
							/>
						</RenderSettingControl>
					) }
					<RenderSettingControl id="gb_postgrid_displayPostExcerpt">
						<ToggleControl
							label={ __( 'Display Excerpt', 'genesis-blocks' ) }
							checked={ attributes.displayPostExcerpt }
							onChange={ () =>
								this.props.setAttributes( {
									displayPostExcerpt: ! attributes.displayPostExcerpt,
								} )
							}
						/>
					</RenderSettingControl>
					{ attributes.displayPostExcerpt && (
						<RenderSettingControl id="gb_postgrid_excerptLength">
							<RangeControl
								label={ __(
									'Excerpt Length',
									'genesis-blocks'
								) }
								value={ attributes.excerptLength }
								onChange={ ( value ) =>
									setAttributes( { excerptLength: value } )
								}
								min={ 0 }
								max={ 150 }
							/>
						</RenderSettingControl>
					) }
					<RenderSettingControl id="gb_postgrid_displayPostLink">
						<ToggleControl
							label={ __(
								'Display Continue Reading Link',
								'genesis-blocks'
							) }
							checked={ attributes.displayPostLink }
							onChange={ () =>
								this.props.setAttributes( {
									displayPostLink: ! attributes.displayPostLink,
								} )
							}
						/>
					</RenderSettingControl>
					{ attributes.displayPostLink && (
						<RenderSettingControl id="gb_postgrid_readMoreText">
							<TextControl
								label={ __(
									'Customize Continue Reading Text',
									'genesis-blocks'
								) }
								type="text"
								value={ attributes.readMoreText }
								onChange={ ( value ) =>
									this.props.setAttributes( {
										readMoreText: value,
									} )
								}
							/>
						</RenderSettingControl>
					) }
				</PanelBody>
				<PanelBody
					title={ __( 'Post and Page Grid Markup', 'genesis-blocks' ) }
					initialOpen={ false }
					className="gb-block-post-grid-markup-settings"
				>
					<RenderSettingControl id="gb_postgrid_sectionTag">
						<SelectControl
							label={ __(
								'Post Grid Section Tag',
								'genesis-blocks'
							) }
							options={ sectionTags }
							value={ attributes.sectionTag }
							onChange={ ( value ) =>
								this.props.setAttributes( {
									sectionTag: value,
								} )
							}
							help={ __(
								'Change the post grid section tag to match your content hierarchy.',
								'genesis-blocks'
							) }
						/>
					</RenderSettingControl>
					{ attributes.sectionTitle && (
						<RenderSettingControl id="gb_postgrid_sectionTitleTag">
							<SelectControl
								label={ __(
									'Section Title Heading Tag',
									'genesis-blocks'
								) }
								options={ sectionTitleTags }
								value={ attributes.sectionTitleTag }
								onChange={ ( value ) =>
									this.props.setAttributes( {
										sectionTitleTag: value,
									} )
								}
								help={ __(
									'Change the post/page section title tag to match your content hierarchy.',
									'genesis-blocks'
								) }
							/>
						</RenderSettingControl>
					) }
					{ attributes.displayPostTitle && (
						<RenderSettingControl id="gb_postgrid_postTitleTag">
							<SelectControl
								label={ __(
									'Post Title Heading Tag',
									'genesis-blocks'
								) }
								options={ sectionTitleTags }
								value={ attributes.postTitleTag }
								onChange={ ( value ) =>
									this.props.setAttributes( {
										postTitleTag: value,
									} )
								}
								help={ __(
									'Change the post/page title tag to match your content hierarchy.',
									'genesis-blocks'
								) }
							/>
						</RenderSettingControl>
					) }
				</PanelBody>
			</InspectorControls>
		);
	}
}
