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
