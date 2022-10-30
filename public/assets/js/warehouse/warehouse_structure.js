import alertUtil from '../utils/alert.js';

const warehouseStructure = {
    initEventListeners: function () {
        const warehouseStructureForm = document.getElementById('warehouse-structure-form');
        warehouseStructureForm.addEventListener('submit', this.createWarehouseStructureElement);

        const openNodeButtons = document.getElementsByClassName('warehouse-open-button');
        Array.from(openNodeButtons).forEach((button) => {
            button.addEventListener('click', this.openNode);
        });
    },
    createWarehouseStructureElement: function (event) {
        event.preventDefault();
        const form = event.target;
        const endpoint = form.getAttribute('action');

        fetch(endpoint, {
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
                    form.appendChild(alertUtil.createAlertWidget('success', 'Element został stworzony'));
                } else {
                    throw new Error('Ups. Coś poszło nie tak podczas tworzenia elementu struktury.');
                }
            })
            .catch(error => {
                form.appendChild(alertUtil.createAlertWidget('danger', error));
            });
    },
    openNode: function (event) {
        const nodeId = event.target.getAttribute('data-nodeId');
        const rawEndpoint = document.getElementById('warehouse-structure-open-endpoint').value;
        const endpoint = rawEndpoint.replace('fileId', nodeId)

        fetch(endpoint)
            .then(response => {
                if (response.ok === false) {
                    throw new Error('Ups. Coś poszło nie tak podczas otwierania elementu struktury.');
                }

                return response.text();
            })
            .then((response) => {
               document.getElementsByClassName('container-fluid')[0].innerHTML = response;
               warehouseStructure.initEventListeners();
            })
            .catch(error => {
                alert(error);
            });
    },
}

warehouseStructure.initEventListeners();