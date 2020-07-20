const openEl = document.querySelector('[data-open]');
const closeEl = document.querySelector('[data-close]');
const visible = 'visible';

openEl.addEventListener('click', function () {
    const modalId = this.dataset.open;
    document.getElementById(modalId).classList.add(visible);
});

closeEl.addEventListener('click', function () {
    this.parentElement.parentElement.parentElement.classList.remove(visible);
});

document.addEventListener('click', e => {
    if (e.target === document.querySelector('.modal.visible')) {
        document.querySelector('.modal.visible').classList.remove(visible);
    }
});

document.addEventListener('keyup', e => {
    if (e.key === 'Escape' && document.querySelector('.modal.visible')) {
        document.querySelector('.modal.visible').classList.remove(visible);
    }
});
