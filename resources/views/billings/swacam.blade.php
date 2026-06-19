@extends('layouts.app')

@section('content')
<div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
    <h2 class="page-title" style="margin-bottom: 0;">SwaCam Entry & OCR</h2>
</div>

<div class="card" style="max-width: 900px;">
    <div style="padding: 20px;">
        <!-- Tab Navigation -->
        <div style="display: flex; gap: 0; border-bottom: 2px solid #e5e7eb; margin-bottom: 20px;">
            <button class="tab-btn active" data-tab="tab-entry" style="padding: 12px 20px; border: none; background: none; cursor: pointer; font-size: 16px; border-bottom: 3px solid #465FFF; color: #465FFF; font-weight: 500; margin-bottom: -2px;">
                📋 New Entry
            </button>
            <button class="tab-btn" data-tab="tab-archive" style="padding: 12px 20px; border: none; background: none; cursor: pointer; font-size: 16px; border-bottom: 3px solid transparent; color: #8A99AF; font-weight: 500; margin-bottom: -2px;">
                📸 Photo Archive
            </button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Tab 1: Entry Form -->
            <div id="tab-entry" class="tab-pane active">
                <form id="swacamForm">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="swacam_pelanggan">Pilih Pelanggan <span class="text-danger">*</span></label>
                            <select id="swacam_pelanggan" name="pelanggan_id" class="form-control" required>
                                <option value="">-- Pilih Pelanggan --</option>
                                @foreach($pelanggans ?? [] as $pelanggan)
                                <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama }} ({{ $pelanggan->no_pelanggan }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="swacam_no_invoice">No Invoice</label>
                            <input type="text" id="swacam_no_invoice" class="form-control" readonly style="background: #f5f5f5;">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="swacam_meter_awal">Meter Awal</label>
                            <input type="number" id="swacam_meter_awal" class="form-control" readonly style="background: #f5f5f5;">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="swacam_meter_reading">Meter Akhir <span class="text-danger">*</span></label>
                            <input type="number" id="swacam_meter_reading" name="meter_reading" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="swacam_pemakaian">Pemakaian (m³)</label>
                        <input type="number" id="swacam_pemakaian" class="form-control" readonly style="background: #f5f5f5;">
                    </div>

                    <div class="form-group">
                        <label for="swacam_photo">Upload Foto Meteran</label>
                        <div class="file-upload-area" id="swacamPhotoArea" style="border: 2px dashed #ccc; border-radius: 8px; padding: 20px; text-align: center; cursor: pointer; background: #f9f9f9; transition: 0.3s;">
                            <input type="file" id="swacam_photo" name="photo" accept="image/*" hidden>
                            <div id="photoPreviewArea">
                                <i class='bx bx-image-add' style="font-size: 32px; color: #999;"></i>
                                <p style="color: #666; margin-top: 8px;">Drag & drop foto meteran atau klik untuk memilih</p>
                            </div>
                        </div>
                        <small style="color: #666; display: block; margin-top: 8px;">Format: JPG, PNG | Ukuran: Maks 5MB</small>
                    </div>

                    <!-- Quality Metrics -->
                    <div id="qualityMetrics" style="display: none; margin: 20px 0; padding: 15px; background: #f5f5f5; border-radius: 8px;">
                        <h6 style="margin-bottom: 12px;">📊 Analisis Foto</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <small><strong>Kualitas Foto:</strong></small>
                                <div class="progress" style="height: 8px; margin: 5px 0;">
                                    <div id="qualityBar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <small id="qualityScore">0%</small>
                            </div>
                            <div class="col-md-6">
                                <small><strong>OCR Confidence:</strong></small>
                                <div class="progress" style="height: 8px; margin: 5px 0;">
                                    <div id="ocrBar" class="progress-bar bg-info" role="progressbar" style="width: 0%"></div>
                                </div>
                                <small id="ocrScore">0%</small>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-6">
                                <small><span id="blurWarning" style="display: none; color: #dc3545;"><i class='bx bx-alert'></i> Foto blur terdeteksi</span></small>
                            </div>
                            <div class="col-md-6">
                                <small><span id="brightnessWarning" style="display: none; color: #ffc107;"><i class='bx bx-alert'></i> Kecerahan kurang</span></small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="swacam_periode">Periode (YYYY-MM)</label>
                        <input type="month" id="swacam_periode" name="periode" class="form-control">
                    </div>

                    <div style="margin-top: 24px; text-align: right; display: flex; gap: 12px; justify-content: flex-end;">
                        <a href="{{ route('billings.index') }}" class="btn btn-secondary" style="background: var(--body-bg); color: var(--text-main);">Batal</a>
                        <button type="submit" class="btn btn-primary" id="submitSwacamBtn">Hitung & Simpan Tagihan</button>
                    </div>
                </form>
            </div>

            <!-- Tab 2: Photo Archive -->
            <div id="tab-archive" class="tab-pane" style="display: none;">
                <div id="archiveContainer" style="max-height: 600px; overflow-y: auto;">
                    <p style="text-align: center; color: #999;">Memuat galeri...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Photo Viewer Modal -->
    <div id="photoViewerModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 1051; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 8px; max-width: 90%; max-height: 90vh; padding: 20px; position: relative;">
            <button type="button" id="closePhotoViewerBtn" style="position: absolute; top: 10px; right: 15px; background: none; border: none; font-size: 24px; cursor: pointer; color: #666;">
                ×
            </button>
            <div style="text-align: center;">
                <img id="photoViewerImage" src="" style="max-width: 100%; max-height: 70vh; border-radius: 8px;">
            </div>
        </div>
    </div>

    <script>
        function showModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        function hideModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('closePhotoViewerBtn').addEventListener('click', () => hideModal('photoViewerModal'));
            document.getElementById('photoViewerModal').addEventListener('click', function(e) {
                if (e.target === this) hideModal('photoViewerModal');
            });

            const form = document.getElementById('swacamForm');
            const pelangganSelect = document.getElementById('swacam_pelanggan');
            const photoInput = document.getElementById('swacam_photo');
            const photoArea = document.getElementById('swacamPhotoArea');
            const qualityMetrics = document.getElementById('qualityMetrics');
            const meterReading = document.getElementById('swacam_meter_reading');
            const pemakaianField = document.getElementById('swacam_pemakaian');

            // Handle pelanggan selection
            pelangganSelect.addEventListener('change', async function() {
                if (!this.value) {
                    document.getElementById('swacam_meter_awal').value = '';
                    document.getElementById('swacam_no_invoice').value = '';
                    pemakaianField.value = '';
                    return;
                }

                try {
                    const response = await fetch(`/api/billings/last-meter/${this.value}`);
                    const data = await response.json();
                    document.getElementById('swacam_meter_awal').value = data.meter_awal || 0;

                    // Generate no invoice
                    const invoiceResponse = await fetch(`/api/billings/generate-invoice`);
                    const invoiceData = await invoiceResponse.json();
                    document.getElementById('swacam_no_invoice').value = invoiceData.no_invoice;

                    // Reload submission history for selected pelanggan
                    loadSubmissionHistory();
                } catch (error) {
                    console.error('Error:', error);
                }
            });

            // Calculate pemakaian when meter_reading changes
            meterReading.addEventListener('input', function() {
                const meterAwal = parseFloat(document.getElementById('swacam_meter_awal').value) || 0;
                const pemakaian = parseFloat(this.value) - meterAwal;
                pemakaianField.value = pemakaian >= 0 ? pemakaian : 0;
            });

            // Handle photo upload
            photoArea.addEventListener('click', () => photoInput.click());
            photoArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                photoArea.style.background = '#e8f5e9';
            });
            photoArea.addEventListener('dragleave', () => {
                photoArea.style.background = '#f9f9f9';
            });
            photoArea.addEventListener('drop', (e) => {
                e.preventDefault();
                photoArea.style.background = '#f9f9f9';
                const files = e.dataTransfer.files;
                if (files.length) photoInput.files = files;
                handlePhotoUpload();
            });

            photoInput.addEventListener('change', handlePhotoUpload);

            // Handle form submission
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const submitBtn = document.getElementById('submitSwacamBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bx bx-loader-alt spin"></i> Processing...';

                try {
                    const formData = new FormData(form);
                    const response = await fetch('{{ route("swacam.store") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        }
                    });

                    const result = await response.json();

                    if (response.ok) {
                        alert('✓ Submission berhasil disimpan!');
                        form.reset();
                        qualityMetrics.style.display = 'none';
                        pemakaianField.value = '';
                        document.getElementById('swacam_no_invoice').value = '';
                        loadSubmissionHistory();
                    } else {
                        alert('❌ Error: ' + (result.message || 'Submission gagal'));
                    }
                } catch (error) {
                    alert('❌ Error: ' + error.message);
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bx bx-check"></i> Submit';
                }
            });

            // Tab switching
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const tabName = this.getAttribute('data-tab');
                    switchTab(tabName);

                    if (tabName === 'tab-archive') loadPhotoArchive();
                });
            });

            // Load submission history on page load
            loadSubmissionHistory();
        });

        function switchTab(tabName) {
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.style.display = 'none';
                pane.classList.remove('active');
            });

            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
                btn.style.borderBottomColor = 'transparent';
                btn.style.color = '#8A99AF';
            });

            const selectedTab = document.getElementById(tabName);
            if (selectedTab) {
                selectedTab.style.display = 'block';
                selectedTab.classList.add('active');
            }

            const selectedBtn = document.querySelector(`[data-tab="${tabName}"]`);
            if (selectedBtn) {
                selectedBtn.classList.add('active');
                selectedBtn.style.borderBottomColor = '#465FFF';
                selectedBtn.style.color = '#465FFF';
            }
        }

        function handlePhotoUpload() {
            const input = document.getElementById('swacam_photo');
            const file = input.files[0];

            if (!file) return;

            const reader = new FileReader();
            reader.onload = async function(e) {
                const img = new Image();
                img.onload = function() {
                    const previewArea = document.getElementById('photoPreviewArea');
                    previewArea.innerHTML = `
                    <img src="${e.target.result}" style="max-width: 100%; max-height: 150px; border-radius: 4px;">
                    <p style="color: #666; margin-top: 8px; font-size: 12px;">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                `;

                    analyzeImageQuality(img, e.target.result);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        async function analyzeImageQuality(img, dataUrl) {
            const metrics = document.getElementById('qualityMetrics');
            metrics.style.display = 'block';

            const canvas = document.createElement('canvas');
            canvas.width = img.width;
            canvas.height = img.height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0);

            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const data = imageData.data;
            let brightness = 0;

            for (let i = 0; i < data.length; i += 4) {
                brightness += (data[i] + data[i + 1] + data[i + 2]) / 3;
            }
            brightness = Math.round(brightness / (data.length / 4) * 100 / 255);

            let blurScore = calculateSharpness(imageData);

            const qualityScore = Math.round((brightness + blurScore) / 2);

            const qualityBar = document.getElementById('qualityBar');
            qualityBar.style.width = qualityScore + '%';
            qualityBar.className = 'progress-bar ' + (qualityScore >= 70 ? 'bg-success' : qualityScore >= 50 ? 'bg-warning' : 'bg-danger');
            document.getElementById('qualityScore').textContent = qualityScore + '%';

            document.getElementById('ocrBar').style.width = qualityScore + '%';
            document.getElementById('ocrScore').textContent = qualityScore + '%';

            if (blurScore < 50) {
                document.getElementById('blurWarning').style.display = 'inline';
            } else {
                document.getElementById('blurWarning').style.display = 'none';
            }

            if (brightness < 40) {
                document.getElementById('brightnessWarning').style.display = 'inline';
            } else {
                document.getElementById('brightnessWarning').style.display = 'none';
            }
        }

        function calculateSharpness(imageData) {
            const data = imageData.data;
            const width = imageData.width;
            const height = imageData.height;
            let laplacian = 0;
            let count = 0;

            for (let i = width * 4 + 4; i < data.length - width * 4 - 4; i += 4) {
                if ((i / 4) % width === 0 || (i / 4) % width === width - 1) continue;

                const pixel = (data[i] + data[i + 1] + data[i + 2]) / 3;
                const left = (data[i - 4] + data[i - 3] + data[i - 2]) / 3;
                const right = (data[i + 4] + data[i + 5] + data[i + 6]) / 3;
                const top = (data[i - width * 4] + data[i - width * 4 + 1] + data[i - width * 4 + 2]) / 3;
                const bottom = (data[i + width * 4] + data[i + width * 4 + 1] + data[i + width * 4 + 2]) / 3;

                laplacian += Math.abs(4 * pixel - left - right - top - bottom);
                count++;
            }

            return Math.min(100, Math.round((laplacian / count) * 10));
        }

        async function loadSubmissionHistory() {
            const container = document.getElementById('historyContainer');
            const pelangganId = document.getElementById('swacam_pelanggan').value;

            try {
                let url = '{{ route("swacam.history") }}';
                if (pelangganId) {
                    url += `?pelanggan_id=${pelangganId}`;
                }

                const response = await fetch(url);
                const submissions = await response.json();

                if (!submissions.length) {
                    container.innerHTML = '<p style="text-align: center; color: #999;">Tidak ada submission history</p>';
                    return;
                }

                let html = `
                    <div class="table-wrapper">
                        <table class="table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>No Tagihan</th>
                                    <th>Pelanggan</th>
                                    <th>Periode</th>
                                    <th>Pemakaian</th>
                                    <th>Total Tagihan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                submissions.forEach(sub => {
                    const bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    const [tahun, bulan] = sub.periode.split('-');
                    const namaBulan = bulanList[parseInt(bulan) - 1];

                    const totalTagihan = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(sub.total_tagihan);

                    html += `
                        <tr>
                            <td style="font-weight: 500;">${sub.no_invoice}</td>
                            <td>
                                <div>${sub.pelanggan.nama}</div>
                                <small style="color: var(--text-muted);">${sub.pelanggan.no_pelanggan}</small>
                            </td>
                            <td>${namaBulan} ${tahun}</td>
                            <td>
                                ${sub.pemakaian} m³<br>
                                <small style="color: var(--text-muted);">(${sub.meter_awal} - ${sub.meter_akhir})</small>
                            </td>
                            <td style="font-weight: 600;">${totalTagihan}</td>
                            <td>
                                <span class="badge" style="background: #d1fae5; color: #065f46;">Tersimpan</span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    ${sub.photo_path ? `<button onclick="viewPhoto('${sub.photo_path}')" class="btn btn-primary btn-sm" style="background: #f1f5f9; color: var(--primary); padding: 4px 8px; border: none; border-radius: 4px; cursor: pointer;">
                                        <i class='bx bx-image'></i>
                                    </button>` : ''}
                                </div>
                            </td>
                        </tr>
                    `;
                });

                html += `
                            </tbody>
                        </table>
                    </div>
                `;

                container.innerHTML = html;
            } catch (error) {
                container.innerHTML = '<p style="color: #d32f2f;">Error loading history: ' + error.message + '</p>';
            }
        }

        async function loadPhotoArchive() {
            const container = document.getElementById('archiveContainer');
            const pelangganId = document.getElementById('swacam_pelanggan').value;

            if (!pelangganId) {
                container.innerHTML = '<p style="text-align: center; color: #999;">Pilih pelanggan terlebih dahulu</p>';
                return;
            }

            try {
                const response = await fetch(`{{ route("swacam.archive") }}?pelanggan_id=${pelangganId}`);
                const submissions = await response.json();

                if (!submissions.filter(s => s.photo_path).length) {
                    container.innerHTML = '<p style="text-align: center; color: #999;">Tidak ada foto tersimpan untuk pelanggan ini</p>';
                    return;
                }

                let html = '<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 12px;">';
                submissions.forEach(sub => {
                    if (sub.photo_path) {
                        html += `
                        <div style="position: relative; border-radius: 8px; overflow: hidden; cursor: pointer;" onclick="viewPhoto('${sub.photo_path}')">
                            <img src="${sub.photo_path}" style="width: 100%; height: 120px; object-fit: cover; background: #f0f0f0;">
                            <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.7); color: white; padding: 4px; font-size: 10px;">
                                ${sub.no_invoice}
                            </div>
                        </div>
                    `;
                    }
                });
                html += '</div>';
                container.innerHTML = html;
            } catch (error) {
                container.innerHTML = '<p style="color: #d32f2f;">Error loading archive: ' + error.message + '</p>';
            }
        }

        function viewPhoto(photoPath) {
            document.getElementById('photoViewerImage').src = photoPath;
            showModal('photoViewerModal');
        }
    </script>


    @endsection
