import { useState, useEffect, useRef } from '@wordpress/element';

const PentaNav = () => {
    const [blogName, setBlogName] = useState(siteInfo.blogName);
    const [homeUrl, setHomeUrl] = useState(siteInfo.homeUrl);
    const [menuItems, setMenuItems] = useState([]);

    const controlRef = useRef([]);
    const colorRef = useRef([]);
    const navItemsRef = useRef(null);
    const navRef = useRef(null);

    useEffect(() => {
        // Fetch menu items using the custom REST API endpoint
        wp.apiFetch({ path: '/mytheme/v1/menu-items/mainNav' })
            .then((items) => setMenuItems(items))
            .catch((error) => console.error('Error fetching menu items:', error));
    }, []);

    useEffect(() => {
        // After the component mounts, you can access the refs
        const controlElems = document.getElementsByClassName("nav-control-btns");
        const navItemsElem = document.getElementById("menu-main-menu");
        const navElem = document.getElementsByTagName("nav")[0];
        const logoElem = document.getElementById("LogoType");

        // Assign refs only when the elements exist
        if (controlElems.length) {
            controlRef.current = Array.from(controlElems);
        }
        if (navItemsElem) {
            navItemsRef.current = navItemsElem;
        }
        if (navElem) {
            navRef.current = navElem;
            colorRef.current.push(navElem); // Add nav element to colorRef
        }
        if (logoElem) {
            colorRef.current.push(logoElem); // Add logo element to colorRef
        }

        // Add control elements to colorRef
        controlRef.current.forEach(control => {
            if (control) {
                colorRef.current.push(control);
            }
        });

        // Log the refs to ensure they are correctly assigned
        console.log('Refs:', {
            control: controlRef.current,
            color: colorRef.current,
            navItems: navItemsRef.current,
            nav: navRef.current,
        });

        // Add event listeners for control buttons
        controlRef.current.forEach((button) => {
            if (button) {
                button.addEventListener("click", handleNavToggle);
            }
        });

        // Clean up the event listeners on unmount
        return () => {
            controlRef.current.forEach((button) => {
                if (button) {
                    button.removeEventListener("click", handleNavToggle);
                }
            });
        };
    }, [menuItems]);

    const handleNavToggle = () => {
        if (controlRef.current[0] && controlRef.current[0].classList.contains("hide")) {
            mobileNav();
        } else {
            openMobileNav();
        }
    };

    const mobileNav = () => {
        if (controlRef.current[0]) {
            controlRef.current[0].classList.remove("hide");
        }
        if (controlRef.current[1]) {
            controlRef.current[1].classList.add("hide");
        }

        // Remove "white" class from all colorRef elements
        colorRef.current.forEach((el) => {
            if (el && el.classList.contains("white")) {
                el.classList.remove("white");
            }
        });

        if (navItemsRef.current) {
            navItemsRef.current.classList.add("hide");
        }
        if (navRef.current) {
            navRef.current.classList.remove("open", "nav-overlay");
        }
    };

    const killMobileNav = () => {
        if (navRef.current) {
            navRef.current.classList.remove("nav-overlay", "open");
        }
        if (navItemsRef.current) {
            navItemsRef.current.classList.remove("hide");
        }

        controlRef.current.forEach((i) => {
            if (i) {
                i.classList.add("hide");
            }
        });

        colorRef.current.forEach((i) => {
            if (i) {
                i.classList.remove("white");
            }
        });
    };

    const openMobileNav = () => {
        if (navItemsRef.current) {
            navItemsRef.current.classList.remove("hide");
        }
        if (navRef.current) {
            navRef.current.classList.add("nav-overlay", "open");
        }

        // Add "white" class to all colorRef elements
        colorRef.current.forEach((el) => {
            if (el) {
                el.classList.add("white");
            }
        });

        controlRef.current.forEach((i) => {
            if (i) {
                i.classList.toggle("hide");
            }
        });
    };

    useEffect(() => {
        const mqListener = window.matchMedia("(max-width:750px)");
        const handleMediaChange = (event) => {
            if (event.matches) {
                mobileNav();
            } else {
                killMobileNav();
            }
        };

        handleMediaChange(mqListener);
        mqListener.addEventListener('change', handleMediaChange);

        return () => {
            mqListener.removeEventListener('change', handleMediaChange);
        };
    }, []);

    return (
        <>
            <a href={homeUrl} className="grid-top grid-left mobile-nav logo-type targets" id="LogoType" ref={(el) => colorRef.current.push(el)}  >
                {blogName}
            </a>
            
            <nav 
                id="mainNav" 
                className="mobile-nav targets" 
                ref={(el) => {
                    navRef.current = el;      // Assign to navRef
                    colorRef.current.push(el); // Add nav element to colorRef
                }} 
            >
                <ul id="menu-main-menu" className="nav-items" ref={navItemsRef}>
                    {menuItems.length > 0 ? (
                        menuItems.map((item) => (
                            <li key={item.id} ref={(el) => colorRef.current.push(el)}>
                                <a href={item.url}>{item.title}</a>
                            </li>
                        ))
                    ) : (
                        <li>No menu items found.</li>
                    )}
                </ul>
            </nav>
            <div 
                className="nav-control-btns grid-top grid-right mobile-nav hide targets" 
                ref={(el) => (controlRef.current[0] = el)}
            >
                <h6>=</h6>
            </div>
            <div 
                className="nav-control-btns grid-top grid-right mobile-nav hide" 
                ref={(el) => (controlRef.current[1] = el)}
            >
                <h6>x</h6>
            </div>
        </>
    );
};

export default PentaNav;
