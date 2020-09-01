const makeInvisible = (modal) => {
    modal.classList.remove(visible);
};

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

export const handleModalClose = () => {
    document.addEventListener('click', e => {
        const openedModal = document.querySelector('.modal.visible');

        if (e.target === openedModal) {
            makeInvisible(openedModal);
        }

        if (document.querySelector('#policy-modal') === openedModal && e.target === openedModal) {
            makeInvisible(openedModal);
            document.querySelector('#reservation-modal').classList.add(visible);
        }
    });

    document.addEventListener('keyup', e => {
        const openedModal = document.querySelector('.modal.visible');

        if (e.key === 'Escape' && openedModal) {
            makeInvisible(openedModal);
        }

        if (document.querySelector('#policy-modal') === openedModal && e.key === 'Escape' && openedModal) {
            makeInvisible(openedModal);
            document.querySelector('#reservation-modal').classList.add(visible);
        }
    });
};
