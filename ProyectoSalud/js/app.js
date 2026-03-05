// Configuración global
const API_BASE = '/ProyectoSalud';

// Variables globales
let map = null;
let markersLayer = null;

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    initTabs();
    initMap();
    loadWHOData();
    loadSpainData();
    updateLastUpdateTime();
});

// Sistema de pestañas
function initTabs() {
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            document.querySelectorAll('.tab-button').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            document.getElementById(tabName).classList.add('active');
            
            if (tabName === 'mapa' && map) {
                setTimeout(() => map.invalidateSize(), 300);
            }
        });
    });
}

// Inicializar mapa
function initMap() {
    map = L.map('map').setView([40.4168, -3.7038], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    markersLayer = L.layerGroup().addTo(map);
    
    // Cargar hospitales desde el servidor PHP (OpenStreetMap Overpass API)
    loadHospitalsFromServer();
}

// Cargar hospitales desde servidor
function loadHospitalsFromServer() {
    const mapInfo = document.getElementById('map-info');
    mapInfo.innerHTML = '<p><i class="fas fa-spinner fa-spin"></i> Cargando centros sanitarios desde OpenStreetMap...</p>';
    
    fetch(API_BASE + '/php/api_openstreetmap.php?lat=40.4168&lon=-3.7038&radius=10000')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.hospitals) {
                markersLayer.clearLayers();
                
                data.data.hospitals.forEach(h => {
                    const marker = L.circleMarker([h.lat, h.lon], {
                        radius: 8,
                        fillColor: '#e74c3c',
                        color: '#fff',
                        weight: 2,
                        opacity: 0.8,
                        fillOpacity: 0.8
                    });
                    
                    marker.bindPopup(`
                        <b>${h.name}</b><br>
                        <i>${h.type}</i><br>
                        ${h.address}
                    `);
                    
                    marker.addTo(markersLayer);
                });
                
                mapInfo.innerHTML = `
                    <p><i class="fas fa-check-circle" style="color: #27ae60;"></i> 
                    Se encontraron <strong>${data.count}</strong> centros sanitarios en Madrid (radio de 10km).</p>
                    <p style="margin-top: 10px; font-size: 0.9em; color: #666;">
                    <i class="fas fa-info-circle"></i> Datos obtenidos desde OpenStreetMap usando Overpass API (procesado en servidor PHP con patrón MVC).</p>
                `;
            } else {
                mapInfo.innerHTML = '<p style="color: #e74c3c;"><i class="fas fa-exclamation-triangle"></i> Error: ' + (data.error || 'No se pudieron cargar los hospitales') + '</p>';
            }
        })
        .catch(error => {
            mapInfo.innerHTML = '<p style="color: #e74c3c;"><i class="fas fa-exclamation-triangle"></i> Error de conexión: ' + error.message + '</p>';
            console.error('Error:', error);
        });
}

// Cargar datos WHO
function loadWHOData() {
    const container = document.getElementById('who-container');
    const loading = document.getElementById('who-loading');

    loading.style.display = 'flex';
    container.innerHTML = '';

    fetch(API_BASE + '/php/api_who.php')
        .then(response => response.json())
        .then(data => {
            loading.style.display = 'none';

            if (data.success && data.data.indicators) {
                let html = '<div class="info-box" style="margin-bottom: 20px;">';
                html += '<p><i class="fas fa-info-circle"></i> <strong>Indicadores de salud global</strong> obtenidos de la API oficial de la OMS (procesado por Modelo MVC).</p>';
                html += '<p>Total de indicadores: <strong>' + data.count + '</strong></p>';
                html += '</div>';
                
                html += '<table class="data-table"><thead><tr><th>País</th><th>Indicador</th><th>Valor</th><th>Año</th></tr></thead><tbody>';
                data.data.indicators.forEach(ind => {
                    html += `<tr><td>${ind.region}</td><td>${ind.name}</td><td><strong>${ind.value}</strong></td><td>${ind.year}</td></tr>`;
                });
                html += '</tbody></table>';
                container.innerHTML = html;
                updateLastUpdateTime();
            } else {
                container.innerHTML = '<div class="alert alert-error">Error: ' + (data.error || 'No hay datos disponibles') + '</div>';
            }
        })
        .catch(error => {
            loading.style.display = 'none';
            container.innerHTML = '<div class="alert alert-error">Error de conexión: ' + error.message + '</div>';
            console.error('Error:', error);
        });
}

// Cargar datos España
function loadSpainData() {
    const container = document.getElementById('spain-container');
    const loading = document.getElementById('spain-loading');

    loading.style.display = 'flex';
    container.innerHTML = '';

    fetch(API_BASE + '/php/api_salud_gob.php')
        .then(response => response.json())
        .then(data => {
            loading.style.display = 'none';

            if (data.success && data.data.health_datasets) {
                let html = '<div class="info-box" style="margin-bottom: 20px;">';
                html += '<p><i class="fas fa-info-circle"></i> <strong>Conjuntos de datos sobre salud</strong> disponibles en el portal de datos abiertos de España (procesado por Modelo MVC).</p>';
                html += '<p>Total de datasets encontrados: <strong>' + data.count + '</strong></p>';
                html += '</div>';
                
                html += '<table class="data-table"><thead><tr><th>Título</th><th>Descripción</th><th>Publicador</th><th>Última Modificación</th></tr></thead><tbody>';
                data.data.health_datasets.forEach(dataset => {
                    html += `<tr>
                        <td><strong>${dataset.title}</strong></td>
                        <td>${dataset.description}</td>
                        <td>${dataset.publisher}</td>
                        <td>${dataset.modified}</td>
                    </tr>`;
                });
                html += '</tbody></table>';
                container.innerHTML = html;
                updateLastUpdateTime();
            } else {
                container.innerHTML = '<div class="alert alert-error">Error: ' + (data.error || 'No hay datos disponibles') + '</div>';
            }
        })
        .catch(error => {
            loading.style.display = 'none';
            container.innerHTML = '<div class="alert alert-error">Error de conexión: ' + error.message + '</div>';
            console.error('Error:', error);
        });
}

// Actualizar hora
function updateLastUpdateTime() {
    const lastUpdateElement = document.getElementById('last-update');
    if (lastUpdateElement) {
        const now = new Date();
        lastUpdateElement.textContent = now.toLocaleString('es-ES');
    }
}
