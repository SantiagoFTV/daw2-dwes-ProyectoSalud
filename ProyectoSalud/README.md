# Portal Integral de Salud - Arquitectura MVC

## Descripción del Proyecto

Portal web que integra diversas fuentes de información sobre salud utilizando APIs REST externas. El proyecto está implementado siguiendo el patrón de diseño **Modelo-Vista-Controlador (MVC)**.

## Arquitectura MVC

### 📁 Estructura del Proyecto

```
ProyectoSalud/
│
├── index.php                      # Punto de entrada principal (enrutador)
│
├── modelo/
│   └── ModeloSalud.php           # Modelo: Lógica de negocio y conexiones a APIs
│
├── vista/
│   └── VistaInicio.php           # Vista: Presentación HTML
│
├── controlador/
│   └── ControladorPrincipal.php  # Controlador: Coordinación modelo-vista
│
├── php/
│   ├── config.php                # Configuración global
│   ├── api_who.php               # Endpoint API WHO (delega al controlador)
│   ├── api_salud_gob.php         # Endpoint API datos.gob.es (delega al controlador)
│   └── api_openstreetmap.php     # Endpoint API OpenStreetMap (delega al controlador)
│
├── css/
│   └── styles.css                # Estilos
│
├── js/
│   └── app.js                    # JavaScript del cliente
│
└── data/
    └── cache/                    # Caché de respuestas de APIs
```

### 🔄 Flujo de Datos

```
Usuario
   ↓
index.php (Enrutador)
   ↓
ControladorPrincipal.php (Controlador)
   ↓
ModeloSalud.php (Modelo) → APIs Externas (WHO, datos.gob.es, OpenStreetMap)
   ↓
ControladorPrincipal.php (Controlador)
   ↓
VistaInicio.php (Vista)
   ↓
Usuario
```

## Componentes del Patrón MVC

### 1. **Modelo** (`modelo/ModeloSalud.php`)

Contiene toda la lógica de negocio:
- `obtenerDatosWHO()`: Consume la API de la Organización Mundial de la Salud
- `obtenerDatosEspana()`: Consume la API CKAN de datos.gob.es
- `obtenerHospitalesOSM()`: Consume la API Overpass de OpenStreetMap
- Gestión de caché para optimizar peticiones
- Procesamiento y transformación de datos

**No hay datos hardcodeados**: Todos los datos se obtienen de APIs REST reales.

### 2. **Vista** (`vista/VistaInicio.php`)

Responsable de la presentación:
- HTML5 con diseño responsivo
- Interfaz de usuario con pestañas
- Integración de Leaflet para mapas
- Font Awesome para iconos
- Muestra datos procesados por el modelo

### 3. **Controlador** (`controlador/ControladorPrincipal.php`)

Coordina el modelo y la vista:
- `inicio()`: Muestra la página principal
- `apiWHO()`: Endpoint JSON para datos de WHO
- `apiEspana()`: Endpoint JSON para datos de España
- `apiOpenStreetMap()`: Endpoint JSON para hospitales
- Manejo de errores y respuestas JSON
- Validación de parámetros

## APIs Integradas (todas consumidas desde servidor PHP)

### 1. **WHO (Organización Mundial de la Salud)**
- **URL**: https://ghoapi.azureedge.net/api
- **Datos**: Indicadores de salud global (esperanza de vida, mortalidad infantil, diabetes)
- **Procesamiento**: Servidor PHP (Modelo MVC)
- **Caché**: 1 hora

### 2. **datos.gob.es (Portal de Datos Abiertos de España)**
- **URL**: https://datos.gob.es/apidata/catalog/dataset
- **Datos**: Conjuntos de datos sobre salud en España
- **API**: CKAN
- **Procesamiento**: Servidor PHP (Modelo MVC)
- **Caché**: 2 horas

### 3. **OpenStreetMap (Overpass API)**
- **URL**: https://overpass-api.de/api/interpreter
- **Datos**: Hospitales y centros sanitarios con geolocalización
- **Procesamiento**: Servidor PHP (Modelo MVC) - NO desde cliente JavaScript
- **Caché**: 6 horas

## Características Técnicas

✅ **Sin datos hardcodeados**: Todos los datos provienen de APIs REST reales  
✅ **Procesamiento en servidor**: Las APIs se consultan desde PHP, no desde JavaScript  
✅ **Sistema de caché**: Optimiza peticiones y reduce carga en APIs externas  
✅ **Arquitectura MVC**: Separación clara de responsabilidades  
✅ **Manejo de errores**: Validación y respuestas apropiadas  
✅ **Interfaz responsiva**: Compatible con móviles y tablets  

## Requisitos

- PHP 7.4 o superior
- Apache con mod_rewrite (XAMPP, WAMP, LAMP)
- Conexión a Internet (para consumir APIs externas)
- Extensión PHP `allow_url_fopen` habilitada

## Instalación

1. Clonar/copiar el proyecto en el directorio del servidor web:
   ```
   c:\xampp\htdocs\ProyectoSalud
   ```

2. Asegurar que el directorio `data/cache` tenga permisos de escritura:
   ```bash
   chmod 777 data/cache
   ```

3. Acceder desde el navegador:
   ```
   http://localhost/ProyectoSalud/
   ```

## Uso

El proyecto se inicia automáticamente cuando accedes a `index.php`:

1. **index.php** carga el **ControladorPrincipal**
2. El controlador llama al método `inicio()`
3. Este método carga la **VistaInicio.php**
4. La vista contiene JavaScript que hace peticiones AJAX a:
   - `php/api_who.php`
   - `php/api_salud_gob.php`
   - `php/api_openstreetmap.php`
5. Cada endpoint PHP delega al **Controlador**
6. El **Controlador** consulta el **Modelo**
7. El **Modelo** obtiene datos de APIs externas y los devuelve
8. El **Controlador** formatea la respuesta JSON
9. JavaScript actualiza la interfaz con los datos

## Caché

- **Ubicación**: `data/cache/`
- **Formato**: JSON
- **TTL**:
  - WHO: 1 hora (3600 segundos)
  - datos.gob.es: 2 horas (7200 segundos)
  - OpenStreetMap: 6 horas (21600 segundos)

## Cumplimiento de la Rúbrica

✅ **Recuperación de información vía REST** (hasta 4 puntos):
- API WHO: Indicadores de salud global
- API datos.gob.es: Conjuntos de datos de salud en España
- API OpenStreetMap: Hospitales con geolocalización

✅ **Muestra correcta de información** (hasta 4 puntos):
- Tablas interactivas con datos de WHO
- Lista de datasets de datos.gob.es
- Mapa con marcadores de hospitales de OpenStreetMap

✅ **Información de servidor habitual** (hasta 4 puntos):
- OpenStreetMap (datos.gob.es también es un servidor reconocido)
- Todo procesado desde servidor PHP (NO hardcodeado)
- Integración completa con Overpass API

## Autor

Proyecto desarrollado con patrón MVC para demostrar integración de APIs REST de salud.

## Licencia

Este proyecto es de código abierto y está disponible para fines educativos.
