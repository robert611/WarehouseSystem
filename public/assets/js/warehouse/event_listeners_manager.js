import {warehouseStructure} from "./warehouse_structure.js";
import {warehouseLeaf} from "./warehouse_leaf.js";

export const eventListenersManager = {
    initWarehouseEventListeners: function () {
        warehouseStructure.initEventListeners();
        warehouseLeaf.initEventListeners();
    },
};