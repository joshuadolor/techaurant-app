import { ref, onMounted, onUnmounted } from 'vue';

export function useScrollNavigation(scrollContainer, quickNav) {
    const activeSection = ref('');

    const scrollToSection = (sectionId, sectionRefs) => {
        const section = sectionRefs[sectionId];
        if (section) {
            const container = scrollContainer.value;

            // Update active section immediately for better UX
            activeSection.value = sectionId;

            if (container && window.innerWidth >= 1024) {
                // Desktop: scroll within the container to center the section
                const containerRect = container.getBoundingClientRect();
                const sectionRect = section.getBoundingClientRect();
                const containerHeight = container.clientHeight;

                // Center the section in the container
                const scrollTop =
                    container.scrollTop +
                    sectionRect.top -
                    containerRect.top -
                    containerHeight / 2 +
                    sectionRect.height / 2;

                container.scrollTo({
                    top: Math.max(0, scrollTop),
                    behavior: 'smooth',
                });
            } else {
                // Mobile: scroll to center the section in the viewport (accounting for quick nav)
                const viewportHeight = window.innerHeight;
                const quickNavHeight = quickNav.value
                    ? quickNav.value.getBoundingClientRect().height
                    : 80;
                const usableHeight = viewportHeight - quickNavHeight;

                const elementPosition = section.getBoundingClientRect().top;
                const sectionHeight = section.getBoundingClientRect().height;

                // Center the section in the usable viewport
                const offsetPosition =
                    window.pageYOffset +
                    elementPosition -
                    usableHeight / 2 +
                    sectionHeight / 2;

                window.scrollTo({
                    top: Math.max(0, offsetPosition),
                    behavior: 'smooth',
                });
            }
        }
    };

    const handleScroll = (sectionRefs) => {
        const isMobile = window.innerWidth < 1024;
        const sections = Object.entries(sectionRefs).map(([id, element]) => ({
            id,
            element,
        }));

        let currentSection = '';

        if (isMobile) {
            // Mobile: use window scroll position with improved detection
            const scrollPosition = window.pageYOffset;
            const viewportHeight = window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight;
            const quickNavHeight = quickNav.value
                ? quickNav.value.getBoundingClientRect().height
                : 80;

            // Check if we're at the bottom of the page
            if (scrollPosition + viewportHeight >= documentHeight - 20) {
                currentSection = 'reservation';
            } else {
                // Use middle of viewport as detection point (accounting for quick nav)
                const detectionPoint =
                    scrollPosition + (viewportHeight - quickNavHeight) / 2;

                // Find which section contains the detection point
                sections.forEach((section) => {
                    if (section.element) {
                        const rect = section.element.getBoundingClientRect();
                        const elementTop = scrollPosition + rect.top;
                        const elementBottom = scrollPosition + rect.bottom;

                        if (
                            detectionPoint >= elementTop &&
                            detectionPoint < elementBottom
                        ) {
                            currentSection = section.id;
                        }
                    }
                });

                // Special handling for when we're at the very top
                if (scrollPosition < 100) {
                    currentSection = '';
                }
            }
        } else {
            // Desktop: use container scroll position
            const container = scrollContainer.value;
            if (!container) return;

            const scrollTop = container.scrollTop;
            const containerHeight = container.clientHeight;
            const scrollHeight = container.scrollHeight;

            // Check if we're at the bottom of the container
            if (scrollTop + containerHeight >= scrollHeight - 20) {
                currentSection = 'reservation';
            } else {
                // Use middle of container as detection point
                const detectionPoint = scrollTop + containerHeight / 2;

                // Find which section contains the detection point
                sections.forEach((section) => {
                    if (section.element) {
                        const elementTop = section.element.offsetTop;
                        const elementBottom =
                            elementTop + section.element.offsetHeight;

                        if (
                            detectionPoint >= elementTop &&
                            detectionPoint < elementBottom
                        ) {
                            currentSection = section.id;
                        }
                    }
                });

                // Special handling for when we're at the very top
                if (scrollTop < 50) {
                    currentSection = '';
                }
            }
        }

        if (activeSection.value !== currentSection) {
            activeSection.value = currentSection;
        }
    };

    const setupScrollListeners = (sectionRefsGetter) => {
        const scrollHandler = () => {
            const currentSectionRefs = typeof sectionRefsGetter === 'function'
                ? sectionRefsGetter()
                : sectionRefsGetter;
            handleScroll(currentSectionRefs);
        };
        const resizeHandler = () => {
            const currentSectionRefs = typeof sectionRefsGetter === 'function'
                ? sectionRefsGetter()
                : sectionRefsGetter;
            handleScroll(currentSectionRefs);
        };

        const container = scrollContainer.value;
        if (container) {
            container.addEventListener('scroll', scrollHandler, { passive: true });
        }
        window.addEventListener('scroll', scrollHandler, { passive: true });
        window.addEventListener('resize', resizeHandler, { passive: true });

        onUnmounted(() => {
            if (container) {
                container.removeEventListener('scroll', scrollHandler);
            }
            window.removeEventListener('scroll', scrollHandler);
            window.removeEventListener('resize', resizeHandler);
        });

        // Initial call
        setTimeout(() => {
            const currentSectionRefs = typeof sectionRefsGetter === 'function'
                ? sectionRefsGetter()
                : sectionRefsGetter;
            handleScroll(currentSectionRefs);
        }, 150);
    };

    return {
        activeSection,
        scrollToSection,
        handleScroll,
        setupScrollListeners,
    };
} 