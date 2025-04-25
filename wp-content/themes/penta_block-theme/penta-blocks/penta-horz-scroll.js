import { useEffect, useRef } from '@wordpress/element';

const PentaHorzScroll = () => {
    const btnsRef = useRef([]);
    const containerRef = useRef([]);

    useEffect(() => {
        const initializeScrollBehavior = () => {
            const btns = Array.from(document.getElementsByClassName("btns"));
            const containers = Array.from(document.getElementsByClassName("work-cat-container"));

            if (!btns.length || !containers.length) {
                return; // Exit if elements are not found
            }

            btnsRef.current = btns;
            containerRef.current = containers;

            btns.forEach((i) => {
                if (/Mobi|Android/i.test(navigator.userAgent)) {
                    i.style.display = "none";
                }
                const parent = i.parentNode;
                if (!parent) return;

                const workFeedContainers = Array.from(parent.querySelectorAll(".work-feed-container"));
                if (!workFeedContainers.length) return;

                workFeedContainers.forEach((feedContainer) => {
                    feedContainer.addEventListener("mouseenter", () => {
                        i.classList.remove("transparent");
                    });
                    containers.forEach((container) => {
                        container.addEventListener("mouseleave", () => {
                            if (!i.classList.contains("transparent")) {
                                i.classList.add("transparent");
                            }
                        });
                    });

                    btns.forEach((btn) => {
                        btn.addEventListener("mouseleave", () => {
                            i.classList.add("transparent");
                        });
                    });

                    i.addEventListener("click", () => {
                        if (i.classList.contains("slide-left")) {
                            feedContainer.scrollLeft -= 200;
                        } else {
                            feedContainer.scrollLeft += 200;
                        }
                    });
                });
            });
        };

        const observer = new MutationObserver(() => {
            initializeScrollBehavior();
        });

        // Start observing changes in the document
        observer.observe(document, { childList: true, subtree: true });

        // Clean up the observer and event listeners when the component is unmounted
        return () => {
            observer.disconnect();
            btnsRef.current.forEach((btn) => {
                btn.removeEventListener("click", initializeScrollBehavior);
            });
            containerRef.current.forEach((container) => {
                container.removeEventListener("mouseleave", initializeScrollBehavior);
            });
        };
    }, []); // Run the effect once on component mount

    return (
        <>
            <div className="slide-left btns transparent">
                <div className="chevron-left"></div>
            </div>
            <div className="slide-right btns transparent">
                <div className="chevron-right"></div>
            </div>
        </>
    );
};

export default PentaHorzScroll;
