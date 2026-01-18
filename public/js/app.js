

'use strict';

document.addEventListener('DOMContentLoaded', () => {
    KegiatanKwitansiModal?.init();
    BuktiSPJManager?.init();
    SubKegiatanCombobox?.init();
    NoRekeningUpdater?.init();
    FormValidation?.init();
});

document.addEventListener('DOMContentLoaded', () => {
    if (window.SubKegiatanCombobox) {
        window.SubKegiatanCombobox.init();
    }
});
