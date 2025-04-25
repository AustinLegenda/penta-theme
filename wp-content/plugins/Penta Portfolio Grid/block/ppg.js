import { useBlockProps, InspectorControls, PanelColorSettings } from '@wordpress/block-editor';
import { useState, useEffect, useRef, createElement } from '@wordpress/element';
import { Spinner, Notice, PanelBody, RangeControl, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import PPGmenu from './ppg-menu';
import apiFetch from '@wordpress/api-fetch';


// Helper functions
const sanitizeNumber = (num) => Number.isInteger(num) ? num : 0;
const sanitizeArray = (arr) => Array.isArray(arr) ? arr : [];
const sanitizeString = (str) => typeof str === 'string' ? str : '';

wp.blocks.registerBlockType('pentaportfoliogrid/ppg', {
  title: __('Penta Portfolio Grid', 'ppg'),
  icon: 'grid-view',
  category: 'media',
  attributes: {
    textColor: { type: 'string', default: '#222' },
    tagName: { type: 'string', default: 'h4' },
    title: { type: 'string', source: 'text', default: __('Default Title', 'pentaportfoliogrid') },
    paddingLeftRight: { type: 'string', default: '35px' },
    paddingTop: { type: 'string', default: '30px' },
    paddingBottom: { type: 'string', default: '0px' },
    selectedCategories: { type: 'array', default: [] },
    numberOfItems: { type: 'number', default: 5 },
    order: { type: 'string', default: 'desc' },
    orderBy: { type: 'string', default: 'date' },
    objectPositions: { type: 'array', default: [] },
  },
  edit: EditComponent,
  save: () => null,
});

function EditComponent({ attributes, setAttributes }) {
  const {
    tagName, textColor,
    paddingLeftRight, paddingTop, paddingBottom,
    objectPositions,
  } = attributes;

  const [posts, setPosts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [categoryIds, setCategoryIds] = useState([]);
  const [selectedCategory, setSelectedCategory] = useState(null);

  const [selectedElementIndex, setSelectedElementIndex] = useState(null);
  const [objectPositionX, setObjectPositionX] = useState(50);
  const [objectPositionY, setObjectPositionY] = useState(50);

  const imageRefs = useRef([]);
  const cacheRef = useRef({});

  // Fetch posts when categoryIds or selectedCategory change
  useEffect(() => {
    let categoriesParam = '';
    if (selectedCategory !== null) {
      categoriesParam = selectedCategory;
    } else if (categoryIds.length) {
      categoriesParam = categoryIds.join(',');
    } else {
      // no filter => empty grid
      setPosts([]);
      setLoading(false);
      return;
    }

    const base = `/custom/v1/all-posts?categories=${categoriesParam}` +
      `&per_page=-1` +
      `&orderby=${attributes.orderBy}` +
      `&order=${attributes.order}` +
      `&_embed`;;
    const key = `${categoriesParam}-${attributes.numberOfItems}-${attributes.order}-${attributes.orderBy}`;

    if (cacheRef.current[key]) {
      setPosts(cacheRef.current[key]);
      setLoading(false);
      return;
    }

    setLoading(true);
    apiFetch({ path: base })
      .then((resp) => {
        cacheRef.current[key] = resp;
        setPosts(resp);
        setLoading(false);
        imageRefs.current = resp.map(() => createElement('div'));
        if (!objectPositions.length) {
          setAttributes({ objectPositions: resp.map(() => ({ x: 50, y: 50 })) });
        }
      })
      .catch((err) => {
        setError(err);
        setLoading(false);
      });
  }, [categoryIds, selectedCategory, attributes.numberOfItems, attributes.orderBy, attributes.order]);

  const blockProps = useBlockProps({ style: { color: textColor } });

  if (loading) return <Spinner />;
  if (error) return <Notice status="error">{__('There was an error loading posts.', 'pentablocktheme')}</Notice>;

  const handleMenuLoad = (ids) => setCategoryIds(ids);
  const handleCategorySelect = (id) => setSelectedCategory(id);

  const handleImageClick = (i) => {
    setSelectedElementIndex(i);
    const pos = objectPositions[i] || { x: 50, y: 50 };
    setObjectPositionX(pos.x);
    setObjectPositionY(pos.y);
  };

  const handleObjectPositionChange = (axis, v) => {
    if (selectedElementIndex === null) return;
    const num = sanitizeNumber(v);
    const updated = [...objectPositions];
    updated[selectedElementIndex] = { ...updated[selectedElementIndex], [axis]: num };
    setObjectPositionX(updated[selectedElementIndex].x);
    setObjectPositionY(updated[selectedElementIndex].y);
    setAttributes({ objectPositions: updated });
  };

  const getContainerClass = (idx, count, fiveCount) => {
    if (count === 1) return 'one-col';
    if (count === 2) return 'two-col';
    if (count === 3) return 'three-col';
    if (count === 4) return 'two-col';
    if (count === 5) return fiveCount % 2 ? 'one-third-col-rev' : 'one-third-col';
    return '';
  };

  const renderFolioContainers = (items) => {
    const containers = [];
    let buf = [], fiveCount = 0;
    items.forEach((post, i) => {
      if (!post.featured_img_src) return;
      const pos = objectPositions[i] || { x: 50, y: 50 };
      buf.push(
        <div
          key={post.id}
          ref={(el) => (imageRefs.current[i] = el)}
          className="ppg-item-size"
          onClick={() => handleImageClick(i)}
          style={{ cursor: 'pointer', border: selectedElementIndex === i ? '2px solid blue' : 'none' }}
        >
          <div className="ppg-item-container">
            <a href={post.link} className="ppg-item">
              <img
                src={sanitizeString(post.featured_img_src)}
                alt={sanitizeString(post.title)}
                loading="lazy"
                style={{ width: '100%', height: '100%', objectFit: 'cover', objectPosition: `${pos.x}% ${pos.y}%` }}
              />
              {createElement(tagName, { style: { color: textColor, margin: 0 } }, sanitizeString(post.title) || __('Default Title', 'pentaportfoliogrid'))}
            </a>
          </div>
        </div>
      );
      if ((i + 1) % 5 === 0 || i === items.length - 1) {
        const cls = getContainerClass(containers.length, buf.length, fiveCount);
        if (buf.length === 5) fiveCount++;
        containers.push(
          <div className={`ppg-item-wrapper ${cls}`} key={containers.length}>
            {buf.map((child, idx) => {
              const isBig = buf.length === 5 && idx === 0;
              const reverse = isBig && fiveCount % 2 === 0;
              return React.cloneElement(child, {
                className: `item-folio ${isBig ? '--big' : ''} ${reverse ? 'reverse' : ''}`,
              });
            })}
          </div>
        );
        buf = [];
      }
    });
    return containers;
  };

  return (
    <div {...blockProps}>
      <InspectorControls group="styles">
        <PanelBody title={__('Text Settings', 'pentablocktheme')}>
          <PanelColorSettings
            title={__('Text Color', 'pentablocktheme')}
            initialOpen
            colorSettings={[{ value: textColor, onChange: (c) => setAttributes({ textColor: sanitizeString(c) }), label: __('Text Color', 'pentablocktheme') }]}
          />
          <SelectControl
            label={__('Heading Level', 'pentablocktheme')}
            value={tagName}
            options={[
              { label: 'H1', value: 'h1' },
              { label: 'H2', value: 'h2' },
              { label: 'H3', value: 'h3' },
              { label: 'H4', value: 'h4' },
              { label: 'H5', value: 'h5' },
              { label: 'DIV', value: 'div' },
            ]}
            onChange={(v) => setAttributes({ tagName: sanitizeString(v) })}
          />
        </PanelBody>
        <PanelBody title={__('Padding Settings', 'pentablocktheme')} initialOpen>
          <RangeControl
            label={__('Padding L/R', 'pentablocktheme')}
            value={parseInt(paddingLeftRight)}
            onChange={(v) => setAttributes({ paddingLeftRight: `${sanitizeNumber(v)}px` })}
            min={0}
            max={200}
          />
          <RangeControl
            label={__('Padding Top', 'pentablocktheme')}
            value={parseInt(paddingTop)}
            onChange={(v) => setAttributes({ paddingTop: `${sanitizeNumber(v)}px` })}
            min={0}
            max={200}
          />
          <RangeControl
            label={__('Padding Bottom', 'pentablocktheme')}
            value={parseInt(paddingBottom)}
            onChange={(v) => setAttributes({ paddingBottom: `${sanitizeNumber(v)}px` })}
            min={0}
            max={200}
          />
        </PanelBody>
        <PanelBody title={__('Crop Selected Image', 'pentablocktheme')} initialOpen>
          {selectedElementIndex !== null && (
            <>
              <p>{`${__('Editing Image', 'pentablocktheme')} #${selectedElementIndex + 1}`}</p>
              <RangeControl
                label={__('Horizontal (X)', 'pentablocktheme')}
                value={objectPositionX}
                onChange={(v) => handleObjectPositionChange('x', v)}
                min={0}
                max={100}
              />
              <RangeControl
                label={__('Vertical (Y)', 'pentablocktheme')}
                value={objectPositionY}
                onChange={(v) => handleObjectPositionChange('y', v)}
                min={0}
                max={100}
              />
            </>
          )}
        </PanelBody>
      </InspectorControls>
     
      <PPGmenu
        menuLocation="ppgMenu"
        onMenuLoad={handleMenuLoad}
        onCategorySelect={handleCategorySelect}
      />
     
      <div className="ppg-wrapper"
        style={{
          paddingLeft: paddingLeftRight,
          paddingRight: paddingLeftRight,
          paddingTop: paddingTop,
          paddingBottom: paddingBottom,
        }}
      >
        <div
          className="ppg-container"
        >

          {posts.length === 0 ? (
            <p>{__('No posts found.', 'pentablocktheme')}</p>
          ) : (
            renderFolioContainers(posts)
          )}
        </div>
      </div>
    </div>
    
  );
}

export default EditComponent;
