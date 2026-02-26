<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Salud - Datos Integrados</title>
    <link rel="stylesheet" href="css/styles.css">
    <!-- Leaflet CSS para mapas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Encabezado -->
        <header class="header">
            <div class="header-content">
                <h1><i class="fas fa-heartbeat"></i> Portal Integral de Salud</h1>
                <p>Información consolidada de diversas fuentes sanitarias internacionales</p>
            </div>
        </header>

        <!-- Navegación de pestañas -->
        <nav class="tabs">
            <button class="tab-button active" data-tab="who-data">
                <i class="fas fa-globe"></i> OMS (WHO)
            </button>
            <button class="tab-button" data-tab="spain-data">
                <i class="fas fa-map-location-dot"></i> Datos España
            </button>
            <button class="tab-button" data-tab="mapa">
                <i class="fas fa-map"></i> Mapa Interactivo
            </button>
            <button class="tab-button" data-tab="resumen">
                <i class="fas fa-chart-bar"></i> Resumen
            </button>
        </nav>

        <!-- Contenido principal -->
        <main class="main-content">

            <!-- Tab 1: Datos de OMS (WHO) -->
            <section id="who-data" class="tab-content active">
                <div class="section-header">
                    <h2>Indicadores de Salud Global (OMS)</h2>
                    <p>Datos procedentes de la API de datos de la Organización Mundial de la Salud</p>
                </div>

                <div id="who-loading" class="loading" style="display:none;">
                    <div class="spinner"></div>
                    <p>Cargando datos de OMS...</p>
                </div>

                <div id="who-container" class="data-container">
                    <div class="info-box">
                        <p><i class="fas fa-spinner"></i> Cargando datos...</p>
                    </div>
                </div>
            </section>

            <!-- Tab 2: Datos de España -->
            <section id="spain-data" class="tab-content">
                <div class="section-header">
                    <h2>Datos de Salud en España</h2>
                    <p>Información de portales sanitarios españoles (datos.gob.es, etc.)</p>
                </div>

                <div id="spain-loading" class="loading" style="display:none;">
                    <div class="spinner"></div>
                    <p>Cargando datos sanitarios españoles...</p>
                </div>

                <div id="spain-container" class="data-container">
                    <div class="info-box">
                        <p><i class="fas fa-spinner"></i> Cargando datos...</p>
                    </div>
                </div>
            </section>

            <!-- Tab 3: Mapa Interactivo -->
            <section id="mapa" class="tab-content">
                <div class="section-header">
                    <h2>Mapa de Centros Sanitarios</h2>
                    <p>Ubicación geográfica de instalaciones sanitarias (basado en OpenStreetMap)</p>
                </div>

                <div id="map-container">
                    <div id="map" style="height: 500px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"></div>
                    <div id="map-info" style="margin-top: 15px;">
                        <p><i class="fas fa-map-location-dot" style="color: #e74c3c;"></i> Centros sanitarios en Madrid - Haz clic en los marcadores para más información.</p>
                    </div>
                </div>
            </section>

            <!-- Tab 4: Resumen -->
            <section id="resumen" class="tab-content">
                <div class="section-header">
                    <h2>Resumen de Fuentes de Datos</h2>
                    <p>Información sobre las APIs integradas en este portal</p>
                </div>

                <div class="cards-grid">
                    <div class="info-card">
                        <div class="card-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h3>OMS (WHO)</h3>
                        <p><strong>API:</strong> Global Health Observatory</p>
                        <p><strong>Datos:</strong> Indicadores sanitarios mundiales</p>
                        <p><strong>URL:</strong> https://www.who.int/data/gho/info/gho-odata-api</p>
                        <p class="status status-active"><i class="fas fa-check-circle"></i> Integrada</p>
                    </div>

                    <div class="info-card">
                        <div class="card-icon">
                            <i class="fas fa-hospital"></i>
                        </div>
                        <h3>Datos España</h3>
                        <p><strong>API:</strong> Portal de Datos Abiertos</p>
                        <p><strong>Datos:</strong> Información sanitaria española</p>
                        <p><strong>URL:</strong> https://datos.gob.es/es/catalogo/</p>
                        <p class="status status-active"><i class="fas fa-check-circle"></i> Integrada</p>
                    </div>

                    <div class="info-card">
                        <div class="card-icon">
                            <i class="fas fa-map"></i>
                        </div>
                        <h3>OpenStreetMap</h3>
                        <p><strong>API:</strong> Leaflet Map API</p>
                        <p><strong>Datos:</strong> Mapas y ubicaciones geográficas</p>
                        <p><strong>URL:</strong> https://www.openstreetmap.org</p>
                        <p class="status status-active"><i class="fas fa-check-circle"></i> Integrada</p>
                    </div>

                    <div class="info-card">
                        <div class="card-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>Otras Fuentes</h3>
                        <p><strong>APIs disponibles:</strong></p>
                        <ul style="margin-top: 10px;">
                            <li>ECDC (Centro Europeo de Control de Enfermedades)</li>
                            <li>ClinicalTrials.gov (Ensayos clínicos)</li>
                            <li>NCBI (National Center for Biotechnology)</li>
                        </ul>
                        <p class="status status-planning"><i class="fas fa-clock"></i> Planificadas</p>
                    </div>
                </div>

                <div class="info-section" style="margin-top: 30px;">
                    <h3>Características del Portal</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> Recuperación de datos vía REST APIs</li>
                        <li><i class="fas fa-check"></i> Visualización interactiva de información</li>
                        <li><i class="fas fa-check"></i> Integración con mapas (OpenStreetMap)</li>
                        <li><i class="fas fa-check"></i> Interfaz responsiva y moderna</li>
                        <li><i class="fas fa-check"></i> Gestión de errores y caché de datos</li>
                    </ul>
                </div>
            </section>

        </main>

        <!-- Pie de página -->
        <footer class="footer">
            <p>&copy; 2026 Portal Integral de Salud. Datos obtenidos de fuentes públicas oficiales.</p>
            <p>Última actualización: <span id="last-update">--:-- --</span></p>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script>
        // Configuración global
        const API_BASE = '/ProyectoSalud';

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            initTabs();
            initMap();
            attachEventListeners();
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
                    
                    if (tabName === 'mapa') {
                        setTimeout(() => window.map && map.invalidateSize(), 300);
                    }
                });
            });
        }

        // Mapa
        let map = null;
        function initMap() {
            map = L.map('map').setView([40.4168, -3.7038], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap',
                maxZoom: 19
            }).addTo(map);

            const hospitals = [
                {lat: 40.4530, lng: -3.6883, name: 'Hospital Universitario La Paz', address: 'Paseo de la Castellana, 261'},
                {lat: 40.4265, lng: -3.7282, name: 'Hospital Clínico San Carlos', address: 'C/ Profesor Martín Lagos, s/n'},
                {lat: 40.3900, lng: -3.8272, name: 'Hospital Fundación Jiménez Díaz', address: 'Av. Reyes Católicos, 2'},
                {lat: 40.4118, lng: -3.6243, name: 'Hospital 12 de Octubre', address: 'Av. de Córdoba, s/n'},
                {lat: 40.4368, lng: -3.6836, name: 'Centro de Salud Pública', address: 'Madrid Centro'}
            ];

            hospitals.forEach(h => {
                L.circleMarker([h.lat, h.lng], {
                    radius: 8,
                    fillColor: '#e74c3c',
                    color: '#fff',
                    weight: 2,
                    opacity: 0.8,
                    fillOpacity: 0.8
                }).bindPopup(`<b>${h.name}</b><br>${h.address}`).addTo(map);
            });
        }

        // Cargar datos automáticamente
        function attachEventListeners() {
            loadWHOData();
            loadSpainData();
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
                        let html = '<table class="data-table"><thead><tr><th>País</th><th>Indicador</th><th>Valor</th><th>Año</th></tr></thead><tbody>';
                        data.data.indicators.forEach(ind => {
                            html += `<tr><td>${ind.region}</td><td>${ind.name}</td><td><strong>${ind.value}</strong></td><td>${ind.year}</td></tr>`;
                        });
                        html += '</tbody></table>';
                        container.innerHTML = html;
                        updateLastUpdateTime();
                    } else {
                        container.innerHTML = '<div class="alert alert-error">Error: ' + (data.error || 'No hay datos') + '</div>';
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

                    if (data.success && data.data.healthcare_facilities) {
                        let html = '<table class="data-table"><thead><tr><th>Hospital</th><th>Tipo</th><th>Ubicación</th><th>Estado</th></tr></thead><tbody>';
                        data.data.healthcare_facilities.forEach(facility => {
                            html += `<tr><td>${facility.name}</td><td>${facility.type}</td><td>${facility.location}</td><td><span class="status status-active">${facility.status}</span></td></tr>`;
                        });
                        html += '</tbody></table>';
                        container.innerHTML = html;
                        updateLastUpdateTime();
                    } else {
                        container.innerHTML = '<div class="alert alert-error">Error: ' + (data.error || 'No hay datos') + '</div>';
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
            const now = new Date();
            document.getElementById('last-update').textContent = now.toLocaleString('es-ES');
        }
    </script>
</body>
</html>
