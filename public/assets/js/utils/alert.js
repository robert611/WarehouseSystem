const alert = {
    createAlertWidget: function (alertClass, message) {
        const alert = document.createElement('div');
        const dismissButton = document.createElement('button');
        const paragraph = document.createElement('span');
        alert.classList.add('alert');
        alert.classList.add('alert-' + alertClass);
        alert.classList.add('alert-dismissible');
        alert.classList.add('fade');
        alert.classList.add('show');
        alert.classList.add('mt-3');
        alert.setAttribute('role', 'alert');
        dismissButton.classList.add('btn-close');
        dismissButton.setAttribute('type', 'button');
        dismissButton.setAttribute('data-bs-dismiss', 'alert');
        dismissButton.setAttribute('aria-label', 'close');
        paragraph.textContent = message;
        alert.appendChild(paragraph);
        alert.appendChild(dismissButton);

        return alert;
    },
}

export default alert;