function incrementQuantity(el, id) {
    let wrapper = el.closest('[data-field-wrapper]');
    if (wrapper) {
        let input = wrapper.querySelector('input[id*="' + id + '"]');
        if (input) {
            input.value = (parseInt(input.value) || 0) + 1;
            input.dispatchEvent(new Event('input'));
        } else {
            console.error('Input field with ID containing "quantity" not found within the wrapper.');
        }
    } else {
        console.error('Field wrapper not found.');
    }
}

function decrementQuantity(el, id) {
    let wrapper = el.closest('[data-field-wrapper]');
    if (wrapper) {
        let input = wrapper.querySelector('input[id*="' + id + '"]');
        if (input) {
            let currentValue = parseInt(input.value) || 0;
            input.value = Math.max(currentValue - 1, 1); // Ensure value does not go below 1
            input.dispatchEvent(new Event('input'));
        } else {
            console.error('Input field with ID containing "' + id + '" not found within the wrapper.');
        }
    } else {
        console.error('Field wrapper not found.');
    }
}
