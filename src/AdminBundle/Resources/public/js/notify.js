function getSuccessNoty(message) {
    new Noty({
        type: 'success',
        layout: 'bottomRight',
        theme: 'bootstrap-v3',
        text: message,
        timeout: 5000,
        progressBar: true,
        closeWith: ['click'],
        animation: {
            open: 'noty_effects_open',
            close: 'noty_effects_close'
        }
    }).show()
}

function getErrorNoty(message) {
    new Noty({
        type: 'error',
        layout: 'bottomRight',
        theme: 'bootstrap-v3',
        text: message,
        timeout: 5000,
        progressBar: true,
        closeWith: ['click'],
        animation: {
            open: 'noty_effects_open',
            close: 'noty_effects_close'
        }
    }).show()
}
