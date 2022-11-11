import alertUtil from '../utils/alert.js';

export const warehouseStructure = {
    initEventListeners: function () {
        const warehouseStructureForm = document.getElementById('warehouse-structure-form');
        warehouseStructureForm.addEventListener('submit', warehouseStructure.createWarehouseStructureElement);

        const openNodeButtons = document.getElementsByClassName('warehouse-open-button');
        Array.from(openNodeButtons).forEach((button) => {
            button.addEventListener('click', warehouseStructure.openNode);
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
            .then(() => {
                const endpoint = warehouseStructure.getOpenNodeEndpoint(form['warehouse_structure[parent]'].value);
                warehouseStructure.refreshNodesList(endpoint).then();
            })
            .catch(error => {
                form.appendChild(alertUtil.createAlertWidget('danger', error));
            });
    },
    openNode: function (event) {
        const button = event.target;
        const nodeId = button.getAttribute('data-nodeId');
        const endpoint = event.target.getAttribute('data-endpoint');

        const refreshNodesList = warehouseStructure.refreshNodesList(endpoint);

        refreshNodesList
            .then(async () => {
                await warehouseStructure.renderNewForm(nodeId);
            })
            .catch(error => {
                alert(error);
            });
    },
    renderNewForm: function (nodeId) {
        const endpoint = document.getElementById('warehouse-structure-new-form-endpoint').value;

        fetch(endpoint)
            .then(response => {
                return response.text();
            })
            .then(response => {
                document.getElementById('warehouse-new-form-container').innerHTML = response;
            })
            .then(() => {
                const parentSelect = document.getElementById('warehouse_structure_parent');
                parentSelect.value = nodeId;
            })
            .then(() => {
                warehouseStructure.initEventListeners();
            });
    },
    refreshNodesList: function (endpoint) {
        return fetch(endpoint)
            .then(response => {
                if (response.ok === false) {
                    throw new Error('Ups. Coś poszło nie tak podczas otwierania elementu struktury.');
                }

                return response.text();
            })
            .then((response) => {
                document.getElementById('warehouse-nodes-grid-display-container').innerHTML = response;
            })
            .then(() => {
                warehouseStructure.initEventListeners();
            });
    },
    getOpenNodeEndpoint: function (parentId = undefined) {
        if (parentId === undefined) {
            const parentInput = document.getElementById('warehouse_structure_parent');
            if (parentInput) {
                parentId = parentInput.value;
            }
        }

        if (parentId) {
            let endpoint = document.getElementById('warehouse-structure-open-endpoint').value;
            endpoint = endpoint.replace('nodeId', parentId);

            return endpoint;
        }

        return document.getElementById('warehouse-structure-open-root-endpoint').value;
    },
}

warehouseStructure.initEventListeners();