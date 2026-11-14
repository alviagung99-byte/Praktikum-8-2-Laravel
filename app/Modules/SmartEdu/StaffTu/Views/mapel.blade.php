@extends('layouts.dashboard')

@section('title', 'Manajemen Mata Pelajaran')
@section('sidebar-subtitle', 'Admin Panel')
@section('page-title', 'Manajemen Mata Pelajaran')
@section('user-initials', 'TU')

@section('sidebar-menu')
    <a href="{{ route('staff-tu.dashboard') }}">🏠 Beranda</a>
    <a href="{{ route('staff-tu.pengguna') }}">👥 Manajemen Pengguna</a>
    <a href="{{ route('staff-tu.kelas') }}">🏫 Manajemen Kelas</a>
    <a href="{{ route('staff-tu.mapel') }}" class="active">📚 Manajemen Mapel</a>
@endsection

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h2 style="font-size:1.6rem;font-weight:800;">📚 Manajemen Mata Pelajaran</h2>
        <p style="color:#64748b;margin-top:.25rem;">Kelola mata pelajaran yang diajarkan di sekolah.</p>
    </div>
</div>

@if (session('success'))
    <div style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;border-radius:10px;padding:.8rem 1rem;margin-bottom:1rem;">
        {{ session('success') }}
    </div>
@endif

<div id="mapelStatus" style="display:none;background:#dbeafe;color:#1d4ed8;border:1px solid #bfdbfe;border-radius:10px;padding:.8rem 1rem;margin-bottom:1rem;"></div>

<div class="card" style="margin-bottom:1.5rem;">
    <h3 style="margin-bottom:1rem;">📁 Tambah / Edit Mata Pelajaran</h3>
    <form id="mapelForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="mapelId" name="mapel_id" value="">
        <div style="display:grid;grid-template-columns:1fr 1fr 1.5fr;gap:1rem;align-items:end;">
            <div class="form-group">
                <label class="form-label" for="kode_mapel">Kode Mapel</label>
                <input type="text" id="kode_mapel" name="kode_mapel" class="form-control" placeholder="contoh: MTK" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="nama_mapel">Nama Mata Pelajaran</label>
                <input type="text" id="nama_mapel" name="nama_mapel" class="form-control" placeholder="contoh: Matematika" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="file">Upload File</label>
                <input type="file" id="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png" required>
            </div>
        </div>
        <div style="margin-top:1rem;display:flex;gap:.75rem;flex-wrap:wrap;">
            <button type="submit" id="mapelSubmit" class="btn btn-primary">⬆️ Simpan Mapel</button>
            <button type="button" id="cancelMapelEdit" class="btn btn-secondary" style="display:none;">❌ Batal Edit</button>
        </div>
    </form>
</div>

<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;flex-wrap:wrap;gap:1rem;">
        <h3 style="margin:0;">Daftar Mata Pelajaran</h3>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Kode Mapel</th>
                    <th>Nama Mata Pelajaran</th>
                    <th>File</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="mapelTableBody"></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const mapelTableBody = document.getElementById('mapelTableBody');
    const mapelForm = document.getElementById('mapelForm');
    const mapelStatus = document.getElementById('mapelStatus');
    const cancelMapelEdit = document.getElementById('cancelMapelEdit');
    const mapelSubmit = document.getElementById('mapelSubmit');
    const fileInput = document.getElementById('file');

    function showStatus(message, tone = 'info') {
        mapelStatus.textContent = message;
        mapelStatus.style.display = 'block';
        mapelStatus.style.background = tone === 'success' ? '#dcfce7' : tone === 'error' ? '#fee2e2' : '#dbeafe';
        mapelStatus.style.color = tone === 'success' ? '#166534' : tone === 'error' ? '#991b1b' : '#1d4ed8';
        mapelStatus.style.borderColor = tone === 'success' ? '#bbf7d0' : tone === 'error' ? '#fecaca' : '#bfdbfe';
    }

    function resetMapelForm() {
        mapelForm.reset();
        document.getElementById('mapelId').value = '';
        fileInput.required = true;
        cancelMapelEdit.style.display = 'none';
        mapelSubmit.textContent = '⬆️ Simpan Mapel';
    }

    function renderMapel(mapel) {
        mapelTableBody.replaceChildren();

        if (!Array.isArray(mapel) || mapel.length === 0) {
            const row = mapelTableBody.insertRow();
            const cell = row.insertCell();
            cell.colSpan = 4;
            cell.textContent = 'Belum ada data mapel.';
            cell.style.textAlign = 'center';
            cell.style.color = '#64748b';
            return;
        }

        mapel.forEach((item) => {
            const row = mapelTableBody.insertRow();
            const codeCell = row.insertCell();
            const code = document.createElement('code');
            code.textContent = item.kode || '';
            codeCell.appendChild(code);

            const nameCell = row.insertCell();
            const name = document.createElement('strong');
            name.textContent = item.nama || '';
            nameCell.appendChild(name);

            const fileCell = row.insertCell();
            if (item.path_file && item.path_file.startsWith('/') && !item.path_file.startsWith('//')) {
                const link = document.createElement('a');
                link.href = item.path_file;
                link.target = '_blank';
                link.rel = 'noopener noreferrer';
                link.textContent = item.nama_file || 'Buka file';
                fileCell.appendChild(link);
            } else {
                fileCell.textContent = 'Tidak ada file';
            }

            const actionCell = row.insertCell();
            ['edit-mapel', 'delete-mapel'].forEach((action) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.dataset.action = action;
                button.dataset.id = item.id;
                button.textContent = action === 'edit-mapel' ? '✏️ Edit' : '🗑️ Hapus';
                button.style.border = 'none';
                button.style.background = 'none';
                button.style.cursor = 'pointer';
                button.style.padding = '0';
                button.style.fontWeight = '600';
                button.style.color = action === 'edit-mapel' ? '#2563eb' : '#dc2626';
                if (action === 'edit-mapel') {
                    button.style.marginRight = '.75rem';
                }
                actionCell.appendChild(button);
            });
        });
    }

    async function loadMapel() {
        try {
            const response = await fetch('{{ route('staff-tu.mapel.data') }}');
            const data = await response.json();
            renderMapel(data);
        } catch (error) {
            renderMapel([]);
            showStatus('Gagal memuat data mapel.', 'error');
        }
    }

    async function submitMapel(event) {
        event.preventDefault();

        const formData = new FormData(mapelForm);
        const mapelId = document.getElementById('mapelId').value;
        const isEdit = Boolean(mapelId);
        const csrfToken = document.querySelector('input[name="_token"]').value;

        if (!isEdit && !formData.get('file')) {
            showStatus('Silakan pilih file mapel terlebih dahulu.', 'error');
            return;
        }

        if (isEdit) {
            formData.append('_method', 'PUT');
            formData.delete('file');
            if (fileInput.files.length > 0) {
                formData.set('file', fileInput.files[0]);
            }
        }

        try {
            const url = isEdit
                ? `{{ url('/staff-tu/mapel') }}/${mapelId}/ajax`
                : '{{ route('staff-tu.mapel.ajax.store') }}';

            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json().catch(() => ({}));

            if (!response.ok) {
                throw new Error(result.message || 'Operasi mapel gagal.');
            }

            showStatus(result.message || 'Data mapel berhasil disimpan.', 'success');
            resetMapelForm();
            await loadMapel();
        } catch (error) {
            showStatus(error.message || 'Terjadi kesalahan saat menyimpan data mapel.', 'error');
        }
    }

    document.addEventListener('click', async function (event) {
        const actionButton = event.target.closest('[data-action]');
        if (!actionButton) {
            return;
        }

        const action = actionButton.dataset.action;
        const id = actionButton.dataset.id;

        if (action === 'delete-mapel') {
            if (!confirm('Hapus mapel ini?')) {
                return;
            }

            try {
                const response = await fetch(`{{ url('/staff-tu/mapel') }}/${id}/ajax`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(result.message || 'Gagal menghapus mapel.');
                }

                showStatus(result.message || 'Mapel berhasil dihapus.', 'success');
                await loadMapel();
            } catch (error) {
                showStatus(error.message || 'Gagal menghapus mapel.', 'error');
            }

            return;
        }

        if (action === 'edit-mapel') {
            const data = await fetch('{{ route('staff-tu.mapel.data') }}').then((res) => res.json());
            const item = data.find((entry) => String(entry.id) === String(id));

            if (!item) {
                showStatus('Data mapel tidak ditemukan.', 'error');
                return;
            }

            document.getElementById('mapelId').value = item.id;
            document.getElementById('kode_mapel').value = item.kode || '';
            document.getElementById('nama_mapel').value = item.nama || '';
            fileInput.required = false;
            cancelMapelEdit.style.display = 'inline-block';
            mapelSubmit.textContent = '✏️ Update Mapel';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    cancelMapelEdit.addEventListener('click', resetMapelForm);
    mapelForm.addEventListener('submit', submitMapel);
    loadMapel();
</script>
@endpush
