import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';
import CatMenuOne from './penta-cat-menu-1';

wp.blocks.registerBlockType('pentablocktheme/penta-blog-block', {
    title: 'Penta Blog Block',
    icon: 'grid-view',
    edit: EditComponent,
    save: SaveComponent,
});

function EditComponent( props ) {
    const { attributes } = props;
    const { menuLocation } = attributes;
    const [posts, setPosts] = useState([]);
    const [selectedCategory, setSelectedCategory] = useState(null);
    const [categoryIds, setCategoryIds] = useState([]);

    // Fetch posts whenever selectedCategory or categoryIds change
    useEffect(() => {
        if (categoryIds.length === 0 && selectedCategory === null) {
            return;
        }

        const categoriesParam = selectedCategory !== null
            ? selectedCategory
            : categoryIds.join(',');

        apiFetch({
            path: `/wp/v2/posts?categories=${categoriesParam}&per_page=20&_embed`,
        })
            .then(setPosts)
            .catch(() => setPosts([]));
    }, [selectedCategory, categoryIds]);

    // Called once when CatMenuOne loads the menu
    const handleMenuLoad = (ids) => {
        setCategoryIds(ids);
    };

    // Called when a menu item is clicked
    const handleCategorySelect = (catId) => {
        setSelectedCategory(catId);
    };

    const renderPostContainer = () =>
        posts.map((post) => {
            const featuredImage = post._embedded?.['wp:featuredmedia']?.[0]?.source_url;
            return (
                <div key={post.id} className="post-item">
                    <a href={post.link}>
                        <div className="post-img-container">
                            {featuredImage ? (
                                <img
                                    src={featuredImage}
                                    alt={post.title.rendered}
                                    loading="lazy"
                                    style={{
                                        height: '100%',
                                        width: '100%',
                                        objectFit: 'cover',
                                    }}
                                />
                            ) : (
                                <div className="placeholder">No Image</div>
                            )}
                        </div>
                        <div className="post-intro">
                            <h3>{post.title.rendered}</h3>
                            <div
                                dangerouslySetInnerHTML={{ __html: post.excerpt.rendered }}
                            />
                        </div>
                    </a>
                </div>
            );
        });

    return (
        <>
            <CatMenuOne
                menuLocation={ menuLocation }
                onMenuLoad={ handleMenuLoad }
                onCategorySelect={ handleCategorySelect }
            />

            <div className="main-grid">
                <article className="post-container main-grid-full">
                    {renderPostContainer()}
                </article>
            </div>
        </>
    );
}

function SaveComponent() {
    return null; // Rendered on the server via PHP
}

export default EditComponent;
