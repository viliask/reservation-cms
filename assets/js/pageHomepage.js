import {form, validateForm, handleModalClose} from './helper';
import './flatpickr';

handleModalClose();

form.addEventListener('submit', (event) => {
    event.preventDefault();
    if (validateForm()){
        form.submit();
    }
});
