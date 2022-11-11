import {warehouseStructure} from "./warehouse_structure.js";
import {eventListenersManager as warehouseEventListenersManager} from "./event_listeners_manager.js";

export const warehouseLeaf = {
    initEventListeners: function () {
        warehouseLeaf.initSetAsLeafButtons();
        warehouseLeaf.initUnsetAsLeafButtons();
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
                    await warehouseStructure.refreshNodesList(endpoint).then();
                }
            })
            .then(() => {
                warehouseEventListenersManager.initWarehouseEventListeners();
            })
            .catch(error => {
                alert(error);
            });
    },
}

warehouseLeaf.initEventListeners();
