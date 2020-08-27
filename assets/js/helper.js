export const form = document.querySelector('.submit-form');
export const visible = 'visible';

export const validateForm = () => {
    const a = document.querySelector('#reservation_checkInDate').value;
    const b = document.querySelector('#reservation_checkOutDate').value;
    if (a === null || a === '' || b === null || b === '') {
        document.querySelector('#empty-form-modal').classList.add(visible);
        document.querySelector('[data-close-empty-form]').addEventListener('click', () => {
            document.querySelector('#empty-form-modal').classList.remove(visible);
        });
        return false;
    }
    return true;
};
