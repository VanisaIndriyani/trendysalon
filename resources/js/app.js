import './bootstrap';

// Debug flag - set to false in production
const DEBUG = window.DEBUG !== false;

// Simple slider for the hero section
document.addEventListener('DOMContentLoaded', () => {
    const slider = document.getElementById('heroSlider');
    if (!slider) return;

    const slides = Array.from(slider.querySelectorAll('.slide'));
    const prevBtn = slider.querySelector('.prev');
    const nextBtn = slider.querySelector('.next');
    let index = 0;
    let sliderIntervalId = null;

    const show = (i) => {
        slides.forEach((s, idx) => {
            if (idx === i) {
                s.classList.remove('hidden');
            } else {
                s.classList.add('hidden');
            }
        });
    };

    const next = () => {
        index = (index + 1) % slides.length;
        show(index);
    };

    const prev = () => {
        index = (index - 1 + slides.length) % slides.length;
        show(index);
    };

    if (slides.length > 1) {
        nextBtn?.addEventListener('click', next);
        prevBtn?.addEventListener('click', prev);
        // Auto-play every 5 seconds
        sliderIntervalId = setInterval(next, 5000);
    }

    // Initial render (single or multiple)
    show(index);

    // Cleanup interval on page unload
    window.addEventListener('pagehide', () => {
        if (sliderIntervalId) {
            clearInterval(sliderIntervalId);
            sliderIntervalId = null;
        }
    });
});

// Mobile menu toggle
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('menuToggle');
    const panel = document.getElementById('mobileMenu');
    if (!toggle || !panel) return;

    toggle.addEventListener('click', () => {
        panel.classList.toggle('hidden');
    });

    panel.querySelectorAll('a').forEach((a) => {
        a.addEventListener('click', () => panel.classList.add('hidden'));
    });
});

// Scan form simple validation
document.addEventListener('DOMContentLoaded', () => {
    const submit = document.getElementById('scanSubmit');
    const form = document.getElementById('scanForm');
    if (!submit || !form) return;

    submit.addEventListener('click', () => {
        const name = document.getElementById('nameInput');
        const phone = document.getElementById('phoneInput');
        const length = document.getElementById('lengthSelect');
        const type = document.getElementById('typeSelect');
        const condition = document.getElementById('conditionSelect');

        const fields = [name, phone, length, type, condition];
        let valid = true;
        fields.forEach((el) => {
            if (!el || !el.value) {
                valid = false;
                el?.classList.add('border-pink-500');
            } else {
                el?.classList.remove('border-pink-500');
            }
        });

        if (!valid) {
            alert('Lengkapi semua kolom bertanda * sebelum melanjutkan scan.');
            return;
        }

        // Simpan identitas & preferensi pengguna untuk dianalisis bersama foto
        try {
            sessionStorage.setItem('userName', name?.value || '');
            sessionStorage.setItem('userPhone', phone?.value || '');
            sessionStorage.setItem('prefLength', length?.value || '');
            sessionStorage.setItem('prefType', type?.value || '');
            sessionStorage.setItem('prefCondition', condition?.value || '');
        } catch (err) {
            if (DEBUG) console.warn('sessionStorage setItem failed:', err);
        }

        const target = submit.getAttribute('data-target') || (window.__SCAN_ROUTES__?.camera || './camera');
        window.location.href = target;
    });
});

// Camera activation and capture -> results
document.addEventListener('DOMContentLoaded', async () => {
    const page = document.getElementById('scanCameraPage');
    if (!page) return;

    const video = document.getElementById('cameraVideo');
    const canvas = document.getElementById('cameraCanvas');
    const placeholder = document.getElementById('cameraPlaceholder');
    const captureBtn = document.getElementById('captureBtn');
    const switchBtn = document.getElementById('switchCameraBtn');
    const errEl = document.getElementById('cameraError');

    // Validasi elemen penting
    if (!(video instanceof HTMLVideoElement) || !(canvas instanceof HTMLCanvasElement)) {
        if (DEBUG) console.error('Camera elements missing or wrong type. Aborting camera init.');
        if (errEl) {
            errEl.classList.remove('hidden');
            errEl.textContent = 'Elemen kamera tidak ditemukan.';
        }
        return;
    }

    if (!(captureBtn instanceof HTMLElement) || !(switchBtn instanceof HTMLElement)) {
        if (DEBUG) console.warn('Capture or switch button missing — camera may work but capture is disabled.');
    }

    // Guard untuk getUserMedia availability
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        if (DEBUG) console.error('getUserMedia not supported by this browser.');
        if (errEl) {
            errEl.classList.remove('hidden');
            errEl.textContent = 'Kamera tidak didukung oleh browser ini.';
        }
        return;
    }

    let currentFacing = 'user';
    let activeStream = null;

    const startCamera = async (facing = 'user') => {
        try {
            if (activeStream) {
                activeStream.getTracks().forEach(t => t.stop());
                activeStream = null;
            }
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: facing } });
            activeStream = stream;
            video.srcObject = stream;
            await video.play().catch(() => {
                // Ignore autoplay block
            });
            video.classList.remove('hidden');
            placeholder?.classList.add('hidden');
            errEl?.classList.add('hidden');
        } catch (err) {
            if (DEBUG) console.error('Camera error:', err);
            if (errEl) {
                errEl.classList.remove('hidden');
                errEl.textContent = 'Gagal mengakses kamera. Periksa izin kamera.';
            }
        }
    };

    await startCamera(currentFacing);

    switchBtn?.addEventListener('click', async () => {
        currentFacing = currentFacing === 'user' ? 'environment' : 'user';
        await startCamera(currentFacing);
    });

    // Capture: gunakan canvas.toBlob untuk mengurangi ukuran payload
    captureBtn?.addEventListener('click', () => {
        if (video.readyState < 2) { // HAVE_CURRENT_DATA
            if (DEBUG) console.warn('Video not ready yet');
            return;
        }
        const width = video.videoWidth || 640;
        const height = video.videoHeight || 480;
        canvas.width = width;
        canvas.height = height;
        const ctx = canvas.getContext('2d');
        if (!ctx) {
            if (DEBUG) console.error('Canvas context missing');
            return;
        }
        ctx.drawImage(video, 0, 0, width, height);

        // Deteksi bentuk wajah sederhana berdasarkan rasio bingkai
        const ratio = width / height;
        let faceShape = 'oval';
        if (ratio >= 0.95 && ratio <= 1.05) faceShape = 'bulat';
        else if (ratio < 0.95) faceShape = 'lonjong';
        else if (ratio > 1.05) faceShape = 'oval';

        // Prefer canvas.toBlob untuk ukuran lebih kecil
        canvas.toBlob((blob) => {
            if (!blob) {
                if (DEBUG) console.error('Failed to create blob from canvas');
                return;
            }
            // Convert blob to dataURL untuk sessionStorage (karena objectURL tidak bisa diserialisasi)
            const reader = new FileReader();
            reader.onloadend = () => {
                const dataUrl = reader.result;
                try {
                    sessionStorage.setItem('scanImage', dataUrl);
                    sessionStorage.setItem('faceShape', faceShape);
                } catch (err) {
                    if (DEBUG) console.warn('sessionStorage setItem failed:', err);
                }
                const resultsUrl = window.__SCAN_ROUTES__?.results || './results';
                window.location.href = resultsUrl;
            };
            reader.onerror = () => {
                if (DEBUG) console.error('FileReader error');
            };
            reader.readAsDataURL(blob);
        }, 'image/jpeg', 0.8);
    });

    // Cleanup stream saat page unload
    window.addEventListener('pagehide', () => {
        if (activeStream) {
            activeStream.getTracks().forEach(t => t.stop());
            activeStream = null;
        }
    });
});

// Notification function untuk menampilkan alert/notifikasi
const showNotification = (type, title, message, duration = 5000) => {
    const toast = document.getElementById('notificationToast');
    const toastIcon = document.getElementById('toastIcon');
    const toastTitle = document.getElementById('toastTitle');
    const toastMessage = document.getElementById('toastMessage');
    const toastClose = document.getElementById('toastClose');
    
    if (!toast) return;
    
    const toastContainer = toast.querySelector('#toastContainer');
    
    // Set icon dan border berdasarkan type
    if (type === 'success') {
        toastIcon.innerHTML = '<div class="h-8 w-8 rounded-full grid place-items-center bg-green-100 text-green-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>';
        if (toastContainer) {
            toastContainer.classList.remove('border-red-300', 'border-yellow-300', 'border-blue-300');
            toastContainer.classList.add('border-green-300');
        }
    } else if (type === 'error') {
        toastIcon.innerHTML = '<div class="h-8 w-8 rounded-full grid place-items-center bg-red-100 text-red-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></div>';
        if (toastContainer) {
            toastContainer.classList.remove('border-green-300', 'border-yellow-300', 'border-blue-300');
            toastContainer.classList.add('border-red-300');
        }
    } else if (type === 'warning') {
        toastIcon.innerHTML = '<div class="h-8 w-8 rounded-full grid place-items-center bg-yellow-100 text-yellow-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div>';
        if (toastContainer) {
            toastContainer.classList.remove('border-green-300', 'border-red-300', 'border-blue-300');
            toastContainer.classList.add('border-yellow-300');
        }
    } else {
        toastIcon.innerHTML = '<div class="h-8 w-8 rounded-full grid place-items-center bg-blue-100 text-blue-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>';
        if (toastContainer) {
            toastContainer.classList.remove('border-green-300', 'border-red-300', 'border-yellow-300');
            toastContainer.classList.add('border-blue-300');
        }
    }
    
    toastTitle.textContent = title;
    toastMessage.textContent = message;
    
    // Show toast
    toast.classList.remove('hidden');
    toast.classList.add('block');
    
    // Auto hide setelah duration
    const autoHide = setTimeout(() => {
        hideNotification();
    }, duration);
    
    // Close button handler
    const closeHandler = () => {
        clearTimeout(autoHide);
        hideNotification();
        toastClose.removeEventListener('click', closeHandler);
    };
    toastClose.addEventListener('click', closeHandler);
};

const hideNotification = () => {
    const toast = document.getElementById('notificationToast');
    if (toast) {
        toast.classList.add('hidden');
        toast.classList.remove('block');
    }
};

// Results page image preview
document.addEventListener('DOMContentLoaded', () => {
    const page = document.getElementById('scanResultPage');
    if (!page) return;

    const dataUrl = sessionStorage.getItem('scanImage');
    const faceShape = sessionStorage.getItem('faceShape');
    // Normalize to API expected enum
    const normalizeFaceShapeForApi = (s) => {
        const v = (s || '').toLowerCase();
        if (v === 'oval') return 'Oval';
        if (v === 'bulat' || v === 'round') return 'Round';
        if (v === 'lonjong' || v === 'oblong') return 'Oblong';
        if (v === 'square' || v === 'kotak') return 'Square';
        if (v === 'heart' || v === 'hati') return 'Heart';
        return 'Oval';
    };
    const apiFaceShape = normalizeFaceShapeForApi(faceShape);
    const userName = sessionStorage.getItem('userName') || '';
    const userPhone = sessionStorage.getItem('userPhone') || '';
    const prefLength = sessionStorage.getItem('prefLength') || '';
    const prefType = sessionStorage.getItem('prefType') || '';
    const prefCondition = sessionStorage.getItem('prefCondition') || '';
    
    // Debug: Log semua data dari sessionStorage
    if (DEBUG) {
        console.log('📋 Data dari sessionStorage:', {
            hasImage: !!dataUrl,
            faceShape: faceShape || 'tidak ada',
            userName: userName || 'kosong',
            userPhone: userPhone || 'kosong',
            prefLength: prefLength || 'kosong',
            prefType: prefType || 'kosong',
            prefCondition: prefCondition || 'kosong'
        });
    }
    if (dataUrl) {
        const wrap = document.getElementById('capturePreview');
        const img = document.getElementById('captureImage');
        if (wrap && img) {
            img.src = dataUrl;
            wrap.classList.remove('hidden');
        }
    }

    // Fetch AI recommendations
    const tokenEl = document.querySelector('meta[name="csrf-token"]');
    const csrf = tokenEl ? (tokenEl.getAttribute('content') || tokenEl.content) : '';
    const loading = document.getElementById('loadingAnalysis');
    const list = document.getElementById('recommendations');
    if (!list) return;

    // Helper untuk membentuk URL gambar absolut
    const assetBase = (window.__ASSET_BASE__ || window.location.origin).replace(/\/$/, '');
    const resolveAsset = (u) => {
        if (!u) return '';
        if (u.startsWith('http') || u.startsWith('data:')) return u;
        if (u.startsWith('/')) return u;
        return `${assetBase}/${u.replace(/^\//,'')}`;
    };

    // Try-on elements
    const tryOn = document.getElementById('tryOnControls');
    const tryOnBase = document.getElementById('tryOnBase');
    const tryOnOverlay = document.getElementById('tryOnOverlay');
    const tryOnScale = document.getElementById('tryOnScale');
    const tryOnOffsetY = document.getElementById('tryOnOffsetY');
    const tryOnOffsetX = document.getElementById('tryOnOffsetX');
    const tryOnClose = document.getElementById('tryOnClose');
    if (tryOnBase && dataUrl) {
        tryOnBase.src = dataUrl;
    }

    // Default transform computed from FaceMesh (auto)
    let autoScaleDefault = null; // number 0.8..1.4
    let autoOffsetDefault = null; // px -40..40 (Y)
    let autoOffsetXDefault = null; // px -60..60 (X)

    const applyTransform = () => {
        const scale = Number(tryOnScale?.value || 100) / 100;
        const offsetY = Number(tryOnOffsetY?.value || 0);
        const offsetX = Number(tryOnOffsetX?.value || 0);
        if (tryOnOverlay) {
            tryOnOverlay.style.transform = `translateX(calc(-50% + ${offsetX}px)) translateY(${offsetY}px) scale(${scale})`;
        }
    };

    tryOnScale?.addEventListener('input', applyTransform);
    tryOnOffsetY?.addEventListener('input', applyTransform);
    tryOnOffsetX?.addEventListener('input', applyTransform);
    tryOnClose?.addEventListener('click', () => tryOn?.classList.add('hidden'));

    // Initialize FaceMesh on captured image to compute face bounding box
    const initFaceMesh = async () => {
        try {
            // Verify library is available
            const FaceMeshLib = window.FaceMesh;
            if (!FaceMeshLib || !FaceMeshLib.FaceMesh || !dataUrl) return;

            const stage = document.getElementById('tryOnStage');
            
            // Prepare image element
            const img = new Image();
            img.src = dataUrl;
            await img.decode();

            // Gunakan ukuran gambar asli untuk akurasi bounding box
            const W = img.naturalWidth || img.width || stage?.clientWidth || 288;
            const H = img.naturalHeight || img.height || stage?.clientHeight || 288;

            // Setup FaceMesh
            const faceMesh = new FaceMeshLib.FaceMesh({
                locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/face_mesh/${file}`,
            });
            faceMesh.setOptions({
                selfieMode: true,
                maxNumFaces: 1,
                refineLandmarks: true,
                minDetectionConfidence: 0.5,
                minTrackingConfidence: 0.5,
            });

            // Tambahkan timeout untuk mencegah hang jika onResults tidak terpanggil
            const TIMEOUT_MS = 10000; // 10 detik
            const resultsPromise = new Promise((resolve, reject) => {
                const timeoutId = setTimeout(() => {
                    reject(new Error('FaceMesh timeout: onResults tidak terpanggil dalam ' + TIMEOUT_MS + 'ms'));
                }, TIMEOUT_MS);
                
                faceMesh.onResults((results) => {
                    clearTimeout(timeoutId);
                    resolve(results);
                });
            });
            
            await faceMesh.send({ image: img });
            const results = await resultsPromise;
            const landmarks = results?.multiFaceLandmarks?.[0];
            if (!landmarks || !Array.isArray(landmarks)) return;

            // Compute bounding box from landmarks (normalized [0..1])
            let minX = 1, minY = 1, maxX = 0, maxY = 0;
            for (const p of landmarks) {
                if (p.x < minX) minX = p.x;
                if (p.y < minY) minY = p.y;
                if (p.x > maxX) maxX = p.x;
                if (p.y > maxY) maxY = p.y;
            }
            const faceW = (maxX - minX) * W;
            const faceH = (maxY - minY) * H;
            const faceCenterX = ((minX + maxX) / 2) * W;

            // Heuristic: hair overlay width ~ 1.6x face width
            const targetRatio = 1.6;
            let autoScale = faceW ? (targetRatio * faceW) / W : 1.0; // to 0.8..1.4
            autoScale = Math.max(0.8, Math.min(1.4, autoScale));

            // Offset Y: align top of hair to near forehead (use minY)
            let autoOffsetY = Math.round((minY * H) * -0.25); // up by 25% of forehead distance
            autoOffsetY = Math.max(-40, Math.min(40, autoOffsetY));

            // Offset X: align to face center, small proportion to avoid jump
            let autoOffsetX = Math.round((faceCenterX - W / 2) * 0.5);
            autoOffsetX = Math.max(-60, Math.min(60, autoOffsetX));

            autoScaleDefault = autoScale;
            autoOffsetDefault = autoOffsetY;
            autoOffsetXDefault = autoOffsetX;
        } catch (err) {
            if (DEBUG) console.warn('FaceMesh init error:', err);
        }
    };
    initFaceMesh();

    // Tampilkan badge bentuk wajah
    const shapeBadge = document.createElement('div');
    shapeBadge.className = 'mt-3 sm:mt-4 inline-flex items-center gap-1.5 sm:gap-2 rounded-xl bg-pink-100 px-2.5 sm:px-3 py-1.5 sm:py-2 text-[10px] sm:text-xs text-stone-700';
    shapeBadge.innerHTML = `<span class="h-1.5 w-1.5 sm:h-2 sm:w-2 rounded-full bg-pink-400 flex-shrink-0"></span> Bentuk wajah terdeteksi: <strong>${faceShape || 'oval'}</strong>`;
    list.before(shapeBadge);

    const render = (items) => {
        list.innerHTML = '';
        items.forEach((it, index) => {
            const card = document.createElement('div');
            card.className = 'rounded-xl bg-white shadow-md hover:shadow-xl px-2.5 sm:px-3 py-2.5 sm:py-3 touch-manipulation cursor-pointer active:scale-[0.98] transition-all duration-300 hover:scale-105 hover:-translate-y-1 group';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            card.innerHTML = `
                <div class="h-28 sm:h-36 rounded-lg bg-stone-200 overflow-hidden relative">
                    <img src="${it.image_url || '/img/model1.png'}" alt="${it.name || 'Model Rambut'}" class="h-28 sm:h-36 w-full object-cover transition-transform duration-500 group-hover:scale-110" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="bg-pink-500/90 text-white text-[8px] sm:text-[10px] px-2 py-1 rounded-full font-semibold">Coba</div>
                    </div>
                </div>
                <p class="mt-2 text-center text-xs sm:text-sm leading-tight px-1 font-medium group-hover:text-pink-600 transition-colors duration-300">${it.name || ''}</p>
            `;
            list.appendChild(card);
            
            // Staggered animation
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'scale(1)';
            }, index * 100);

            // Klik kartu -> coba overlay di wajah pengguna
            card.addEventListener('click', () => {
                if (tryOnOverlay) {
                    // Detach handler sebelumnya untuk mencegah loop
                    tryOnOverlay.onerror = null;
                    tryOnOverlay.onload = null;
                    
                    // Coba cari overlay PNG transparan berdasarkan nama model
                    const slug = (it.name || '')
                        .toLowerCase()
                        .replace(/[^a-z0-9]+/g, '-')
                        .replace(/^-|-$/g, '');
                    const overlayGuess = slug ? `/img/overlays/${slug}.png` : '';
                    let errorHandled = false;

                    // Fallback ke gambar biasa bila overlay tidak tersedia
                    tryOnOverlay.onerror = () => {
                        if (errorHandled) return; // Prevent loop
                        errorHandled = true;
                        tryOnOverlay.onerror = null; // Detach setelah digunakan
                        tryOnOverlay.src = it.image_url || '/img/model1.png';
                        applyTransform();
                        tryOn?.classList.remove('hidden');
                    };
                    tryOnOverlay.onload = () => {
                        tryOnOverlay.onload = null; // Detach setelah digunakan
                        applyTransform();
                        tryOn?.classList.remove('hidden');
                    };
                    tryOnOverlay.src = overlayGuess || (it.image_url || '/img/model1.png');
                    // Reset kontrol default
                    const scaleVal = autoScaleDefault ? String(Math.round(autoScaleDefault * 100)) : '100';
                    const offsetYVal = autoOffsetDefault !== null ? String(autoOffsetDefault) : '0';
                    const offsetXVal = autoOffsetXDefault !== null ? String(autoOffsetXDefault) : '0';
                    if (tryOnScale) tryOnScale.value = scaleVal;
                    if (tryOnOffsetY) tryOnOffsetY.value = offsetYVal;
                    if (tryOnOffsetX) tryOnOffsetX.value = offsetXVal;
                    // applyTransform dipanggil pada onload di atas
                }
            });
        });
    };

    // Helper untuk mengirim gambar: gunakan FormData jika payload terlalu besar
    const sendImage = async (imageDataUrl, faceShape, userName, userPhone, prefLength, prefType, prefCondition) => {
        const SIZE_LIMIT = 1024 * 700; // ~700KB
        const analyzeUrl = (window.__SCAN_ROUTES__ && window.__SCAN_ROUTES__.analyze) ? window.__SCAN_ROUTES__.analyze : '../analyze';
        
        // Estimate base64 size: length * 3/4
        if (imageDataUrl && imageDataUrl.length * 3 / 4 > SIZE_LIMIT) {
            // Upload blob via FormData untuk payload besar
            try {
                const blob = await (await fetch(imageDataUrl)).blob();
                const fd = new FormData();
                fd.append('image', blob, 'capture.jpg');
                fd.append('face_shape', faceShape || 'Oval');
                fd.append('user_name', userName || '');
                fd.append('user_phone', userPhone || '');
                fd.append('pref[length]', prefLength || '');
                fd.append('pref[type]', prefType || '');
                fd.append('pref[condition]', prefCondition || '');
                
                const resp = await fetch(analyzeUrl, {
                    method: 'POST',
                    body: fd,
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                    }
                });
                return resp;
            } catch (err) {
                if (DEBUG) console.warn('FormData upload failed, falling back to JSON:', err);
                // Fallback ke JSON jika FormData gagal
            }
        }
        
        // Gunakan JSON untuk payload kecil atau fallback
        const payload = {
            image: imageDataUrl || '',
            face_shape: faceShape || 'Oval',
            user_name: userName || '',
            user_phone: userPhone || '',
            pref: {
                length: prefLength || '',
                type: prefType || '',
                condition: prefCondition || '',
            },
        };
        
        return fetch(analyzeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        });
    };

    const analyze = async () => {
        if (DEBUG) console.log('🚀 ANALYZE FUNCTION CALLED');
        
        try {
            if (DEBUG) {
                console.log('📤 Sending analyze request:', {
                    hasImage: !!dataUrl,
                    face_shape: apiFaceShape,
                    user_name: userName || 'kosong',
                    user_phone: userPhone || 'kosong',
                    preferences: {
                        length: prefLength || 'kosong',
                        type: prefType || 'kosong',
                        condition: prefCondition || 'kosong'
                    }
                });
            }
            
            const analyzeUrl = (window.__SCAN_ROUTES__ && window.__SCAN_ROUTES__.analyze) ? window.__SCAN_ROUTES__.analyze : '../analyze';
            if (DEBUG) {
                console.log('🌐 Analyze URL:', analyzeUrl);
                console.log('🔑 CSRF Token:', csrf ? 'EXISTS' : 'MISSING');
            }
            
            const resp = await sendImage(dataUrl, apiFaceShape, userName, userPhone, prefLength, prefType, prefCondition);
            
            // Handle CORS headers dengan try/catch
            let responseHeaders = {};
            try {
                responseHeaders = Object.fromEntries(resp.headers.entries());
            } catch (err) {
                if (DEBUG) console.warn('Cannot read response headers (CORS restriction):', err);
            }
            
            if (DEBUG) {
                console.log('📥 Response received:', {
                    status: resp.status,
                    statusText: resp.statusText,
                    ok: resp.ok,
                    headers: responseHeaders
                });
            }
            
            if (!resp.ok) {
                const errorText = await resp.text();
                if (DEBUG) console.error('❌ HTTP error!', resp.status, errorText);
                throw new Error(`HTTP error! status: ${resp.status} - ${errorText}`);
            }
            
            // Error handling JSON parse dengan try/catch
            let json;
            try {
                const text = await resp.text();
                json = JSON.parse(text);
            } catch (parseErr) {
                if (DEBUG) console.error('❌ JSON parse error:', parseErr);
                throw new Error('Invalid JSON response from server');
            }
            
            if (DEBUG) {
                console.log('📥 Analyze response:', {
                    ok: json.ok,
                    saved: json.saved,
                    saved_id: json.saved_id,
                    save_error: json.save_error,
                    recommendations_count: json.recommendations?.length || 0
                });
            }
            
            // Tampilkan pesan jika data berhasil disimpan
            if (json.saved && json.saved_id) {
                showNotification('success', 'Data Berhasil Disimpan!', 
                    `Data Anda telah tersimpan dengan ID: ${json.saved_id}`, 4000);
                if (DEBUG) {
                    console.log('✅ Data berhasil disimpan ke database dengan ID:', json.saved_id);
                    console.log('📊 Data yang disimpan:', {
                        name: json.debug?.name_saved || userName || 'Pengguna',
                        phone: json.debug?.phone_saved || userPhone || '-',
                        face_shape: apiFaceShape,
                        recommendations: json.recommendations?.map(r => r.name).join(', ') || 'Tidak ada'
                    });
                    if (json.debug) {
                        console.log('🔍 Debug info:', json.debug);
                    }
                }
            } else if (json.save_error) {
                const errorMsg = json.save_error || 'Terjadi kesalahan saat menyimpan data';
                showNotification('error', 'Gagal Menyimpan Data!', 
                    `Error: ${errorMsg}. Silakan coba lagi atau hubungi admin.`, 8000);
                console.error('❌ Gagal menyimpan data ke database:', json.save_error);
                if (DEBUG) console.error('❌ Error details:', json);
            } else {
                showNotification('warning', 'Data Tidak Tersimpan!', 
                    'Data tidak berhasil disimpan ke database. Rekomendasi tetap ditampilkan, namun data tidak tersimpan untuk laporan.', 6000);
                if (DEBUG) {
                    console.warn('⚠️ Data tidak tersimpan (saved: false, tidak ada error)');
                    console.warn('⚠️ Response details:', json);
                }
            }
            
            loading?.classList.add('hidden');
            
            // PENTING: Render rekomendasi terlebih dahulu
            if (json && json.ok && json.recommendations && json.recommendations.length > 0) {
                render(json.recommendations);
                
                // Pastikan data tersimpan - jika tidak, tampilkan notifikasi lagi
                if (!json.saved) {
                    if (!json.save_error) {
                        // Jika tidak ada error tapi tidak tersimpan, tampilkan warning
                        showNotification('warning', 'Peringatan!', 
                            'Rekomendasi berhasil ditampilkan, namun data tidak tersimpan ke database. Silakan refresh halaman dan coba lagi.', 7000);
                    }
                    console.error('❌ CRITICAL: Data TIDAK tersimpan ke database meskipun rekomendasi ada!');
                    if (DEBUG) {
                        console.error('❌ Save status:', json.saved);
                        console.error('❌ Save error:', json.save_error);
                        console.error('❌ Response:', json);
                    }
                } else {
                    if (DEBUG) {
                        console.log('✅✅✅ DATA BERHASIL DISIMPAN KE DATABASE! ✅✅✅');
                        console.log('✅ ID:', json.saved_id);
                    }
                }
                
                return true; // Berhasil, tidak perlu fallback
            } else {
                if (DEBUG) console.warn('⚠️ Tidak ada rekomendasi dari analyze, menggunakan fallback');
                return false; // Gagal, perlu fallback
            }
        } catch (e) {
            const errorMsg = e.message || 'Terjadi kesalahan saat memproses request';
            showNotification('error', 'Error!', 
                `Gagal memproses analisis: ${errorMsg}. Silakan coba lagi.`, 8000);
            console.error('❌ Analyze error:', e);
            if (DEBUG) {
                console.error('❌ Error details:', {
                    message: e.message,
                    stack: e.stack,
                    name: e.name
                });
            }
            loading?.classList.add('hidden');
            return false; // Error, perlu fallback
        }
    };

    // Fallback loader that hits rule-based recommendation API when analyze isn't used
    // CATATAN: loadRecs() TIDAK menyimpan data ke database, hanya mengambil rekomendasi
    const loadRecs = async () => {
        if (DEBUG) {
            console.warn('⚠️ Using loadRecs() fallback - DATA TIDAK AKAN TERSIMPAN KE DATABASE!');
            console.warn('⚠️ Ini hanya untuk menampilkan rekomendasi, bukan untuk menyimpan data');
        }
        
        try {
            const injected = (window.__SCAN_ROUTES__ && window.__SCAN_ROUTES__.apiModels) ? window.__SCAN_ROUTES__.apiModels : null;
            const relative = '../api/recommendations/hair-models';
            const pickUrl = (u) => `${u}?face_shape=${encodeURIComponent(apiFaceShape)}`;

            let resp;
            let json;
            if (injected) {
                try {
                    resp = await fetch(pickUrl(injected));
                    if (!resp.ok) throw new Error(`Bad status ${resp.status}`);
                    json = await resp.json();
                } catch (e) {
                    if (DEBUG) console.warn('Injected URL failed, trying relative:', e);
                    resp = await fetch(pickUrl(relative));
                    json = await resp.json();
                }
            } else {
                resp = await fetch(pickUrl(relative));
                json = await resp.json();
            }

            const items = Array.isArray(json?.data) ? json.data : (Array.isArray(json) ? json : []);
            if (items.length) {
                render(items.map(m => ({ name: m.name, image_url: resolveAsset(m.image || m.illustration_url) })));
                if (DEBUG) console.warn('⚠️ PERINGATAN: Data user TIDAK tersimpan karena menggunakan fallback loadRecs()');
                return true;
            }
            return false;
        } catch (e) {
            console.error('Fallback loadRecs error:', e);
            if (DEBUG) console.error('Error details:', e);
            return false;
        }
    };
    
    // Panggil analyze() dan pastikan data tersimpan
    // analyze() akan menyimpan data ke database - INI WAJIB DIPANGGIL!
    if (DEBUG) {
        console.log('🎯 ========== STARTING ANALYZE PROCESS ==========');
        console.log('📋 Available data from sessionStorage:', {
            hasDataUrl: !!dataUrl,
            hasFaceShape: !!faceShape,
            hasUserName: !!userName,
            hasUserPhone: !!userPhone,
            hasPrefLength: !!prefLength,
            hasPrefType: !!prefType,
            hasPrefCondition: !!prefCondition,
            dataUrlLength: dataUrl ? dataUrl.length : 0,
            userNameValue: userName || 'KOSONG',
            userPhoneValue: userPhone || 'KOSONG'
        });
    }
    
    // Pastikan analyze() dipanggil - WAJIB untuk menyimpan data
    if (typeof analyze === 'function') {
        if (DEBUG) console.log('✅ analyze() function exists, calling it NOW...');
        // Panggil analyze() - INI WAJIB untuk menyimpan data ke database
        analyze().then((success) => {
            if (DEBUG) {
                console.log('📊 Analyze result:', success);
                if (success) {
                    console.log('✅ Analyze successful, data should be saved to database');
                } else {
                    console.log('⚠️ Analyze failed or no recommendations, trying fallback...');
                }
            }
            if (!success) {
                loadRecs().then((fallbackSuccess) => {
                    if (!fallbackSuccess) {
                        if (DEBUG) console.log('⚠️ Fallback also failed, showing default recommendations');
                        render([
                            { name: 'Oval Layer with Curtain Bangs', image_url: '/img/model1.png' },
                            { name: 'Butterfly Layer', image_url: '/img/model2.png' },
                            { name: 'Wolf Cut Long Hair', image_url: '/img/model3.png' },
                        ]);
                    }
                });
            }
        }).catch((error) => {
            console.error('❌ Analyze promise rejected:', error);
            if (DEBUG) {
                console.error('❌ Error stack:', error.stack);
                console.error('❌ Full error:', error);
            }
            loadRecs();
        });
    } else {
        console.error('❌ CRITICAL ERROR: analyze() function not found!');
        if (DEBUG) console.error('❌ This means data will NOT be saved to database!');
        // Fallback langsung
        loadRecs();
    }
});
