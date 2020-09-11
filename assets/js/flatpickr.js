import flatpickr from 'flatpickr';
import rangePlugin from 'flatpickr/dist/plugins/rangePlugin';
import pl from 'flatpickr/dist/l10n/pl';

if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
    flatpickr('.js-datepicker', {
        minDate: 'today',
        locale: 'pl',
    });
} else {
    flatpickr('.js-datepicker', {
        minDate: 'today',
        locale: 'pl',
        plugins: [new rangePlugin({ input: "#reservation_checkOutDate"})],
    });
}
