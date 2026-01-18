
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) console.warn('⚠️ CSRF token meta tag tidak ditemukan. Tambahkan di layout main3.');

    // 🧠 Event delegation agar dropdown tetap bisa digunakan berulang kali
    document.addEventListener('click', function(e) {
        const option = e.target.closest('.status2-option');
        if (!option) return;
        e.preventDefault();

        const id = option.dataset.id;
        const status2 = option.dataset.status; // ✅ variabel status2 disini
        const statusInput = document.getElementById(`status2_${id}`);

        if (!statusInput) return;
        statusInput.value = status2;

        if (status2 === 'belum_valid') { // ✅ variabel yang benar
            document.getElementById('feedback_spj_id').value = id;
            $('#feedbackModal').modal('show');
        } else {
            document.getElementById(`form-${id}`).submit();
        }
    });

    // ➕ Tambah alasan baru
    document.addEventListener('click', function(e) {
        if (e.target.id === 'add-feedback') {
            const container = document.getElementById('feedback-list');
            const clone = container.firstElementChild.cloneNode(true);
            clone.querySelectorAll('select, textarea').forEach(el => el.value = '');
            container.appendChild(clone);
        }
    });

    // ❌ Hapus alasan
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            const container = document.getElementById('feedback-list');
            if (container.children.length > 1) {
                e.target.closest('.feedback-item').remove();
            } else {
                Swal.fire('Minimal satu alasan harus ada', '', 'warning');
            }
        }
    });

    // ♻️ Reset modal setelah ditutup
    $('#feedbackModal').on('hidden.bs.modal', function() {
        const container = document.getElementById('feedback-list');
        const first = container.firstElementChild.cloneNode(true);
        first.querySelectorAll('select, textarea').forEach(el => el.value = '');
        container.innerHTML = '';
        container.appendChild(first);
    });

    // 🚀 Submit form revisi (feedback)
    document.getElementById('feedbackForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const spj_id = document.getElementById('feedback_spj_id').value;
        const formData = new FormData(this);

        const fieldNames = formData.getAll('field_name[]').filter(v => v);
        const messages = formData.getAll('message[]').filter(v => v);

        if (fieldNames.length === 0 || messages.length === 0) {
            Swal.fire('Lengkapi Form', 'Minimal satu alasan harus diisi lengkap.', 'warning');
            return;
        }

        try {
            const res = await fetch(`/spj/${spj_id}/revisi`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: formData
            });

            const data = await res.json();
            $('#feedbackModal').modal('hide');

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Feedback Dikirim',
                    text: data.message,
                    timer: 1800,
                    showConfirmButton: false
                });

                // ✅ Update badge tampilan status
                const badge = document.querySelector(`#dropdownMenuButton${spj_id}`);
                if (badge) {
                    badge.className = 'badge bg-danger text-white dropdown-toggle border-0';
                    badge.textContent = 'Tidak Disetujui';
                }

                // ✅ Update hidden field komentar lalu submit form utama
                document.getElementById(`komentar_${spj_id}`).value = messages.join('; ');
                document.getElementById(`form-${spj_id}`).submit();
            } else {
                Swal.fire('Gagal', data.message || 'Terjadi kesalahan server', 'error');
            }
        } catch (error) {
            console.error('Fetch error:', error);
            $('#feedbackModal').modal('hide');
            Swal.fire('Terjadi Kesalahan', 'Tidak dapat mengirim feedback ke server.', 'error');
        }
    });
});