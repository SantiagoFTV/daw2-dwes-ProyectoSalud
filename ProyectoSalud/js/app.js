// Variables globales
let map = null;
let markers = [];

// Inicialización al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    initTabs();
    initMap();
    attachEventListeners();
    updateLastUpdateTime();
});

// Inicializar sistema de pestañas
function initTabs() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            
            // Remover active de todos los botones y contenidos
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Agregar active al elemento clickeado
            this.classList.add('active');
            document.getElementById(tabName).classList.add('active');
            
            // Inicializar mapa si es necesario
            if (tabName === 'mapa' && map) {
                setTimeout(() => map.invalidateSize(), 300);
            }
        });
    });
}

// Inicializar mapa con OpenStreetMap
function initMap() {
    map = L.map('map').setView([40.4168, -3.7038], 12); // Madrid

    // Agregar capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);

    // Agregar marcadores de ejemplo (hospitales en Madrid)
    addSanitaryMarkers();
}

// Agregar marcadores de centros sanitarios
function addSanitaryMarkers() {
    const hospitals = [
        {
            lat: 40.4530,
            lng: -3.6883,
            name: 'Hospital Universitario La Paz',
            address: 'Paseo de la Castellana, 261',
            type: 'Hospital General'
        },
        {
            lat: 40.4265,
            lng: -3.7282,
            name: 'Hospital Clínico San Carlos',
            address: 'C/ Profesor Martín Lagos, s/n',
            type: 'Hospital Universitario'
        },
        {
            lat: 40.3900,
            lng: -3.8272,
            name: 'Hospital Fundación Jiménez Díaz',
            address: 'Av. Reyes Católicos, 2',
            type: 'Hospital Privado'
        },
        {
            lat: 40.4118,
            lng: -3.6243,
            name: 'Hospital 12 de Octubre',
            address: 'Av. de Córdoba, s/n',
            type: 'Hospital General'
        },
        {
            lat: 40.4368,
            lng: -3.6836,
            name: 'Centro de Salud Pública',
            address: 'Madrid Centro',
            type: 'Centro de Salud'
        }
    ];

    hospitals.forEach(hospital => {
        const icon = L.icon({
            iconUrl: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAzMiAzMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48Y2lyY2xlIGN4PSIxNiIgY3k9IjE2IiByPSIxNCIgZmlsbD0iI2U3NGMzYyIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLXdpZHRoPSIyIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuMWVtIiBmaWxsPSJ3aGl0ZSIgZm9udC1zaXplPSIyMCIgZm9udC13ZWlnaHQ9ImJvbGQiPisrPC90ZXh0Pjwvc3ZnPg==',
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });

        const marker = L.marker([hospital.lat, hospital.lng], { icon: icon })
            .addTo(map)
            .bindPopup(`
                <div style="font-family: Arial, sans-serif;">
                    <strong style="color: #2c3e50; font-size: 1.1em;">${hospital.name}</strong>
                    <p style="margin: 8px 0 0 0; color: #666;">
                        <i class="fas fa-map-marker-alt"></i> ${hospital.address}<br>
                        <i class="fas fa-hospital"></i> ${hospital.type}
                    </p>
                </div>
            `);

        markers.push(marker);
    });
}

// Adjuntar escuchadores de eventos
function attachEventListeners() {
    document.getElementById('btn-cargar-who')?.addEventListener('click', loadWHOData);
    document.getElementById('btn-actualizar-who')?.addEventListener('click', loadWHOData);
    document.getElementById('btn-cargar-spain')?.addEventListener('click', loadSpainData);
}

// Cargar datos de OMS (WHO)
function loadWHOData() {
    const container = document.getElementById('who-container');
    const loading = document.getElementById('who-loading');
    const button = document.getElementById('btn-cargar-who');

    // Mostrar loading
    loading.style.display = 'flex';
    container.innerHTML = '';
    button.disabled = true;

    // Llamar al backend PHP
    fetch('/ProyectoSalud/php/api_who.php')
        .then(response => {
            if (!response.ok) throw new Error('Error en la respuesta del servidor: ' + response.status);
            return response.text().then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Respuesta no válida:', text);
                    throw new Error('Respuesta inválida del servidor: ' + text.substring(0, 100));
                }
            });
        })
        .then(data => {
            loading.style.display = 'none';
            button.disabled = false;

            if (data.success) {
                displayWHOData(data.data);
                updateLastUpdateTime();
                showBtn('btn-actualizar-who');
            } else {
                showError(container, data.error || 'Error al cargar los datos de OMS');
            }
        })
        .catch(error => {
            loading.style.display = 'none';
            button.disabled = false;
            console.error('Error:', error);
            showError(container, '❌ Error de conexión: ' + error.message);
        });
}

// Mostrar datos de OMS
function displayWHOData(data) {
    const container = document.getElementById('who-container');
    
    let html = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Datos de OMS cargados correctamente</div>';
    
    if (data.indicators && data.indicators.length > 0) {
        html += '<table class="data-table"><thead><tr><th>País</th><th>Indicador</th><th>Valor</th><th>Año</th></tr></thead><tbody>';
        
        data.indicators.forEach(indicator => {
            html += `<tr>
                <td>${indicator.region}</td>
                <td>${indicator.name}</td>
                <td><strong>${indicator.value}</strong></td>
                <td>${indicator.year}</td>
            </tr>`;
        });
        
        html += '</tbody></table>';
    }
    
    container.innerHTML = html;
}

// Cargar datos de España
function loadSpainData() {
    const container = document.getElementById('spain-container');
    const loading = document.getElementById('spain-loading');
    const button = document.getElementById('btn-cargar-spain');

    loading.style.display = 'flex';
    container.innerHTML = '';
    button.disabled = true;

    fetch('/ProyectoSalud/php/api_salud_gob.php')
        .then(response => {
            if (!response.ok) throw new Error('Error en la respuesta del servidor: ' + response.status);
            return response.text().then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Respuesta no válida:', text);
                    throw new Error('Respuesta inválida del servidor: ' + text.substring(0, 100));
                }
            });
        })
        .then(data => {
            loading.style.display = 'none';
            button.disabled = false;

            if (data.success) {
                displaySpainData(data.data);
                updateLastUpdateTime();
            } else {
                showError(container, data.error || 'Error al cargar los datos de España');
            }
        })
        .catch(error => {
            loading.style.display = 'none';
            button.disabled = false;
            console.error('Error:', error);
            showError(container, '❌ Error de conexión: ' + error.message);
        });
}

// Mostrar datos de España
function displaySpainData(data) {
    const container = document.getElementById('spain-container');
    
    let html = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Datos de España cargados correctamente</div>';
    
    if (data.healthcare_facilities) {
        html += '<table class="data-table"><thead><tr><th>Hospital</th><th>Tipo</th><th>Ubicación</th><th>Estado</th></tr></thead><tbody>';
        
        data.healthcare_facilities.forEach(facility => {
            const statusClass = facility.status === 'Activo' ? 'status-active' : 'status-planning';
            html += `<tr>
                <td>${facility.name}</td>
                <td>${facility.type}</td>
                <td>${facility.location}</td>
                <td><span class="status ${statusClass}">${facility.status}</span></td>
            </tr>`;
        });
        
        html += '</tbody></table>';
    }
    
    container.innerHTML = html;
}

// Mostrar errores
function showError(container, message) {
    container.innerHTML = `<div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i> ${message}
    </div>`;
}

// Mostrar/ocultar botón
function showBtn(btnId) {
    const btn = document.getElementById(btnId);
    if (btn) {
        btn.style.display = 'inline-flex';
    }
}

// Actualizar hora de última actualización
function updateLastUpdateTime() {
    const lastUpdateElement = document.getElementById('last-update');
    const now = new Date();
    lastUpdateElement.textContent = now.toLocaleString('es-ES', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

// Función para formatear números
function formatNumber(num) {
    return new Intl.NumberFormat('es-ES').format(num);
}
