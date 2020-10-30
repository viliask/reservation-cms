export const form = document.querySelector('.submit-form');
export const visible = 'visible';
const MIN_STAY_DAYS = 2;

export const validateForm = () => {
    const a = document.querySelector('#reservation_checkInDate').value;
    const b = document.querySelector('#reservation_checkOutDate').value;
    if (a === null || a === '' || b === null || b === '') {
        makeVisible(document.querySelector('#empty-form-modal'));
        document.querySelector('[data-close-empty-form]').addEventListener('click', () => {
            makeInvisible(document.querySelector('#empty-form-modal'));
        });
        return false;
    }
    if (a >= b) {
        makeVisible(document.querySelector('#datepicker-error'));
        document.querySelector('[data-close-datepicker-error]').addEventListener('click', () => {
            makeInvisible(document.querySelector('#datepicker-error'));
        });
        return false;
    }
    const checkInDate = new Date(document.querySelector('#reservation_checkInDate').value);
    const checkOutDate = new Date(document.querySelector('#reservation_checkOutDate').value);
    const stayLength = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
    if (stayLength < MIN_STAY_DAYS) {
        document.querySelector('#min-stay-modal').classList.add(visible);
        document.querySelector('[data-close-min-stay-modal]').addEventListener('click', () => {
            document.querySelector('#min-stay-modal').classList.remove(visible);
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
            makeVisible('#reservation-modal');
        }
    });
};

export const makeVisible = (element) => {
    element.style.removeProperty('visibility');
    element.classList.add(visible);
};

export const makeInvisible = (element) => {
    element.classList.remove(visible);
};
