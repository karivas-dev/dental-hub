//import './bootstrap';

function incrementQuantity(el, id) {
    let wrapper = el.closest('[data-field-wrapper]');
    if (wrapper) {
        let input = wrapper.querySelector('input[id*="' + id + '"]');
        if (input) {
            input.value = (parseInt(input.value) || 0) + 1;
        } else {
            console.error('Input field with ID containing "quantity" not found within the wrapper.');
        }
    } else {
        console.error('Field wrapper not found.');
    }
}

