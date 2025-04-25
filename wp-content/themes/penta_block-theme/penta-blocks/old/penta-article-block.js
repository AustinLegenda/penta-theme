import { InnerBlocks } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import PentaNav from './penta-nav.js';

wp.blocks.registerBlockType("pentablocktheme/penta-article-block", {
    title: "Penta Article Block",
    edit: EditComponent,
    save: () => {
        return <InnerBlocks.Content />;
    },
});

function EditComponent() {
    const post = useSelect((select) => select('core/editor').getCurrentPost()); // Get current post data
    const [categoryNames, setCategoryNames] = useState([]); // State for category names
    const [previousPost, setPreviousPost] = useState(null); // State for the previous post
    const [nextPost, setNextPost] = useState(null); // State for the next post

    useEffect(() => {
        if (post && Array.isArray(post.categories) && post.categories.length > 0) {
            // Fetch category details from REST API based on category IDs
            apiFetch({ path: `/wp/v2/categories?include=${post.categories.join(',')}` }).then((categories) => {
                const names = categories.map((category) => category.name); // Get the category names
                setCategoryNames(names); // Store them in state
            });

            // Fetch previous and next posts using the REST API
            const currentPostId = post.id;
            apiFetch({ path: `/wp/v2/posts?per_page=1&exclude=${currentPostId}&orderby=date&order=asc` })
                .then((posts) => {
                    if (posts.length > 0) {
                        setNextPost(posts[0]);
                    }
                });

            apiFetch({ path: `/wp/v2/posts?per_page=1&exclude=${currentPostId}&orderby=date&order=desc` })
                .then((posts) => {
                    if (posts.length > 0) {
                        setPreviousPost(posts[0]);
                    }
                });
        }
    }, [post]);

    return (
        <div className="main-grid">
            <PentaNav />
            <article className="main-grid-full">
                <header>
                    <div className="penta-article-group">
                        <div className="penta-article-column">
                            <h1>{post?.title?.rendered || 'Post Title'}</h1>
                            <h5>{categoryNames.length > 0 ? categoryNames.join(', ') : 'Categories'}</h5> {/* Render category names */}
                        </div>
                        <div className="penta-article-column">
                            <h3>{post?.excerpt?.rendered || 'The post excerpt will be here.'}</h3>
                        </div>
                    </div>
                </header>
                <main>
                    <InnerBlocks />
                </main>
                <div className="pagination">
                    
                        {previousPost ? (
                            <a href={previousPost.link} rel="prev">
                             <h5>  « {previousPost.title.rendered} </h5> 
                            </a>
                        ) : (
                            'No previous post'
                        )}
                    
                   
                        {nextPost ? (
                            <a href={nextPost.link} rel="next">
                                <h5> {nextPost.title.rendered} »   </h5>
                            </a>
                        ) : (
                            'No next post'
                        )}
                  
                </div>
            </article>
        </div>
    );
}

export default EditComponent;
