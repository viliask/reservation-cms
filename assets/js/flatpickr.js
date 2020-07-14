import flatpickr from 'flatpickr';
import rangePlugin from 'flatpickr/dist/plugins/rangePlugin';
import pl from 'flatpickr/dist/l10n/pl';

flatpickr('.js-datepicker', {
    minDate: 'today',
    locale: 'pl',
    plugins: [new rangePlugin({ input: "#reservation_checkOutDate"})]
});
