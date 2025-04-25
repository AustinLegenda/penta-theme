import apiFetch from '@wordpress/api-fetch';
import { useState, useEffect } from '@wordpress/element';

const PPGmenu = ({
  menuLocation     = 'ppgMenu',
  onMenuLoad       = () => {},
  onCategorySelect = () => {},
}) => {
  const [menuItems, setMenuItems] = useState([]);
  const [error, setError]         = useState(null);

  useEffect(() => {
    if (!menuLocation) {
      setError('No Menu Location provided');
      return;
    }

    const restPath = `mytheme/v1/menu-items/${menuLocation}`;
    apiFetch({ path: restPath })
      .then((items) => {
        setMenuItems(items);
        const categoryIds = items
          .filter(item => item.object === 'category')
          .map(item => item.object_id);
        onMenuLoad(categoryIds);
      })
      .catch((err) => {
        setError(err.message || 'Fetch error');
        onMenuLoad([]);
      });
  }, [menuLocation]);

  if (error) {
    return <div>Error: {error}</div>;
  }

  if (!menuItems.length) {
    return <div>Loading menu…</div>;
  }

  return (
    <div class="ppg-menu-wrapper">
    <nav className="ppg-menu-container" aria-label="Category Menu">
      <ul>
        <li>
          <a
            href="#"
            className="ppg-menu-item"
            onClick={(e) => {
              e.preventDefault();
              onCategorySelect(null);
            }}
          >
            <h5>All</h5>
          </a>
        </li>
        {menuItems.map(item => {
          if (item.object !== 'category') {
            return null;
          }
          return (
            <li key={item.ID}>
              <a
                href="#"
                className="ppg-menu-item"
                onClick={(e) => {
                  e.preventDefault();
                  onCategorySelect(item.object_id);
                }}
              >
                <h5>{item.title}</h5>
              </a>
            </li>
          );
        })}
      </ul>
    </nav>
    </div>
  );
};

export default PPGmenu;
