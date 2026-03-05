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
                <h1><i class="fas fa-heartbeat"></i> Portal Integral de Salud (MVC)</h1>
                <p>Información consolidada de diversas fuentes sanitarias internacionales - Arquitectura Modelo-Vista-Controlador</p>
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
                    <p>Conjuntos de datos sanitarios del Portal de Datos Abiertos de España (API CKAN)</p>
                </div>

                <div id="spain-loading" class="loading" style="display:none;">
                    <div class="spinner"></div>
                    <p>Cargando conjuntos de datos sanitarios desde datos.gob.es...</p>
                </div>

                <div id="spain-container" class="data-container">
                    <div class="info-box">
                        <p><i class="fas fa-spinner"></i> Cargando conjuntos de datos...</p>
                    </div>
                </div>
            </section>

            <!-- Tab 3: Mapa Interactivo -->
            <section id="mapa" class="tab-content">
                <div class="section-header">
                    <h2>Mapa de Centros Sanitarios</h2>
                    <p>Ubicación geográfica de instalaciones sanitarias obtenidas en tiempo real desde OpenStreetMap (Overpass API - Servidor PHP)</p>
                </div>

                <div id="map-container">
                    <div id="map" style="height: 500px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"></div>
                    <div id="map-info" style="margin-top: 15px;">
                        <p><i class="fas fa-spinner fa-spin"></i> Cargando centros sanitarios...</p>
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
                        <p><strong>Procesamiento:</strong> Servidor PHP (Modelo MVC)</p>
                        <p class="status status-active"><i class="fas fa-check-circle"></i> Integrada</p>
                    </div>

                    <div class="info-card">
                        <div class="card-icon">
                            <i class="fas fa-hospital"></i>
                        </div>
                        <h3>Datos España</h3>
                        <p><strong>API:</strong> Portal de Datos Abiertos (CKAN)</p>
                        <p><strong>Datos:</strong> Información sanitaria española</p>
                        <p><strong>URL:</strong> https://datos.gob.es/es/catalogo/</p>
                        <p><strong>Procesamiento:</strong> Servidor PHP (Modelo MVC)</p>
                        <p class="status status-active"><i class="fas fa-check-circle"></i> Integrada</p>
                    </div>

                    <div class="info-card">
                        <div class="card-icon">
                            <i class="fas fa-map"></i>
                        </div>
                        <h3>OpenStreetMap</h3>
                        <p><strong>API:</strong> Overpass API</p>
                        <p><strong>Datos:</strong> Hospitales y centros sanitarios con geolocalización</p>
                        <p><strong>URL:</strong> https://overpass-api.de/api/interpreter</p>
                        <p><strong>Procesamiento:</strong> Servidor PHP (Modelo MVC)</p>
                        <p class="status status-active"><i class="fas fa-check-circle"></i> Integrada</p>
                    </div>

                    <div class="info-card">
                        <div class="card-icon">
                            <i class="fas fa-code"></i>
                        </div>
                        <h3>Arquitectura MVC</h3>
                        <p><strong>Patrón:</strong> Modelo-Vista-Controlador</p>
                        <p><strong>Modelo:</strong> ModeloSalud.php (lógica de negocio)</p>
                        <p><strong>Vista:</strong> VistaInicio.php (presentación)</p>
                        <p><strong>Controlador:</strong> ControladorPrincipal.php (coordinación)</p>
                        <p class="status status-active"><i class="fas fa-check-circle"></i> Implementado</p>
                    </div>
                </div>

                <div class="info-section" style="margin-top: 30px;">
                    <h3>Características del Portal</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> Recuperación de datos vía REST APIs desde servidor PHP</li>
                        <li><i class="fas fa-check"></i> Visualización interactiva de información sanitaria</li>
                        <li><i class="fas fa-check"></i> Integración con OpenStreetMap (Overpass API) desde servidor</li>
                        <li><i class="fas fa-check"></i> Arquitectura MVC (Modelo-Vista-Controlador)</li>
                        <li><i class="fas fa-check"></i> Sistema de caché para optimizar peticiones a APIs externas</li>
                        <li><i class="fas fa-check"></i> No hay datos hardcodeados - todo se obtiene de APIs reales</li>
                    </ul>
                </div>
            </section>

        </main>

        <!-- Pie de página -->
        <footer class="footer">
            <p>&copy; 2026 Portal Integral de Salud (Patrón MVC). Datos obtenidos de fuentes públicas oficiales.</p>
            <p>Última actualización: <span id="last-update">--:-- --</span></p>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script src="js/app.js"></script>
</body>
</html>

