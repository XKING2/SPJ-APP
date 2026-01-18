@extends('layouts.main')

@section('pageheads')
<div class="container">
    <h4 class="mb-1">Tambah Data Kwitansi</h4>
</div>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <form action="{{ route('bukti.store') }}" method="POST" novalidate>
                @csrf

                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Rekanan</label>
                            <input type="text" name="penerima_kwitansi" class="form-control" value="{{ old('penerima_kwitansi') }}" required>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Rekening Bank Rekanan</label>
                            <input type="text" name="no_rekening_tujuan" class="form-control" value="{{ old('no_rekening_tujuan') }}" required>
                        </div>
                          
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-success px-4 py-2">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SweetAlert Validasi --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            if (form.dataset.submitting === "true") return;
            if (document.querySelector('.swal2-container')) return;

            const requiredFields = form.querySelectorAll('[required]');
            const emptyFields = [];

            requiredFields.forEach(input => {
                const label = input.closest('.mb-3')?.querySelector('label')?.innerText || input.name;
                if (!input.value.trim()) {
                    emptyFields.push(label.replace('*', '').trim());
                }
            });

            if (emptyFields.length > 0) {
                Swal.fire({
                    title: 'Data Belum Lengkap!',
                    html: `
                        <p>Harap isi semua kolom berikut sebelum menyimpan:</p>
                        <ul style="text-align:left; margin-left: 20px;">
                            ${emptyFields.map(f => `<li>${f}</li>`).join('')}
                        </ul>
                    `,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6',
                    allowOutsideClick: false
                });
                return;
            }

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Pastikan data yang Anda isi sudah benar sebelum disimpan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    form.dataset.submitting = "true";
                    HTMLFormElement.prototype.submit.call(form);
                }
            });
        });
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

  // config
  const pageSize = 10; // jumlah per "batch"
  let allItems = [];   // semua subkegiatan di-client
  let filteredItems = []; // hasil filter search
  let startIndex = 0;  // indeks window inclusive
  let endIndex = -1;   // indeks window inclusive

  // elems
  const pptkSelect = document.getElementById('id_pptk'); // existing PPTK select
  const toggleBtn = document.getElementById('kegiatan-toggle');
  const dropdown = document.getElementById('kegiatan-dropdown');
  const listContainer = document.getElementById('kegiatan-list');
  const combobox = document.getElementById('kegiatan-combobox');
  const hiddenInput = document.getElementById('id_kegiatan_hidden');
  const searchBox = document.getElementById('kegiatan-search');
  const footerHint = document.getElementById('kegiatan-footer');

  // NO REKENING input (yang harus terisi saat pilih sub kegiatan)
  const noRekSubInput = document.getElementById('no_rek_sub');

  // helper render single item element
  function createItemElement(item, index) {
    const div = document.createElement('div');
    div.className = 'kegiatan-item';
    div.dataset.id = item.id;
    div.dataset.idx = index;
    // you can add small meta if you want later
    div.innerHTML = `<div>${item.subkegiatan}</div>`;
    return div;
  }

  // render window from startIndex..endIndex
  function renderWindow() {
    listContainer.innerHTML = '';
    if (filteredItems.length === 0) {
      listContainer.innerHTML = `<div class="text-muted small p-2">Tidak ada hasil</div>`;
      footerHint.style.display = 'none';
      return;
    }

    const slice = filteredItems.slice(startIndex, endIndex + 1);
    slice.forEach((item, i) => {
      const el = createItemElement(item, startIndex + i);
      // mark selected if matches hidden input
      if (String(hiddenInput.value) === String(item.id)) {
        el.classList.add('selected');
      }
      listContainer.appendChild(el);
    });

    // footer hint show if more items exist
    footerHint.style.display = (filteredItems.length > pageSize) ? 'block' : 'none';
  }

  // reset window to first page
  function resetWindow() {
    startIndex = 0;
    endIndex = Math.min(pageSize - 1, filteredItems.length - 1);
    renderWindow();
    // ensure scrollTop at top
    listContainer.scrollTop = 0;
  }

  // append next page at bottom (if available)
  function loadNext() {
    if (endIndex >= filteredItems.length - 1) return;
    const nextEnd = Math.min(endIndex + pageSize, filteredItems.length - 1);
    const slice = filteredItems.slice(endIndex + 1, nextEnd + 1);
    slice.forEach((item, i) => {
      const el = createItemElement(item, endIndex + 1 + i);
      listContainer.appendChild(el);
    });
    endIndex = nextEnd;
  }

  // prepend previous page at top (if available)
  function loadPrev() {
    if (startIndex <= 0) return;
    const prevStart = Math.max(0, startIndex - pageSize);
    const slice = filteredItems.slice(prevStart, startIndex);
    // prepend in reverse to keep order
    for (let i = slice.length -1; i >=0; i--) {
      const el = createItemElement(slice[i], prevStart + i);
      listContainer.insertBefore(el, listContainer.firstChild);
    }
    // adjust startIndex
    startIndex = prevStart;
  }

  // attach click delegate for selecting item
  listContainer.addEventListener('click', function(e) {
    const it = e.target.closest('.kegiatan-item');
    if (!it) return;
    const id = it.dataset.id;
    const text = it.textContent.trim();
    hiddenInput.value = id;
    combobox.value = text;

    // mark selection visually
    listContainer.querySelectorAll('.kegiatan-item').forEach(x => x.classList.remove('selected'));
    it.classList.add('selected');

    // fetch nomor rekening sub kegiatan dan set ke input no_rek_sub
    if (id) {
      fetch(`/get-norek-sub/${id}`)
        .then(r => {
          if (!r.ok) throw new Error('Gagal mengambil no rekening');
          return r.json();
        })
        .then(d => {
          noRekSubInput.value = d.no_rek_sub ?? '';
        })
        .catch(err => {
          console.error('Error fetching no rek on select:', err);
          noRekSubInput.value = '';
        });
    }

    // close dropdown
    dropdown.style.display = 'none';
  });

  // handle scroll top/bottom for infinite-like loading
  listContainer.addEventListener('scroll', function () {
    const scrollTop = listContainer.scrollTop;
    const scrollHeight = listContainer.scrollHeight;
    const clientHeight = listContainer.clientHeight;

    // bottom reached (with 20px tolerance)
    if (scrollTop + clientHeight >= scrollHeight - 20) {
      loadNext();
    }
    // top reached
    if (scrollTop <= 10) {
      const oldScrollHeight = listContainer.scrollHeight;
      loadPrev();
      const newScrollHeight = listContainer.scrollHeight;
      // preserve scroll position to avoid jump
      listContainer.scrollTop = newScrollHeight - oldScrollHeight + scrollTop;
    }
  });

  // toggle dropdown
  toggleBtn.addEventListener('click', function () {
    if (dropdown.style.display === 'none' || !dropdown.style.display) {
      openDropdown();
    } else {
      closeDropdown();
    }
  });

  // open dropdown
  function openDropdown() {
    dropdown.style.display = 'block';
    searchBox.focus();
    // reset filter if empty (but keep current selection)
    if (!searchBox.value.trim()) {
      filteredItems = allItems.slice(); // copy
      resetWindow();
    } else {
      applySearch(searchBox.value.trim());
    }
  }

  function closeDropdown() {
    dropdown.style.display = 'none';
  }

  // click outside closes dropdown
  document.addEventListener('click', function(e){
    if (!e.target.closest('.select-wrapper')) {
      closeDropdown();
    }
  });

  // when user types in search box
  function applySearch(q) {
    const qLower = String(q).toLowerCase();
    filteredItems = allItems.filter(item => item.subkegiatan.toLowerCase().includes(qLower));
    resetWindow();
    // ensure dropdown open
    dropdown.style.display = 'block';
  }

  searchBox.addEventListener('input', function() {
    applySearch(this.value);
  });

  // combobox typing should forward to search
  combobox.addEventListener('input', function() {
    searchBox.value = combobox.value;
    applySearch(combobox.value);
  });

  // when PPTK changes, fetch subkegiatan and reset UI
  if (pptkSelect) {
    pptkSelect.addEventListener('change', function() {
      const id = this.value;
      fetchSubkegiatan(id);
    });

    // if there is a preselected PPTK on page load, auto fetch:
    const initialPptk = pptkSelect.value;
    if (initialPptk) {
      fetchSubkegiatan(initialPptk, true); // true = try to preselect existing hidden value
    }
  }

  // fetch sub kegiatan endpoint
  function fetchSubkegiatan(pptkId, tryPreselect = false) {
    if (!pptkId) return;
    fetch(`/get-subkegiatan/${pptkId}`)
      .then(r => {
        if (!r.ok) throw new Error('Failed to load');
        return r.json();
      })
      .then(data => {
        allItems = Array.isArray(data) ? data : [];
        filteredItems = allItems.slice();
        // initialize window
        startIndex = 0;
        endIndex = Math.min(pageSize - 1, filteredItems.length - 1);
        renderWindow();

        // if there's a hidden selected id prefilled (edit mode), highlight & set combobox text
        if (tryPreselect) {
          const selId = hiddenInput.value;
          if (selId) {
            const selItem = allItems.find(x => String(x.id) === String(selId));
            if (selItem) {
              combobox.value = selItem.subkegiatan;
              // --- tambahan: ambil no_rek_sub untuk preselected item ---
              fetch(`/get-norek-sub/${selId}`)
                .then(r => {
                  if (!r.ok) throw new Error('Gagal mengambil no rekening');
                  return r.json();
                })
                .then(d => {
                  noRekSubInput.value = d.no_rek_sub ?? '';
                })
                .catch(err => {
                  console.error('Error fetching no rek for preselect:', err);
                  noRekSubInput.value = '';
                });
            }
          }
        }
      })
      .catch(err => {
        console.error('Error fetching subkegiatan', err);
        allItems = [];
        filteredItems = [];
        renderWindow();
      });
  }

});
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const sub = document.getElementById("no_rek_sub");
    const manual = document.querySelector("input[name='no_rek_manual']");
    const finalInput = document.getElementById("no_rekening");

    function updateFinal() {
        let s = sub.value.trim();
        let m = manual.value.trim();
        finalInput.value = m ? `${s}.${m}` : s;
    }

    manual.addEventListener("input", updateFinal);
    sub.addEventListener("input", updateFinal);
});
</script>

@endsection
    