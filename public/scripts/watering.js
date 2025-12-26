/**
 * Skrypt dla strony sterowania podlewaniem
 */

let currentWateringZone = null;
let wateringTimer = null;

document.addEventListener('DOMContentLoaded', function() {
    initializeWateringStatus();
});

/**
 * Inicjalizuje status podlewania
 */
function initializeWateringStatus() {
    if (typeof manualStatus !== 'undefined' && manualStatus.is_active) {
        currentWateringZone = manualStatus.zone_id;
        updateWateringUI(true);
    }
}

/**
 * Rozpoczyna ręczne podlewanie
 */
async function startManualWatering() {
    const zoneSelect = document.getElementById('zoneSelect');
    const zoneId = parseInt(zoneSelect.value);
    const zoneName = zoneSelect.options[zoneSelect.selectedIndex].text;
    
    if (!zoneId) {
        showNotification('Wybierz strefę do podlewania', 'warning');
        return;
    }
    
    try {
        const response = await postJSON('/dashboard/schedules/startManual', {
            zone_id: zoneId
        });
        
        if (response.success) {
            currentWateringZone = zoneId;
            updateWateringUI(true, zoneName);
            showNotification('Podlewanie rozpoczęte', 'success');
        }
    } catch (error) {
        showNotification('Nie udało się rozpocząć podlewania', 'error');
    }
}

/**
 * Zatrzymuje ręczne podlewanie
 */
async function stopManualWatering() {
    if (!currentWateringZone) return;
    
    try {
        const response = await postJSON('/dashboard/schedules/stopManual', {
            zone_id: currentWateringZone
        });
        
        if (response.success) {
            currentWateringZone = null;
            updateWateringUI(false);
            showNotification('Podlewanie zatrzymane', 'success');
        }
    } catch (error) {
        showNotification('Nie udało się zatrzymać podlewania', 'error');
    }
}

/**
 * Aktualizuje interfejs podlewania
 */
function updateWateringUI(isActive, zoneName = 'Brak') {
    const startBtn = document.getElementById('startWateringBtn');
    const stopBtn = document.getElementById('stopWateringBtn');
    const statusText = document.getElementById('statusText');
    
    if (isActive) {
        startBtn.disabled = true;
        stopBtn.disabled = false;
        statusText.innerHTML = `Obecnie podlewana: <span class="zone-name active">${zoneName}</span>`;
    } else {
        startBtn.disabled = false;
        stopBtn.disabled = true;
        statusText.innerHTML = `Obecnie podlewana: <span class="zone-name">${zoneName}</span>`;
    }
}

/**
 * Przełącza aktywność harmonogramu
 */
async function toggleSchedule(scheduleId) {
    try {
        const response = await postJSON('/dashboard/schedules/toggle', {
            schedule_id: scheduleId
        });
        
        if (response.success) {
            showNotification(
                response.active ? 'Harmonogram aktywowany' : 'Harmonogram dezaktywowany',
                'success'
            );
        }
    } catch (error) {
        // Przywróć poprzedni stan checkboxa
        const checkbox = document.querySelector(`input[data-schedule-id="${scheduleId}"]`);
        if (checkbox) {
            checkbox.checked = !checkbox.checked;
        }
        showNotification('Nie udało się zmienić statusu harmonogramu', 'error');
    }
}

/**
 * Edytuj harmonogram
 */
function editSchedule(scheduleId) {
    window.location.href = `/dashboard/schedules/edit?id=${scheduleId}`;
}

/**
 * Usuń harmonogram
 */
async function deleteSchedule(scheduleId) {
    confirmAction('Czy na pewno chcesz usunąć ten harmonogram?', async () => {
        try {
            const response = await postJSON('/dashboard/schedules/delete', {
                schedule_id: scheduleId
            });
            
            if (response.success) {
                // Usuń kartę z DOM
                const card = document.querySelector(`[data-schedule-id="${scheduleId}"]`);
                if (card) {
                    card.style.opacity = '0';
                    setTimeout(() => card.remove(), 300);
                }
                
                showNotification('Harmonogram został usunięty', 'success');
            }
        } catch (error) {
            showNotification('Nie udało się usunąć harmonogramu', 'error');
        }
    });
}