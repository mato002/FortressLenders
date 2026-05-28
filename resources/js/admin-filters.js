/**
 * Auto-submit admin GET filter forms when inputs change.
 * Forms must have the data-auto-filter attribute.
 */
document.addEventListener('DOMContentLoaded', () => {
    const debounceMs = 450;

    document.querySelectorAll('form[data-auto-filter]').forEach((form) => {
        let searchTimeout = null;

        const submitForm = () => {
            if (typeof form.requestSubmit === 'function') {
                form.requestSubmit();
            } else {
                form.submit();
            }
        };

        form.querySelectorAll('select, input[type="date"], input[type="datetime-local"]').forEach((el) => {
            el.addEventListener('change', submitForm);
        });

        form.querySelectorAll('input[type="checkbox"], input[type="radio"]').forEach((el) => {
            el.addEventListener('change', submitForm);
        });

        form.querySelectorAll('input[type="text"], input[type="search"], input[type="number"], input[type="email"], input[type="tel"]').forEach((el) => {
            el.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(submitForm, debounceMs);
            });

            el.addEventListener('keydown', (event) => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    clearTimeout(searchTimeout);
                    submitForm();
                }
            });
        });
    });
});
