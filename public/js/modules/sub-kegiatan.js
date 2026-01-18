'use strict';

window.SubKegiatanCombobox = {
    config: {
        kecamatanSelect: '#kecamatan_id',
        desaSelect: '#desa_id',
        searchInput: '#search_sub_kegiatan',
        endpoint: '/ajax/sub-kegiatan',
        delay: 400
    },

    init() {
        this.cacheDom();
        this.attachEventListeners();
    },

    cacheDom() {
        this.kecamatanEl = document.querySelector(this.config.kecamatanSelect);
        this.desaEl = document.querySelector(this.config.desaSelect);
        this.searchEl = document.querySelector(this.config.searchInput);
    },

    attachEventListeners() {
        if (this.kecamatanEl) {
            this.kecamatanEl.addEventListener('change', () => {
                this.fetchDesa(this.kecamatanEl.value);
            });
        }

        if (this.searchEl) {
            this.searchEl.addEventListener(
                'input',
                this.debounce(() => {
                    this.fetchSubKegiatan(this.searchEl.value);
                }, this.config.delay)
            );
        }
    },

    fetchDesa(kecamatanId) {
        if (!kecamatanId) return;

        this.ajax({
            url: `/ajax/desa/${kecamatanId}`,
            onSuccess: (res) => this.renderOptions(this.desaEl, res)
        });
    },

    fetchSubKegiatan(keyword) {
        this.ajax({
            url: `${this.config.endpoint}?q=${encodeURIComponent(keyword)}`,
            onSuccess: (res) => {
                console.log('Sub kegiatan:', res);
            }
        });
    },

    renderOptions(selectEl, data) {
        if (!selectEl) return;

        selectEl.innerHTML = '<option value="">-- Pilih --</option>';

        data.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.nama;
            selectEl.appendChild(opt);
        });
    },

    ajax({ url, method = 'GET', onSuccess, onError }) {
        fetch(url, {
            method,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(res => {
                if (!res.ok) throw res;
                return res.json();
            })
            .then(data => onSuccess && onSuccess(data))
            .catch(err => {
                console.error('AJAX Error:', err);
                if (onError) onError(err);
            });
    },

    debounce(fn, delay) {
        let timer;
        return function (...args) {
            clearTimeout(timer);
            timer = setTimeout(() => fn.apply(this, args), delay);
        };
    }
};
