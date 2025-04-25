import { useState, useEffect, useRef } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';

wp.blocks.registerBlockType("pentaportfoliogrid/penta-work-block", {
    title: __("Penta Work Block", "work by category"),
    icon: "grid-view",
    edit: EditComponent,
    save: SaveComponent,
});

function EditComponent() {
    const [categories, setCategories] = useState([]);
    const [postsByCategory, setPostsByCategory] = useState({});
    const containerRefs = useRef([]);
    const btnRefs = useRef([]);

    useEffect(() => {
        // Fetch posts with categories from the custom API
        apiFetch({ path: '/custom/v1/all-posts?parent=4' }).then((fetchedPosts) => {
            const uniqueCategories = [];
            fetchedPosts.forEach(post => {
                post.categories.forEach(cat => {
                    if (!uniqueCategories.some(category => category.id === cat.id)) {
                        uniqueCategories.push(cat);
                    }
                });
            });

            setCategories(uniqueCategories);

            const newPostsByCategory = {};
            uniqueCategories.forEach(category => {
                newPostsByCategory[category.id] = fetchedPosts.filter(post =>
                    post.categories.some(cat => cat.id === category.id)
                );
            });

            setPostsByCategory(newPostsByCategory);
        });
    }, []);

    useEffect(() => {
        if (!categories.length || !Object.keys(postsByCategory).length) return;

        containerRefs.current.forEach((container, index) => {
            if (!container) return; // Skip if ref is not assigned

            const btnLeft = btnRefs.current[index]?.left;
            const btnRight = btnRefs.current[index]?.right;

            const handleMouseEnter = () => {
                if (btnLeft) btnLeft.classList.remove("transparent");
                if (btnRight) btnRight.classList.remove("transparent");
            };

            const handleMouseLeave = () => {
                if (btnLeft) btnLeft.classList.add("transparent");
                if (btnRight) btnRight.classList.add("transparent");
            };

            const handleScrollClick = (direction) => {
                container.scrollLeft += direction === "left" ? -200 : 200;
            };

            container.addEventListener("mouseenter", handleMouseEnter);
            container.addEventListener("mouseleave", handleMouseLeave);

            if (btnLeft) {
                btnLeft.addEventListener("click", () => handleScrollClick("left"));
            }
            if (btnRight) {
                btnRight.addEventListener("click", () => handleScrollClick("right"));
            }

            // Cleanup function to remove event listeners
            return () => {
                container.removeEventListener("mouseenter", handleMouseEnter);
                container.removeEventListener("mouseleave", handleMouseLeave);
                if (btnLeft) btnLeft.removeEventListener("click", () => handleScrollClick("left"));
                if (btnRight) btnRight.removeEventListener("click", () => handleScrollClick("right"));
            };
        });
    }, [categories, postsByCategory]);

    const renderWorkContainer = () => {
        return categories.map((category, index) => {
            const posts = postsByCategory[category.id] || [];

            return (
                <div key={category.id}>
                    <h2 className="work-cat-title">{category.name}</h2>
                    <div
                        className="work-cat-container"
                        ref={(el) => (containerRefs.current[index] = el)}
                    >
                        <div className="work-feed-container">
                            {posts.length > 0 ? (
                                posts.map((post) => {
                                    const featuredImage = post.featured_img_src || 'path/to/default-image.jpg';
                                    const postTitle = post.title || 'No Title Available';

                                    return (
                                        <a key={post.id} href={post.link}>
                                            {featuredImage ? (
                                                <img
                                                    src={featuredImage}
                                                    alt={postTitle}
                                                    loading="lazy"
                                                    style={{
                                                        height: '198px',
                                                        width: '300px',
                                                        objectFit: 'cover',
                                                    }}
                                                />
                                            ) : (
                                                <div className="placeholder">No Image</div>
                                            )}
                                            <div>
                                                <h4>{postTitle}</h4>
                                            </div>
                                        </a>
                                    );
                                })
                            ) : (
                                <p>No posts available for this category.</p>
                            )}
                        </div>
                        <div
                            className="slide-left btns transparent"
                            ref={(el) => {
                                if (!btnRefs.current[index]) {
                                    btnRefs.current[index] = {};
                                }
                                btnRefs.current[index].left = el;
                            }}
                        >
                            <div className="chevron-left"></div>
                        </div>
                        <div
                            className="slide-right btns transparent"
                            ref={(el) => {
                                if (!btnRefs.current[index]) {
                                    btnRefs.current[index] = {};
                                }
                                btnRefs.current[index].right = el;
                            }}
                        >
                            <div className="chevron-right"></div>
                        </div>
                    </div>
                </div>
            );
        });
    };

    return (
        <div className="main-grid">
            <article className="main-grid-full">
                {renderWorkContainer()}
            </article>
        </div>
    );
}

function SaveComponent() {
    return null; // Render handled on the server via PHP template
}
