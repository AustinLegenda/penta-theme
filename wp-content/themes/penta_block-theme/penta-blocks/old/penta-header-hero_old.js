import { useBlockProps, InspectorControls, RichText, PanelColorSettings } from '@wordpress/block-editor';
import { useState, useEffect } from '@wordpress/element';
import { Spinner, Notice, PanelBody, SelectControl } from '@wordpress/components';
import PentaNav from '../penta-nav.js';

wp.blocks.registerBlockType("pentablocktheme/penta-header-hero", {
    title: "Penta Header Hero",
    attributes: {
        textColor: { type: "string", default: "#222" },
        title: { type: "string", source: "text", selector: "h3.title"},
        excerpt: { type: "string", source: "text", selector: "h3.tag"}, 
        gridClass: {
            type: 'string',
            default: 'grid-bottom grid-bottom-right' // Default value for gridClass
        },
    },
    edit: EditComponent,
    save: SaveComponent,

});

function EditComponent({ attributes, setAttributes }) {
    const { textColor, title, excerpt } = attributes;
    const { gridClass } = attributes; // Destructure attributes to access gridClass and textColor


    const [posts, setPosts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchPosts = wp.apiFetch({ path: '/wp/v2/posts?categories=23&per_page=10&_embed' });
        const fetchFolio = wp.apiFetch({ path: '/wp/v2/folio?categories=23&per_page=10&_embed' });

        Promise.all([fetchPosts, fetchFolio])
            .then(([postsResponse, folioResponse]) => {
                const combinedPosts = [...postsResponse, ...folioResponse];
                setPosts(combinedPosts);
                setLoading(false);
            })
            .catch((err) => {
                console.error('API fetch error:', err);
                setError(err);
                setLoading(false);
            });
    }, []);

    const blockProps = useBlockProps({
        style: { color: textColor }
       
    }); // Add block props for the main block

    if (loading) {
        return <Spinner />;
    }

    if (error) {
        return <Notice status="error" isDismissible={true}>There was an error loading posts.</Notice>;
    }

    return (
        <div {...blockProps}>
            <InspectorControls>
                <PanelBody title="Hero Text Color">
                    <PanelColorSettings
                        title="Text Color"
                        initialOpen={true}
                        colorSettings={[
                            {
                                value: textColor,
                                onChange: (color) => setAttributes({ textColor: color }),
                                label: 'Text Color'
                            },
                        ]}
                    />
                </PanelBody>
                <PanelBody title="Hero Heading Position">
                    <SelectControl
                        label="Select Grid Position"
                        value={gridClass}
                        options={[
                            { label: 'Bottom Left', value: 'grid-bottom grid-left' },
                            { label: 'Bottom Center', value: 'grid-bottom-center' },
                            { label: 'Bottom Right', value: 'grid-bottom grid-bottom-right' },
                            { label: 'Center Left', value: 'grid-center-left' },
                            { label: 'Center Center', value: 'grid-center-center' },
                            { label: 'Center Right', value: 'grid-center-right' },
                        ]}
                        onChange={(newGridClass) => setAttributes({ gridClass: newGridClass })} // Update gridClass attribute

                    />
                </PanelBody>
            </InspectorControls>

            {posts.length === 0 ? (
                <p>No posts found.</p>
            ) : (
                posts.map((post) => (
                    <div key={post.id}>
                        <div className="wrapper" id="PentaHeader">
                            <div className="header-container main-grid" style={{ color: textColor }}>
                                <PentaNav />
                                <div className={`tag-and-title ${gridClass}`} id="TagAndTitle">
                                    <a href="#" >
                                        <RichText
                                            tagName="h3"
                                            className="title"
                                            value={title || post.title.rendered || 'Default Title'}
                                            onChange={(newText) => setAttributes({ title: newText })}
                                            placeholder="Add title here"
                                        />
                                        <RichText
                                            tagName="h3"
                                            className="tag"
                                            value={excerpt || post.excerpt.rendered || 'Tag Here'}
                                            onChange={(newText) => setAttributes({ excerpt: newText })}
                                            placeholder="Add excerpt here"
                                        />
                                    </a>
                                </div>
                                {/* HERO IMAGE */}
                                {post._embedded && post._embedded['wp:featuredmedia'] && (
                                    <a href="#" className="main-grid-bleed">
                                        <img
                                            src={post._embedded['wp:featuredmedia'][0].source_url}
                                            alt={post.title.rendered}
                                            style={{ height: '100vh', width: '100%', objectFit: 'cover' }}
                                        />
                                    </a>
                                )}
                            </div>
                        </div>
                    </div>
                ))
            )}
        </div>
    );
}

function SaveComponent() {
    return null; // Using PHP template for rendering
}