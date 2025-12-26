/**
 * Skrypt dla strony podglądu kamer
 * Obsługuje streaming, kontrolę kamer i przełączanie widoków
 */

let currentCamera = null;
let streamActive = false;
let streamInterval = null;

document.addEventListener('DOMContentLoaded', function() {
    if (typeof mainCameraId !== 'undefined' && mainCameraId) {
        currentCamera = mainCameraId;
        initializeMainCamera();
    }
});

/**
 * Inicjalizuje główną kamerę
 */
function initializeMainCamera() {
    console.log('Główna kamera zainicjalizowana:', currentCamera);
    // TODO: Rozpocznij rzeczywisty streaming gdy będzie dostępny
}

/**
 * Przełącza kamerę
 */
function switchCamera(cameraId) {
    if (currentCamera === cameraId) return;
    
    const camera = cameras.find(c => c.id === cameraId);
    if (!camera) return;
    
    currentCamera = cameraId;
    
    // Aktualizuj główny obraz
    const mainStream = document.getElementById('mainCameraStream');
    const cameraLabel = document.querySelector('.camera-label');
    
    if (mainStream) {
        mainStream.src = camera.stream_url;
    }
    
    if (cameraLabel) {
        const statusDot = cameraLabel.querySelector('.status-dot');
        if (statusDot) {
            statusDot.className = `status-dot ${camera.status_color}`;
        }
        cameraLabel.innerHTML = `
            <span class="status-dot ${camera.status_color}"></span>
            Kamera - ${camera.name}
        `;
    }
    
    // Podświetl wybraną miniaturę
    document.querySelectorAll('.camera-thumbnail').forEach(thumb => {
        thumb.classList.remove('active');
    });
    
    const selectedThumb = document.querySelector(`[data-camera-id="${cameraId}"]`);
    if (selectedThumb) {
        selectedThumb.classList.add('active');
    }
}

/**
 * Przełącza odtwarzanie streamu
 */
function toggleStream(cameraId) {
    streamActive = !streamActive;
    const playBtn = document.querySelector('.play-btn');
    
    if (streamActive) {
        playBtn.textContent = '⏸';
        startStreamRefresh();
    } else {
        playBtn.textContent = '▶';
        stopStreamRefresh();
    }
}

/**
 * Rozpoczyna odświeżanie streamu
 */
function startStreamRefresh() {
    // Odświeżaj obraz co 100ms dla efektu "live stream"
    streamInterval = setInterval(() => {
        const mainStream = document.getElementById('mainCameraStream');
        if (mainStream && currentCamera) {
            // Dodaj timestamp aby wymusić odświeżenie
            const currentSrc = mainStream.src.split('?')[0];
            mainStream.src = `${currentSrc}?t=${Date.now()}`;
        }
    }, 100);
}

/**
 * Zatrzymuje odświeżanie streamu
 */
function stopStreamRefresh() {
    if (streamInterval) {
        clearInterval(streamInterval);
        streamInterval = null;
    }
}

/**
 * Zoom in
 */
async function zoomIn(cameraId) {
    try {
        await postJSON('/dashboard/cameras/control', {
            camera_id: cameraId,
            action: 'zoom_in'
        });
        showNotification('Przybliżanie...', 'info');
    } catch (error) {
        showNotification('Nie udało się przybliżyć', 'error');
    }
}

/**
 * Zoom out
 */
async function zoomOut(cameraId) {
    try {
        await postJSON('/dashboard/cameras/control', {
            camera_id: cameraId,
            action: 'zoom_out'
        });
        showNotification('Oddalanie...', 'info');
    } catch (error) {
        showNotification('Nie udało się oddalić', 'error');
    }
}

/**
 * Przełącza nagrywanie
 */
let isRecording = false;
async function toggleRecording(cameraId) {
    isRecording = !isRecording;
    
    try {
        await postJSON('/dashboard/cameras/control', {
            camera_id: cameraId,
            action: isRecording ? 'start_recording' : 'stop_recording'
        });
        
        const btn = event.target.closest('.control-btn');
        if (btn) {
            btn.classList.toggle('recording', isRecording);
        }
        
        showNotification(
            isRecording ? 'Nagrywanie rozpoczęte' : 'Nagrywanie zatrzymane',
            'success'
        );
    } catch (error) {
        isRecording = !isRecording; // Przywróć stan
        showNotification('Nie udało się zmienić stanu nagrywania', 'error');
    }
}

/**
 * Zrób snapshot
 */
async function takeSnapshot(cameraId) {
    try {
        const response = await postJSON('/dashboard/cameras/snapshot', {
            camera_id: cameraId
        });
        
        if (response.success) {
            showNotification('Zdjęcie zapisane', 'success');
            
            // Opcjonalnie: Pobierz zdjęcie
            if (response.image_url) {
                const link = document.createElement('a');
                link.href = response.image_url;
                link.download = `snapshot_${cameraId}_${Date.now()}.jpg`;
                link.click();
            }
        }
    } catch (error) {
        showNotification('Nie udało się zrobić zdjęcia', 'error');
    }
}

/**
 * Przełącz pełny ekran
 */
function toggleFullscreen() {
    const viewer = document.querySelector('.camera-viewer');
    
    if (!document.fullscreenElement) {
        if (viewer.requestFullscreen) {
            viewer.requestFullscreen();
        } else if (viewer.webkitRequestFullscreen) {
            viewer.webkitRequestFullscreen();
        } else if (viewer.msRequestFullscreen) {
            viewer.msRequestFullscreen();
        }
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
    }
}

// Zatrzymaj streaming gdy użytkownik opuszcza stronę
window.addEventListener('beforeunload', function() {
    stopStreamRefresh();
});