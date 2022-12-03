const liveEventListener = function (eventType, elementSelector, callback) {
    document.addEventListener(eventType, function (event) {
        const elements = document.querySelectorAll(elementSelector);
        Array.from(elements).forEach((element) => {
            if (event.target === element) {
                callback.call(event.target, event);
            }
        })
    });
}

export default liveEventListener;