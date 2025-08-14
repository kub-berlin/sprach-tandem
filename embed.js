window.addEventListener('load', () => {
    parent.postMessage({
        type: 'size',
        value: document.documentElement.scrollHeight,
    }, location.origin);
});
