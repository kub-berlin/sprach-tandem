window.addEventListener('load', () => {
    parent.postMessage({
        type: 'size',
        value: document.body.scrollHeight,
    }, location.origin);
});
