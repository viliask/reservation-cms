import flatpickr from 'flatpickr';
import rangePlugin from 'flatpickr/dist/plugins/rangePlugin';

flatpickr('.js-datepicker', {
    plugins: [new rangePlugin({ input: "#reservation_checkOutDate"})]
});
