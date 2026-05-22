document.addEventListener('DOMContentLoaded', () => {
    const menuContainer = document.querySelector('.menu-container');

    if (menuContainer) {
        menuContainer.addEventListener('click', (event) => {
            const targetLink = event.target.closest('.menu-link');
            if (!targetLink) return;
            const menuItem = targetLink.parentElement;
            if (menuItem.classList.contains('has-children')) {
                menuItem.classList.toggle('collapsed');
            }
        });
    }
});