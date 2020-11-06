import './cookieconsent.min'
import './cookie-info'

document.querySelector('.js-res-nav-toggle').addEventListener('click', () => {
    if (document.querySelector('body').classList.contains('res-offcanvas')) {
        document.querySelector('body').classList.remove('res-offcanvas');
    } else {
        document.querySelector('body').classList.add('res-offcanvas');
    }
});

document.addEventListener('click', (e) => {
    const container = document.querySelector('.js-res-nav-toggle');
    if (container.contains(e.target) === false) {
        if (document.querySelector('body').classList.contains('res-offcanvas')) {
            document.querySelector('body').classList.remove('res-offcanvas');
        }
    }
});

window.addEventListener('resize', () => {
    if (document.querySelector('#offcanvas-menu')) {
        document.querySelector('body').classList.remove('res-offcanvas');
    }
});
