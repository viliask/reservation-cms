import {form, validateForm} from './helper';

form.addEventListener('submit', (event) => {
    event.preventDefault();
    if (validateForm()){
        form.submit();
    }
});
