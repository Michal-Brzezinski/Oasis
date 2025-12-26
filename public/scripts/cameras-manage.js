/**
 * Skrypt dla strony zarządzania kamerami
 */

/**
 * Usuń kamerę
 */
async function deleteCamera(cameraId) {
    confirmAction('Czy na pewno chcesz usunąć tę kamerę?', async () => {
        try {
            const response = await postJSON('/dashboard/cameras/delete', {
                camera_id: cameraId
            });
            
            if (response.success) {
                // Usuń kartę z DOM
                const card = document.querySelector(`[data-camera-id="${cameraId}"]`);
                if (card) {
                    const parent = card.closest('.camera-manage-card');
                    if (parent) {
                        parent.style.opacity = '0';
                        setTimeout(() => parent.remove(), 300);
                    }
                }
                
                showNotification('Kamera została usunięta', 'success');
            }
        } catch (error) {
            showNotification('Nie udało się usunąć kamery', 'error');
        }
    });
}

/**
 * Test połączenia z kamerą
 */
async function testCameraConnection(cameraId) {
    showNotification('Testowanie połączenia...', 'info');
    
    try {
        const response = await getJSON(`/dashboard/cameras/test?id=${cameraId}`);
        
        if (response.success) {
            showNotification('Połączenie z kamerą działa prawidłowo', 'success');
        } else {
            showNotification('Nie można połączyć się z kamerą', 'error');
        }
    } catch (error) {
        showNotification('Błąd podczas testowania połączenia', 'error');
    }
}