import {warehouseStructure} from "./warehouse_structure.js";
import {eventListenersManager as warehouseEventListenersManager} from "./event_listeners_manager.js";

export const warehouseLeaf = {
    initEventListeners: function () {
        warehouseLeaf.initSetAsLeafButtons();
        warehouseLeaf.initUnsetAsLeafButtons();
        warehouseLeaf.initSaveConfigurationForm();
    },
    initSetAsLeafButtons: function () {
        const setAsLeafButtons = document.getElementsByClassName('warehouse-set-as-leaf-button');
        Array.from(setAsLeafButtons).forEach((button) => {
            button.addEventListener('click', warehouseLeaf.toggleLeafStatus);
        });
    },
    initUnsetAsLeafButtons: function () {
        const unsetAsLeafButtons = document.getElementsByClassName('warehouse-unset-as-leaf-button');
        Array.from(unsetAsLeafButtons).forEach((button) => {
            button.addEventListener('click', warehouseLeaf.toggleLeafStatus);
        });
    },
    initSaveConfigurationForm: function () {
        const form = document.getElementById('warehouse-leaf-configuration-form');
        if (form) {
            form.addEventListener('submit', warehouseLeaf.saveConfiguration);
        }
    },
    toggleLeafStatus: function (event) {
        event.preventDefault();
        const button = event.target;
        const endpoint = button.getAttribute('data-endpoint');

        fetch(endpoint, {
            method: 'POST'
        })
            .then(response => {
                if (response.ok === false) {
                    throw new Error('Ups. Coś poszło nie tak podczas zmiany stanu elementu.');
                }

                return response.json();
            })
            .then(async response => {
                if (response.error === true) {
                    alert(response['errorMessage']);
                } else {
                    const endpoint = warehouseStructure.getOpenNodeEndpoint();
                    await warehouseStructure.refreshNodesList(endpoint);
                }
            })
            .then(() => {
                warehouseEventListenersManager.initWarehouseEventListeners();
            })
            .catch(error => {
                alert(error);
            });
    },
    saveConfiguration: function (event) {
        event.preventDefault();
        const form = event.target;
        const endpoint = form.getAttribute('action');

        fetch(endpoint, {
            method: 'POST',
            body: new FormData(form),
        })
            .then((response) => {
                return response.text();
            })
            .then((response) => {
                document.getElementById('warehouse-leaf-configuration-form').innerHTML = response;
            });
    },
}

warehouseLeaf.initEventListeners();
