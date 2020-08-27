import {form, validateForm, handleModalClose} from './helper';

handleModalClose();

form.addEventListener('submit', (event) => {
    event.preventDefault();
    if (validateForm()){
        form.submit();
    }
});
