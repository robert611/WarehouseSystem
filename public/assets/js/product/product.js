const product = {
    initEventListeners: function () {
        const addProductPictureModal = document.getElementById('add-product-picture-modal');
        addProductPictureModal.addEventListener('show.bs.modal', this.renderAddProductPictureModal)
    },
    renderAddProductPictureModal: function(event) {
        const button = event.relatedTarget
        const endpoint = button.getAttribute('data-endpoint');
        const modalBodyInput = document.getElementById('add-product-picture-modal').querySelector('.modal-body');

        fetch(endpoint)
            .then(response => response.text())
            .then(htmlContent => {
                modalBodyInput.innerHTML = htmlContent;
                const productPictureForm = document.getElementById('product-picture-form');
                console.log(productPictureForm);
                productPictureForm.addEventListener('submit', product.sendProductPictureForm);
            });
    },
    sendProductPictureForm: function(event) {
        event.preventDefault();
        const form = event.target;
        const alert = document.getElementById('add-product-picture-alert');

        fetch(form.getAttribute('action'), {
            'method': 'POST',
            'body': new FormData(form),
        })
            .then(response => {
                if (response.ok === false) {
                    throw new Error('Ups. Coś poszło nie tak podczas zapisywania zdjęcia.');
                }

                return response.json();
            })
            .then(() => {
                alert.setAttribute('class', 'alert alert-success');
                alert.textContent = 'Zdjęcie zostało zapisane';
            })
            .catch(error => {
                alert.setAttribute('class', 'alert alert-danger');
                alert.textContent = error;
            });
    }
}

document.addEventListener("DOMContentLoaded", function() {
    product.initEventListeners();
});