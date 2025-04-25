import { useBlockProps, InspectorControls, PanelColorSettings } from '@wordpress/block-editor';
import { useState, useEffect, useRef, createElement } from '@wordpress/element';
import { Spinner, Notice, PanelBody, RangeControl, SelectControl } from '@wordpress/components';
import { QueryControls } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { debounce } from '@wordpress/compose'; 

// Helper functions to sanitize inputs
const sanitizeNumber = (num) => Number.isInteger(num) ? num : 0;
const sanitizeArray = (arr) => Array.isArray(arr) ? arr : [];
const sanitizeString = (str) => typeof str === 'string' ? str : '';

const QUERY_DEFAULTS = {
    selectedCategories: [],
    categories: {},
    numberOfItems: 5,
    order: 'desc',
    orderBy: 'date',
};

wp.blocks.registerBlockType("pentaportfoliogrid/ppg", {
    title: __("Penta Portfolio Grid", "ppg"),
    icon: "grid-view",
    category: "media",
    attributes: {
        textColor: { type: "string", default: "#222" },
        tagName: { type: 'string', default: 'h4' },
        title: { type: "string", source: "text", default: __('Default Title', 'pentaportfoliogrid') },
        paddingLeftRight: { type: "string", default: "30px" },
        paddingTop: { type: "string", default: "75px" },
        paddingBottom: { type: "string", default: "0px" },
        selectedCategories: { type: "array", default: [] },
        numberOfItems: { type: "number", default: QUERY_DEFAULTS.numberOfItems },
        order: { type: "string", default: QUERY_DEFAULTS.order },
        orderBy: { type: "string", default: QUERY_DEFAULTS.orderBy },
        objectPositions: { type: "array", default: [] },
    },
    edit: EditComponent,
    save: SaveComponent,
});

function EditComponent({ attributes, setAttributes }) {
    const { tagName, textColor, paddingLeftRight, paddingTop, paddingBottom, selectedCategories, numberOfItems, order, orderBy, objectPositions } = attributes;

    const [posts, setPosts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [categoryError, setCategoryError] = useState(null);
    const [selectedElementIndex, setSelectedElementIndex] = useState(null);
    const [objectPositionX, setObjectPositionX] = useState(50);
    const [objectPositionY, setObjectPositionY] = useState(50);

    const imageRefs = useRef([]);
    const cacheRef = useRef({}); // Cache object to store API responses

    const [query, setQuery] = useState({
        selectedCategories: sanitizeArray(selectedCategories),
        categories: {},
        numberOfItems: sanitizeNumber(numberOfItems) || QUERY_DEFAULTS.numberOfItems,
        order: sanitizeString(order) || QUERY_DEFAULTS.order,
        orderBy: sanitizeString(orderBy) || QUERY_DEFAULTS.orderBy,
    });

    // Fetch categories on mount
    useEffect(() => {
        wp.apiFetch({ path: '/wp/v2/categories' })
            .then((categoryData) => {
                const categorySuggestions = categoryData.reduce((acc, category) => {
                    acc[category.name.toLowerCase()] = category;
                    return acc;
                }, {});

                const initialSelectedCategories = selectedCategories.length ? selectedCategories : [categoryData[0]];

                setQuery((prevQuery) => ({
                    ...prevQuery,
                    categories: categorySuggestions,
                    selectedCategories: sanitizeArray(initialSelectedCategories),
                }));
            });
    }, [selectedCategories]);

    // Fetch posts based on query with caching to minimize API requests
    useEffect(() => {
        const categoryIds = query.selectedCategories.map(category => category.id);
        if (categoryIds.length === 0) {
            setPosts([]);
            setLoading(false);
            return;
        }

        const cacheKey = `${categoryIds.join(',')}-${query.numberOfItems}-${query.order}-${query.orderBy}`;
        if (cacheRef.current[cacheKey]) {
            setPosts(cacheRef.current[cacheKey]);
            setLoading(false);
        } else {
            setLoading(true);
            wp.apiFetch({
                path: `/custom/v1/all-posts?categories=${categoryIds.join(',')}&per_page=${query.numberOfItems}&orderby=${query.orderBy}&order=${query.order}&_embed`,
            })
                .then((postsResponse) => {
                    cacheRef.current[cacheKey] = postsResponse; // Cache the response
                    setPosts(postsResponse);
                    setLoading(false);
                    imageRefs.current = postsResponse.map(() => React.createRef());

                    if (objectPositions.length === 0) {
                        setAttributes({ objectPositions: postsResponse.map(() => ({ x: 50, y: 50 })) });
                    }
                })
                .catch((err) => {
                    setError(err);
                    setLoading(false);
                });
        }
    }, [query]);

    // Update attributes when the query changes
    useEffect(() => {
        setAttributes({
            selectedCategories: sanitizeArray(query.selectedCategories),
            numberOfItems: sanitizeNumber(query.numberOfItems),
            order: sanitizeString(query.order),
            orderBy: sanitizeString(query.orderBy),
        });
    }, [query, setAttributes]);

    const blockProps = useBlockProps({
        style: {
            color: textColor,
        }
    });

    if (loading) return <Spinner />;
    if (error) return <Notice status="error">{__("There was an error loading posts.", "pentablocktheme")}</Notice>;

    // Debounce query updates to reduce API requests
    const updateQuery = debounce((newQuery) => {
        setQuery((prevQuery) => ({ ...prevQuery, ...newQuery }));
    }, 300);

    const handleImageClick = (index) => {
        setSelectedElementIndex(index);
        if (objectPositions[index]) {
            setObjectPositionX(objectPositions[index].x);
            setObjectPositionY(objectPositions[index].y);
        }
    };

    const handleObjectPositionChange = (axis, value) => {
        if (selectedElementIndex !== null) {
            const updatedObjectPositions = [...objectPositions];
            if (axis === 'x') {
                setObjectPositionX(sanitizeNumber(value));
                updatedObjectPositions[selectedElementIndex] = {
                    ...updatedObjectPositions[selectedElementIndex],
                    x: sanitizeNumber(value)
                };
            } else if (axis === 'y') {
                setObjectPositionY(sanitizeNumber(value));
                updatedObjectPositions[selectedElementIndex] = {
                    ...updatedObjectPositions[selectedElementIndex],
                    y: sanitizeNumber(value)
                };
            }
            setAttributes({ objectPositions: updatedObjectPositions });
        }
    };
    
    const handleTagChange = (newTag) => setAttributes({ tagName: sanitizeString(newTag) });

    const getContainerClass = (containerIndex, itemCount, fiveItemContainersCount) => {
        switch (itemCount) {
            case 1: return "one-col";
            case 2: return "two-col";
            case 3: return "three-col";
            case 4: return "two-col";
            case 5: return fiveItemContainersCount % 2 === 1 ? "one-third-col-rev" : "one-third-col";
            default: return "";
        }
    };

    const renderFolioContainers = (posts) => {
        const containers = [];
        const itemsPerContainer = 5;
        let currentContainer = [];
        let fiveItemContainersCount = 0;

        posts.forEach((post, index) => {
            if (post.featured_img_src) {
                const objectPosition = objectPositions[index] || { x: 50, y: 50 };
                currentContainer.push(
                    <div
                        key={post.id}
                        ref={imageRefs.current[index]}
                        className="item-folio"
                        onClick={() => handleImageClick(index)}
                        style={{
                            cursor: 'pointer',
                            border: selectedElementIndex === index ? '2px solid blue' : 'none',
                        }}
                    >
                        <div className="folio-snippet">
                            <a href={post.link} className="folio-els-container">
                                <img
                                    src={sanitizeString(post.featured_img_src)}
                                    alt={sanitizeString(post.title.rendered)}
                                    loading="lazy" // Lazy load images
                                    style={{
                                        height: '100%',
                                        width: '100%',
                                        objectFit: 'cover',
                                        objectPosition: `${objectPosition.x}% ${objectPosition.y}%`,
                                    }}
                                />
                                {createElement(tagName, { style: { color: textColor, margin: '0' }}, sanitizeString(post.title) || __('Default Title', 'pentaportfoliogrid'))}
                            </a>
                        </div>
                    </div>
                );
            }

            if ((index + 1) % itemsPerContainer === 0 || index === posts.length - 1) {
                const containerClasses = getContainerClass(containers.length, currentContainer.length, fiveItemContainersCount);

                if (currentContainer.length === 5) fiveItemContainersCount++;

                containers.push(
                    <div className={`folio-container nav-toggle ${containerClasses}`} key={`container-${containers.length}`}>
                        {renderContainerItems(currentContainer, fiveItemContainersCount)}
                    </div>
                );

                currentContainer = [];
            }
        });

        return containers;
    };

    const renderContainerItems = (items, fiveItemContainersCount) => {
        return items.map((item, index) => {
            const isBig = items.length === 5 && index === 0;
            const isReverse = isBig && fiveItemContainersCount % 2 === 0;

            return React.cloneElement(item, {
                className: `item-folio ${isBig ? '--big' : ''} ${isReverse ? 'reverse' : ''}`,
            });
        });
    };

    return (
        <div {...blockProps}>
            <InspectorControls group="settings">
                <PanelBody title={__("Query Settings", "pentablocktheme")} initialOpen={true}>
                    {categoryError && <Notice status="warning">{categoryError}</Notice>}
                    <QueryControls
                        maxItems={100}
                        minItems={1}
                        numberOfItems={query.numberOfItems}
                        order={query.order}
                        orderBy={query.orderBy}
                        categorySuggestions={query.categories}
                        selectedCategories={query.selectedCategories}
                        onOrderByChange={(newOrderBy) => updateQuery({ orderBy: sanitizeString(newOrderBy) })}
                        onOrderChange={(newOrder) => updateQuery({ order: sanitizeString(newOrder) })}
                        onCategoryChange={(newCategories) => {
                            const updatedCategories = newCategories.map((cat) => typeof cat === 'string' ? query.categories[cat.toLowerCase()] : cat).filter(Boolean);
                            if (!updatedCategories.length) setCategoryError(__('Please select at least one valid category.', 'pentablocktheme'));
                            else setCategoryError(null);
                            updateQuery({ selectedCategories: sanitizeArray(updatedCategories) });
                        }}
                        onNumberOfItemsChange={(newNumberOfItems) => updateQuery({ numberOfItems: sanitizeNumber(newNumberOfItems) })}
                    />
                </PanelBody>
            </InspectorControls>
            <InspectorControls group="styles">
                <PanelBody title={__("Text Settings", "pentablocktheme")}>
                    <PanelColorSettings
                        title={__("Text Color", "pentablocktheme")}
                        initialOpen={true}
                        colorSettings={[
                            {
                                value: textColor,
                                onChange: (color) => setAttributes({ textColor: sanitizeString(color) }),
                                label: __('Text Color', 'pentablocktheme')
                            },
                        ]}
                    />
                    <SelectControl
                        label={__("Change Heading Level", "pentablocktheme")}
                        value={tagName}
                        options={[
                            { label: __('Heading 1', 'pentablocktheme'), value: 'h1' },
                            { label: __('Heading 2', 'pentablocktheme'), value: 'h2' },
                            { label: __('Heading 3', 'pentablocktheme'), value: 'h3' },
                            { label: __('Heading 4', 'pentablocktheme'), value: 'h4' },
                            { label: __('Heading 5', 'pentablocktheme'), value: 'h5' },
                            { label: __('div (p by default)', 'pentablocktheme'), value: 'div' },
                        ]}
                        onChange={handleTagChange}
                    />
                </PanelBody>
                <PanelBody title={__("Padding Settings", "pentablocktheme")} initialOpen={true}>
                    <RangeControl
                        label={__('Padding Left/Right', 'pentablocktheme')}
                        value={parseInt(paddingLeftRight)}
                        onChange={(value) => setAttributes({ paddingLeftRight: `${sanitizeNumber(value)}px` })}
                        min={0}
                        max={200}
                    />
                    <RangeControl
                        label={__('Padding Top', 'pentablocktheme')}
                        value={parseInt(paddingTop)}
                        onChange={(value) => setAttributes({ paddingTop: `${sanitizeNumber(value)}px` })}
                        min={0}
                        max={200}
                    />
                    <RangeControl
                        label={__('Padding Bottom', 'pentablocktheme')}
                        value={parseInt(paddingBottom)}
                        onChange={(value) => setAttributes({ paddingBottom: `${sanitizeNumber(value)}px` })}
                        min={0}
                        max={200}
                    />
                </PanelBody>
                <PanelBody title={__("Crop Selected Image", "pentablocktheme")} initialOpen={true}>
                    {selectedElementIndex !== null && (
                        <>
                            <p>{`${__('Editing Image', 'pentablocktheme')} #${selectedElementIndex + 1}`}</p>
                            <RangeControl
                                label={__('Horizontal Position (X)', 'pentablocktheme')}
                                value={objectPositionX}
                                onChange={(value) => handleObjectPositionChange('x', value)}
                                min={0}
                                max={100}
                            />
                            <RangeControl
                                label={__('Vertical Position (Y)', 'pentablocktheme')}
                                value={objectPositionY}
                                onChange={(value) => handleObjectPositionChange('y', value)}
                                min={0}
                                max={100}

                                
                            />
                        </>
                    )}
                </PanelBody>
            </InspectorControls>
            {posts.length === 0 ? (
                <p>{__("No posts found.", "pentablocktheme")}</p>
            ) : (
                <div className="folio-wrapper"       style={{
                    paddingLeft: paddingLeftRight,
                    paddingRight: paddingLeftRight,
                    paddingTop: paddingTop,
                    paddingBottom: paddingBottom,
                }}>
                    {renderFolioContainers(posts)}
                </div>
            )}
        </div>
    );
}

function SaveComponent() {
    return null; // Render handled on the server via PHP template
}
