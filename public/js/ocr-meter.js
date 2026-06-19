// OCR Meter Module menggunakan Tesseract.js

export async function initOCR() {
    // Load Tesseract.js dari CDN
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js';
    script.async = true;
    document.head.appendChild(script);
}

export function setupOCRListeners() {
    const fileInput = document.getElementById('meter_foto');
    const previewContainer = document.getElementById('preview_container');
    const scanBtn = document.getElementById('btn_scan_ocr');
    const clearBtn = document.getElementById('btn_clear_ocr');
    const loadingDiv = document.getElementById('ocr_loading');

    if (!fileInput || !scanBtn) return;

    // Handle OCR scan
    scanBtn.addEventListener('click', async function() {
        const file = fileInput.files[0];
        if (!file) {
            document.getElementById('ocr_result').innerHTML = `
                <div style="background: #fee2e2; padding: 12px; border-radius: 8px; color: #7f1d1d; border-left: 4px solid #ef4444;">
                    <strong>⚠ Pilih foto terlebih dahulu!</strong>
                </div>
            `;
            return;
        }

        // Show loading
        loadingDiv.style.display = 'block';
        scanBtn.disabled = true;
        scanBtn.innerHTML = '<span style="display: inline-block; width: 16px; height: 16px; border: 2px solid #fff; border-top: 2px solid #3b82f6; border-radius: 50%; animation: spin 1s linear infinite; margin-right: 8px;"></span> Memproses...';

        try {
            // Read file
            const reader = new FileReader();
            reader.onload = async function(event) {
                const imageData = event.target.result;

                // Use Tesseract.js for OCR
                const { data: { text } } = await Tesseract.recognize(
                    imageData,
                    'eng',
                    {
                        logger: m => console.log('OCR Progress:', m)
                    }
                );

                // Extract numbers from OCR text
                const numbers = text.match(/\d+/g);
                
                if (numbers && numbers.length > 0) {
                    // Ambil angka terbesar (biasanya meter reading)
                    const largestNumber = Math.max(...numbers.map(n => parseInt(n)));
                    
                    // Jika angka masuk akal (tidak lebih dari 9999 untuk meter air)
                    if (largestNumber <= 9999 && largestNumber > 0) {
                        document.getElementById('meter_akhir').value = largestNumber;
                        document.getElementById('ocr_result').innerHTML = `
                            <div style="background: #d1fae5; padding: 12px; border-radius: 8px; color: #065f46; border-left: 4px solid #10b981;">
                                <strong>✓ Hasil OCR:</strong> <span style="font-size: 18px; font-weight: 700;">${largestNumber}</span> m³
                            </div>
                        `;
                        
                        // Trigger calculation
                        const event = new Event('input', { bubbles: true });
                        document.getElementById('meter_akhir').dispatchEvent(event);
                    } else {
                        // Tampilkan semua angka yang ditemukan untuk dipilih user
                        const numberOptions = numbers.map((n, i) => `<option value="${n}">${n}</option>`).join('');
                        document.getElementById('ocr_result').innerHTML = `
                            <div style="background: #fef3c7; padding: 12px; border-radius: 8px; color: #92400e; border-left: 4px solid #f59e0b;">
                                <strong>⚠ Angka ditemukan, pilih yang benar:</strong>
                                <select id="meter_select" onchange="selectMeterValue(this.value)" style="margin-top: 8px; padding: 8px; width: 100%; border: 1px solid #d1d5db; border-radius: 4px;">
                                    <option value="">-- Pilih angka yang benar --</option>
                                    ${numberOptions}
                                </select>
                            </div>
                        `;
                    }
                } else {
                    document.getElementById('ocr_result').innerHTML = `
                        <div style="background: #fee2e2; padding: 12px; border-radius: 8px; color: #7f1d1d; border-left: 4px solid #ef4444;">
                            <strong>✗ Tidak ada angka ditemukan</strong><br>
                            <small>Pastikan foto meteran jelas, terang, dan angkanya terlihat dengan baik.</small>
                        </div>
                    `;
                }
            };
            reader.readAsDataURL(file);

        } catch (error) {
            console.error('OCR Error:', error);
            document.getElementById('ocr_result').innerHTML = `
                <div style="background: #fee2e2; padding: 12px; border-radius: 8px; color: #7f1d1d; border-left: 4px solid #ef4444;">
                    <strong>✗ Error:</strong> ${error.message}
                </div>
            `;
        } finally {
            loadingDiv.style.display = 'none';
            scanBtn.disabled = false;
            scanBtn.innerHTML = '<i class="bx bx-scan"></i> Scan dengan OCR';
        }
    });
}

// Fungsi untuk select angka dari dropdown hasil OCR
window.selectMeterValue = function(value) {
    if (value) {
        document.getElementById('meter_akhir').value = value;
        const event = new Event('input', { bubbles: true });
        document.getElementById('meter_akhir').dispatchEvent(event);
        
        document.getElementById('ocr_result').innerHTML = `
            <div style="background: #d1fae5; padding: 12px; border-radius: 8px; color: #065f46; border-left: 4px solid #10b981;">
                <strong>✓ Terpilih:</strong> <span style="font-size: 18px; font-weight: 700;">${value}</span> m³
            </div>
        `;
    }
};
