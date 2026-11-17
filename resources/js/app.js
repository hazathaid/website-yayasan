import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('[data-menu-toggle]');
    const menu = document.querySelector('[data-menu]');

    if (toggle && menu) {
        toggle.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            toggle.setAttribute('aria-expanded', menu.classList.contains('hidden') ? 'false' : 'true');
        });
    }

    document.querySelectorAll('[data-confirm]').forEach((element) => {
        element.addEventListener('submit', (event) => {
            if (! window.confirm(element.dataset.confirm)) {
                event.preventDefault();
            }
        });
    });
});
