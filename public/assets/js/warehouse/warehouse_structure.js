import alertUtil from '../utils/alert.js';
import {eventListenersManager as warehouseEventListenersManager} from "./event_listeners_manager.js";

export const warehouseStructure = {
    initEventListeners: function () {
        const warehouseStructureForm = document.getElementById('warehouse-structure-form');
        if (warehouseStructureForm) {
            warehouseStructureForm.addEventListener('submit', warehouseStructure.createWarehouseStructureElement);
        }

        const openNodeButtons = document.getElementsByClassName('warehouse-open-button');
        Array.from(openNodeButtons).forEach((button) => {
            button.addEventListener('click', warehouseStructure.openNode);
        });

        const goBackButton = document.getElementById('warehouse-structure-go-back');
        if (goBackButton) {
            goBackButton.addEventListener('click', warehouseStructure.goBack);
        }
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
    openNode: async function (event) {
        const button = event.target;
        const nodeId = button.getAttribute('data-nodeId');
        const endpoint = event.target.getAttribute('data-endpoint');
        await warehouseStructure.refreshNodesList(endpoint);
        await warehouseStructure.renderNewForm(nodeId);
        if (button.hasAttribute('data-isLeaf')) {
            warehouseStructure.removeNewForm();
        }
        warehouseEventListenersManager.initWarehouseEventListeners();
    },
    goBack: async function (event) {
        const endpoint = event.target.getAttribute('data-endpoint');
        const parentNodeId = event.target.getAttribute('data-nodeId');
        await warehouseStructure.refreshNodesList(endpoint).then();
        await warehouseStructure.renderNewForm(parentNodeId);
    },
    renderNewForm: async function (nodeId) {
        const endpoint = document.getElementById('warehouse-structure-new-form-endpoint').value;

        await fetch(endpoint)
            .then(response => {
                return response.text();
            })
            .then(response => {
                document.getElementById('warehouse-new-form-container').innerHTML = response;
            })
            .then(() => {
                const parentSelect = document.getElementById('warehouse_structure_parent');
                parentSelect.value = nodeId;
            });

        warehouseEventListenersManager.initWarehouseEventListeners();
    },
    refreshNodesList: async function (endpoint) {
        await fetch(endpoint)
            .then(response => {
                if (response.ok === false) {
                    throw new Error('Ups. Coś poszło nie tak podczas otwierania elementu struktury.');
                }

                return response.text();
            })
            .then((response) => {
                document.getElementById('warehouse-nodes-grid-display-container').innerHTML = response;
            })
            .catch(error => {
                alert(error);
            });

        warehouseEventListenersManager.initWarehouseEventListeners();
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
    removeNewForm: function () {
        document.getElementById('warehouse-new-form-container').innerHTML = '';
    },
}

warehouseStructure.initEventListeners();

// 1921 Violet Stamp. Since there were not many designs and nominal values used, it was decieded to distinguish stamps through the use of different main colors. Sold US 3.500.