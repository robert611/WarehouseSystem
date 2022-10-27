import alert from '../utils/alert.js';

const warehouseStructure = {
    initEventListeners: function () {
        const warehouseStructureForm = document.getElementById('warehouse-structure-form');
        warehouseStructureForm.addEventListener('submit', this.createWarehouseStructureElement);
    },
    createWarehouseStructureElement: function (event) {
        event.preventDefault();
        const form = event.target;

        fetch(form.getAttribute('action'), {
            'method': 'POST',
            'body': new FormData(form),
        })
            .then(response => {
                if (response.ok === false) {
                    throw new Error('Ups. Coś poszło nie tak podczas tworzenia elementu struktury.');
                }

                return response.json();
            })
            .then((response) => {
                if (response.error === false) {
                    form.appendChild(alert.createAlertWidget('success', 'Element został stworzony'));
                } else {
                    throw new Error('Ups. Coś poszło nie tak podczas tworzenia elementu struktury.');
                }
            })
            .catch(error => {
                form.appendChild(alert.createAlertWidget('danger', error));
            });
    },
}

warehouseStructure.initEventListeners();