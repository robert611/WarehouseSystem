import liveEventListener from "../utils/live_event_listener.js";

const productParameter = {
    initEventListeners: function () {
        liveEventListener('click', '.add_item_link', function () {
            productParameter.addFormToCollection(this);
        });
    },
    addFormToCollection: function (button) {
        const collectionHolder = document.querySelector('#' + button.dataset.collectionHolderId);
        const metaDataHolder = document.querySelector('#' + button.dataset.prototypeHolder);
        const item = document.createElement('div');
        item.innerHTML = metaDataHolder
            .dataset
            .prototype
            .replace(
                /__name__/g,
                metaDataHolder.dataset.index
            );
        collectionHolder.appendChild(item);
        metaDataHolder.dataset.index++;
    },
};

productParameter.initEventListeners();