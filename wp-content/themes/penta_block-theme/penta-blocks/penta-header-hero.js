import {
    useBlockProps,
    InspectorControls,
    RichText,
    PanelColorSettings,
    MediaUpload,
    MediaUploadCheck,
    URLInputButton,
} from '@wordpress/block-editor';
import { PanelBody, PanelRow, SelectControl, Button } from '@wordpress/components';
import PentaNav from './penta-nav.js';

wp.blocks.registerBlockType('pentablocktheme/penta-header-hero', {
    title: 'Penta Header Hero',
    attributes: {
        textColor: { type: 'string', default: '#222' },
        title: { type: 'string', default: '' },          // no source/selector
        excerpt: { type: 'string', default: '' },          // no source/selector
        gridClass: { type: 'string', default: 'grid-bottom grid-bottom-right' },
        heroImage: { type: 'object', default: null },
        linkURL: { type: 'string', default: '' },
    },
    edit: EditComponent,
    save: SaveComponent
});

function EditComponent({ attributes, setAttributes }) {
    // Destructure ALL attributes at once:
    const { textColor, title, excerpt, gridClass, heroImage, linkURL } = attributes;

    const blockProps = useBlockProps({
        style: { color: textColor },
    });

    return (
        <div {...blockProps}>
            <InspectorControls>
                <PanelBody title="Text & Layout" initialOpen={true}>
                    <PanelColorSettings
                        title="Text Color"
                        initialOpen={false}
                        colorSettings={[
                            {
                                value: textColor,
                                onChange: (color) => setAttributes({ textColor: color }),
                                label: 'Text Color',
                            },
                        ]}
                    />

                    <SelectControl
                        label="Grid Position"
                        value={gridClass}
                        options={[
                            { label: 'Bottom Left', value: 'grid-bottom grid-left' },
                            { label: 'Bottom Center', value: 'grid-bottom-center' },
                            { label: 'Bottom Right', value: 'grid-bottom grid-bottom-right' },
                            { label: 'Center Left', value: 'grid-center-left' },
                            { label: 'Center Center', value: 'grid-center-center' },
                            { label: 'Center Right', value: 'grid-center-right' },
                        ]}
                        onChange={(newGrid) => setAttributes({ gridClass: newGrid })}
                    />
                </PanelBody>

                <PanelBody
                    title="Hero Image"
                    initialOpen={ ! heroImage }    // auto‑open only when no image
                    className={ heroImage ? 'has-image' : '' }
                >
                    { heroImage ? (
                        <>
                            <PanelRow>
                                <img
                                    src={ heroImage.url }
                                    alt={ heroImage.alt || '' }
                                    style={ {
                                        width:  '100%',
                                        height: 'auto',
                                        display: 'block',
                                        marginBottom: '8px',
                                    } }
                                />
                            </PanelRow>
                            <PanelRow>
                                <Button
                                    isSecondary
                                    onClick={ () =>
                                        setAttributes( { heroImage: null } )
                                    }
                                >
                                    Remove Image
                                </Button>
                                <MediaUploadCheck>
                                    <MediaUpload
                                        onSelect={ ( media ) =>
                                            setAttributes( {
                                                heroImage: {
                                                    id:  media.id,
                                                    url: media.url,
                                                    alt: media.alt,
                                                },
                                            } )
                                        }
                                        allowedTypes={ [ 'image' ] }
                                        value={ heroImage.id }
                                        render={ ( { open } ) => (
                                            <Button
                                                style={ { marginLeft: '8px' } }
                                                isSecondary
                                                onClick={ open }
                                            >
                                                Replace Image
                                            </Button>
                                        ) }
                                    />
                                </MediaUploadCheck>
                            </PanelRow>
                        </>
                    ) : (
                        <PanelRow>
                            <MediaUploadCheck>
                                <MediaUpload
                                    onSelect={ ( media ) =>
                                        setAttributes( {
                                            heroImage: {
                                                id:  media.id,
                                                url: media.url,
                                                alt: media.alt,
                                            },
                                        } )
                                    }
                                    allowedTypes={ [ 'image' ] }
                                    render={ ( { open } ) => (
                                        <Button onClick={ open } isSecondary>
                                            Upload/Select Image
                                        </Button>
                                    ) }
                                />
                            </MediaUploadCheck>
                        </PanelRow>
                    ) }
                </PanelBody>                <PanelBody title="Link Settings" initialOpen={true}>
                    <URLInputButton
                        label="Hero Link URL"
                        url={linkURL}
                        onChange={(newUrl) => setAttributes({ linkURL: newUrl })}
                    />
                </PanelBody>
            </InspectorControls>

            <div className="wrapper" id="PentaHeader">
                <div className="header-container main-grid" style={{ color: textColor }}>
                    <PentaNav />
                    <div className={`tag-and-title ${gridClass}`} id="TagAndTitle">
                        <RichText
                            tagName="h3"
                            className="title"
                            value={title || ''}
                            onChange={(newText) => setAttributes({ title: newText })}
                            placeholder="Add Title Here"
                        />
                        <RichText
                            tagName="h3"
                            className="tag"
                            value={excerpt || ''}
                            onChange={(newText) => setAttributes({ excerpt: newText })}
                            placeholder="Add excerpt here"
                        />
                    </div>

                    {heroImage && (
                        <a href={attributes.linkURL || '#' } target="_blank" className="main-grid-bleed">
                            <img
                                src={heroImage.url}
                                alt={heroImage.alt || title}
                                style={{
                                    height: '100vh',
                                    width: '100%',
                                    objectFit: 'cover',
                                }}
                            />
                        </a>
                    )}
                </div>
            </div>
        </div>
    );
}

function SaveComponent() {
    return null;
}
