import {warehouseStructure} from "./warehouse_structure.js";

const warehouseLeaf = {
    initEventListeners: function () {
        warehouseLeaf.initSetAsLeafButtons();
        warehouseLeaf.initUnsetAsLeafButtons();
    },
    initSetAsLeafButtons: function () {
        const setAsLeafButtons = document.getElementsByClassName('warehouse-set-as-leaf-button');
        Array.from(setAsLeafButtons).forEach((button) => {
            button.addEventListener('click', warehouseLeaf.setAsLeaf);
        });
    },
    initUnsetAsLeafButtons: function () {
        const unsetAsLeafButtons = document.getElementsByClassName('warehouse-unset-as-leaf-button');
        Array.from(unsetAsLeafButtons).forEach((button) => {
            button.addEventListener('click', warehouseLeaf.unsetAsLeaf);
        });
    },
    setAsLeaf: function (event) {
        console.log('setAsLeaf');
        event.preventDefault();
        const button = event.target;
        const endpoint = button.getAttribute('data-endpoint');

        fetch(endpoint, {
            method: 'POST'
        })
            .then(response => {
                if (response.ok === false) {
                    throw new Error('Ups. Coś poszło nie tak podczas otwierania elementu struktury.');
                }

                return response.json();
            })
            .then(response => {
                if (response.error === true) {
                    alert(response['errorMessage']);
                } else {
                    const endpoint = warehouseStructure.getOpenNodeEndpoint();
                    warehouseStructure.refreshNodesList(endpoint).then();
                }
            })
            .catch(error => {
                alert(error);
            });
    },
    unsetAsLeaf: function () {

    },
}

warehouseLeaf.initEventListeners();
