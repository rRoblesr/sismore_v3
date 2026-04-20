const SismoreMapAgent = {
    // Topologías que espera leer globalmente (deben estar cargadas antes de llamar al agente)
    mapDataPeru: null,
    mapDataProvincia: null,
    mapDataDistritos: null,

    // Diccionario demográfico (inyectado dinámicamente según el reporte)
    demografia: null,

    // Configuración para el valor del Tooltip  (Ej: "Plazas" o "habitantes")
    unidadMedida: 'habitantes',

    // Variables de Estado
    chart: null,
    currentLevel: 'pais',
    historyStack: [],
    btnBack: null,
    btnBackText: null,

    // Inicializador
    init: function () {
        this.mapDataPeru = typeof p_departamento !== 'undefined' ? p_departamento : (typeof departamento !== 'undefined' ? departamento : null);
        this.mapDataProvincia = typeof p_ucayali !== 'undefined' ? p_ucayali : (typeof otros !== 'undefined' ? otros : null);
        this.mapDataDistritos = typeof p_distritos !== 'undefined' ? p_distritos : (typeof p_distrito !== 'undefined' ? p_distrito : (typeof otros2 !== 'undefined' ? otros2 : null));
    },

    // Punto de entrada: Renderizar mapa en un contenedor específico con datos dinámicos
    renderizarMapa: function (containerId, data, config = { unit: 'habitantes' }) {
        this.init(); // Asegura de atrapar las globales de topología
        this.demografia = data;
        this.unidadMedida = config.unit || 'habitantes';
        this.currentLevel = 'pais';
        this.historyStack = [];

        // Generar o recuperar el Botón "Atrás"
        let container = document.getElementById(containerId);
        if (!container) return;

        // Aseguramos que exista un wrapper relativo que NO sea borrado por Highcharts
        let wrapperId = `wrapper-${containerId}`;
        let wrapper = document.getElementById(wrapperId);
        if (!wrapper) {
            // Envolvemos el contenedor en un div relativo
            wrapper = document.createElement('div');
            wrapper.id = wrapperId;
            wrapper.style.cssText = 'position: relative; display: block;';
            container.parentNode.insertBefore(wrapper, container);
            wrapper.appendChild(container);
        }

        // Botón fuera del contenedor de Highcharts → no se borra con chart.destroy()
        if (!document.getElementById(`btn-back-${containerId}`)) {
            let btn = document.createElement('button');
            btn.id = `btn-back-${containerId}`;
            btn.style.cssText = `
                position: absolute; bottom: 120px; right: 20px; z-index: 999;
                width: 30px; height: 30px; padding: 0;
                display: none; align-items: center; justify-content: center;
                cursor: pointer;
                background-color: rgba(255,255,255,0.97);
                color: #1a6b5e;
                border: 1px solid #cccccc;
                border-radius: 6px;
                box-shadow: 0 1px 4px rgba(0,0,0,0.12);
                transition: background 0.15s;
            `;
            btn.title = 'Regresar al nivel anterior';
            btn.innerHTML = `
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round"
                     style="width:13px;height:13px;pointer-events:none;">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>`;
            btn.onmouseover = function () { btn.style.backgroundColor = '#e8f4f2'; };
            btn.onmouseout = function () { btn.style.backgroundColor = 'rgba(255,255,255,0.97)'; };

            // Va al wrapper, NO al contenedor de Highcharts
            wrapper.appendChild(btn);

            this.btnBack = btn;

            // Lógica Global del botón Atrás
            this.btnBack.addEventListener('click', () => {
                if (this.historyStack.length > 0) {
                    var prev = this.historyStack.pop();
                    this.currentLevel = prev.level;

                    if (this.historyStack.length === 0) {
                        this.actualizarBotonAtras('', false);
                    } else {
                        var capaPadreAnterior = this.historyStack[this.historyStack.length - 1].level;
                        if (capaPadreAnterior === 'pais') this.actualizarBotonAtras('Volver a Perú', true);
                        if (capaPadreAnterior === 'provincias') this.actualizarBotonAtras('Volver a Ucayali (Provincias)', true);
                    }

                    var subTitleMap = prev.subtitle || null;

                    this.render(containerId, prev.geo, prev.title, subTitleMap, this.currentLevel);
                }
            });
        } else {
            this.btnBack = document.getElementById(`btn-back-${containerId}`);
        }

        this.actualizarBotonAtras('', false);

        // Determinar qué mapa pintar inicialmente basado en config
        let startLevel = config.initialLevel || 'pais';
        this.currentLevel = startLevel;

        // 'title' in config = fue pasado explícitamente (null incluido = sin título)
        var tieneTitulo = 'title' in config;
        var tieneSubtitulo = 'subtitle' in config;

        if (startLevel === 'pais') {
            if (!this.mapDataPeru) {
                console.error("Topología base Nacional no ha sido cargada");
                return;
            }
            this.render(containerId, this.mapDataPeru,
                tieneTitulo ? config.title : 'Análisis Demográfico del Perú',
                tieneSubtitulo ? config.subtitle : 'Haz doble clic en UCAYALI para hacer Zoom',
                'pais');
        } else if (startLevel === 'provincias') {
            if (!this.mapDataProvincia) {
                console.error("Topología base Provincial no ha sido cargada");
                return;
            }
            this.render(containerId, this.mapDataProvincia,
                tieneTitulo ? config.title : 'Ucayali (Nivel Provincial)',
                tieneSubtitulo ? config.subtitle : 'Despliegue General. Haz doble clic en una provincia',
                'provincias');
        }
    },

    actualizarBotonAtras: function (texto, mostrar) {
        if (!this.btnBack) return;
        this.btnBack.style.display = mostrar ? 'flex' : 'none';
        this.btnBack.title = texto || 'Regresar al nivel anterior';
    },

    generarDatos: function (mapGeoJSON, nivel) {
        var data = [];
        if (!mapGeoJSON || !mapGeoJSON.features) return data;
        let self = this;

        mapGeoJSON.features.forEach(function (f) {
            var nombreOriginal = '';
            var joinKey = '';

            if (nivel === 'pais') {
                nombreOriginal = f.properties && f.properties.NOMBDEP ? f.properties.NOMBDEP : (f.name || '');
                joinKey = nombreOriginal;
            } else {
                nombreOriginal = f.properties && f.properties.name ? f.properties.name : (f.name || '');
                joinKey = f.properties && f.properties['hc-key'] ? f.properties['hc-key'] : f.id;
            }

            var nombreLimpio = nombreOriginal.toUpperCase().trim();
            var val = null;
            var valObj = null;

            if (self.demografia) {
                let dict = null;
                if (nivel === 'pais') dict = self.demografia.departamentos;
                else if (nivel === 'provincias') dict = self.demografia.provincias_ucayali;
                else dict = self.demografia.distritos_ucayali;

                if (dict) {
                    valObj = dict[nombreLimpio] !== undefined ? dict[nombreLimpio] : (dict[joinKey] !== undefined ? dict[joinKey] : null);
                }
            }

            var valNum = valObj !== null && typeof valObj === 'object' ? valObj.porcentaje : valObj;

            if (nivel === 'pais') {
                data.push({ NOMBDEP: nombreOriginal, value: valNum, fullData: valObj });
            } else {
                data.push({ 'hc-key': joinKey, name: nombreOriginal, value: valNum, fullData: valObj });
            }
        });
        return data;
    },

    construirMapaDistritosProvincia: function (nombreProvincia) {
        if (!this.mapDataDistritos || !this.mapDataDistritos.features) return null;
        var nombre = nombreProvincia.toUpperCase().trim();
        var provinceUbigeo = null;
        var provinceHcKey = null;

        if (this.mapDataProvincia && this.mapDataProvincia.features) {
            var provFeature = this.mapDataProvincia.features.find(function (element) {
                var pName = element.properties.name ? String(element.properties.name).toUpperCase().trim() : '';
                return pName === nombre;
            });
            if (provFeature) {
                provinceUbigeo = provFeature.ubigeo ? String(provFeature.ubigeo) : null;
                provinceHcKey = (provFeature.properties && provFeature.properties['hc-key']) ? String(provFeature.properties['hc-key']) : null;
            }
        }

        if (nombre === 'PADRE ABAD') {
            provinceUbigeo = '2503';
            provinceHcKey = 'pe-uc-pa';
        }

        var featuresFiltradas = this.mapDataDistritos.features.filter(function (element) {
            var padre = element.padre ? String(element.padre).toUpperCase().trim() : '';
            var matchName = (padre === nombre);
            var matchUbigeo = provinceUbigeo && element.ubigeo ? String(element.ubigeo).startsWith(provinceUbigeo) : false;
            var matchHcKey = provinceHcKey && element.properties && element.properties['hc-key'] ? String(element.properties['hc-key']).startsWith(provinceHcKey + '-') : false;

            return matchName || matchUbigeo || matchHcKey;
        });

        return {
            title: this.mapDataDistritos.title,
            version: this.mapDataDistritos.version,
            type: this.mapDataDistritos.type,
            crs: this.mapDataDistritos.crs,
            features: featuresFiltradas
        };
    },

    construirMapaDistritoAislado: function (hcKeyTarget) {
        if (!this.mapDataDistritos || !this.mapDataDistritos.features) return null;
        var featuresFiltradas = this.mapDataDistritos.features.filter(function (element) {
            var key = element.properties && element.properties['hc-key'] ? element.properties['hc-key'] : '';
            return key === hcKeyTarget;
        });

        return {
            title: this.mapDataDistritos.title,
            version: this.mapDataDistritos.version,
            type: this.mapDataDistritos.type,
            crs: this.mapDataDistritos.crs,
            features: featuresFiltradas
        };
    },

    render: function (containerId, mapGeoJSON, title, subtitle, nivel) {
        if (this.chart) {
            this.chart.destroy();
        }

        var mapDataArr = this.generarDatos(mapGeoJSON, nivel);
        var joinConfig = (nivel === 'pais') ? 'NOMBDEP' : 'hc-key';

        // Paleta teal unificada (igual a anal1) para todos los niveles
        var minCol = '#e0f6f3', maxCol = '#1a6b5e';

        let self = this;

        this.chart = Highcharts.mapChart(containerId, {
            chart: {
                map: mapGeoJSON,
                backgroundColor: 'transparent'
            },
            title: { text: title || null },
            subtitle: { text: subtitle || null },
            mapNavigation: {
                enabled: true,
                enableDoubleClickZoomTo: false,
                buttonOptions: {
                    verticalAlign: 'bottom',
                    align: 'right',
                    x: -10,
                    theme: {
                        'stroke-width': 1,
                        stroke: '#cccccc',
                        r: 8,
                        fill: 'rgba(255,255,255,0.97)',
                        style: {
                            color: '#1a6b5e',
                            fontWeight: 'bold',
                            fontSize: '14px'
                        },
                        states: {
                            hover: { fill: '#e8f4f2' }
                        }
                    }
                },
                buttons: {
                    zoomIn: { y: -40 },
                    zoomOut: { y: -10 }
                }
            },
            colorAxis: {
                minColor: minCol,
                maxColor: maxCol,
                showInLegend: false
            },
            tooltip: {
                useHTML: true,
                formatter: function () {
                    var nombre = (nivel === 'pais') ? (this.point.properties && this.point.properties.NOMBDEP ? this.point.properties.NOMBDEP : this.point.name) : this.point.name;
                    var valorStr = 'Datos no disponibles';

                    if (this.point.fullData !== undefined && this.point.fullData !== null && typeof this.point.fullData === 'object') {
                        let qty = this.point.fullData.cantidad;
                        let pct = this.point.fullData.porcentaje;
                        if (qty !== undefined && pct !== undefined) {
                            valorStr = `Docentes: <b>${Highcharts.numberFormat(qty, 0)}</b><br>Participación: <b>${Highcharts.numberFormat(pct, 1)}${self.unidadMedida}</b>`;
                        } else {
                            valorStr = `Valor: <b>${Highcharts.numberFormat(this.point.value, 1)}${self.unidadMedida}</b>`;
                        }
                    } else if (this.point.value !== null && this.point.value !== undefined) {
                        valorStr = `Valor: <b>${Highcharts.numberFormat(this.point.value, 1)}${self.unidadMedida}</b>`;
                    }

                    var nombreSeguro = nombre ? nombre : 'Desconocido';
                    return `<div style="text-align:center;">
                                <strong>${nombreSeguro}</strong><br>
                                <span style="font-size:12px;">${valorStr}</span>
                            </div>`;
                },
                backgroundColor: '#fff',
                borderColor: '#cccccc',
                borderRadius: 10,
                shadow: true,
                style: { fontSize: '12px', color: '#333' }
            },
            series: [{
                data: mapDataArr,
                joinBy: [joinConfig, joinConfig],
                showInLegend: false,
                states: { hover: { color: '#f5bd22' } },
                borderColor: '#bbbbbb',
                borderWidth: (nivel === 'distrito_aislado') ? 2 : 1,
                dataLabels: {
                    enabled: true,
                    useHTML: true,
                    formatter: function () {
                        var nombre = (nivel === 'pais') ? (this.point.properties && this.point.properties.NOMBDEP ? this.point.properties.NOMBDEP : this.point.name) : this.point.name;
                        var valor = this.point.value;
                        if (valor === null || valor === undefined) return '';
                        return `<div style="text-align:center; color:white; text-shadow: 1px 1px 2px black;">${nombre}<br><span style="font-size:12px;">${Highcharts.numberFormat(valor, 1)}${self.unidadMedida}</span></div>`;
                    },
                    style: {
                        fontSize: (nivel === 'distrito_aislado') ? '16px' : '10px',
                        fontWeight: 'bold',
                        color: '#FFFFFF',
                        textShadow: '0px 0px 3px #000000'
                    }
                },
                point: {
                    events: {
                        click: function (e) {
                            var point = this;
                            var t = new Date().getTime();
                            if (point.lastClick && (t - point.lastClick) < 400) {

                                if (self.currentLevel === 'pais') {
                                    var nombreDep = point.properties.NOMBDEP || point.name;
                                    if (nombreDep.toUpperCase().trim() === 'UCAYALI') {
                                        self.historyStack.push({ level: 'pais', title: self.chart.options.title.text, geo: self.mapDataPeru });
                                        self.currentLevel = 'provincias';
                                        self.actualizarBotonAtras('Volver a Perú', true);
                                        self.render(containerId, self.mapDataProvincia, 'Ucayali (Nivel Provincial)', 'Haz doble clic para inspeccionar distritos', 'provincias');
                                    } else {
                                        if (typeof Swal !== 'undefined') {
                                            Swal.fire({
                                                icon: 'info',
                                                title: 'Fuera de alcance territorial',
                                                text: `Sistema sin cartografía municipal paramétrica para ${nombreDep}.`
                                            });
                                        }
                                    }
                                }
                                else if (self.currentLevel === 'provincias') {
                                    var provName = point.properties && point.properties.name ? point.properties.name.toUpperCase().trim() : (point.name ? point.name.toUpperCase().trim() : '');
                                    var dataDistritos = self.construirMapaDistritosProvincia(provName);

                                    if (dataDistritos && dataDistritos.features.length > 0) {
                                        self.historyStack.push({ level: 'provincias', title: self.chart.options.title.text, subtitle: self.chart.options.subtitle.text, geo: self.mapDataProvincia });
                                        self.currentLevel = 'distritos';
                                        self.actualizarBotonAtras('Volver a Ucayali (Provincias)', true);//Porcentaje de Plazas Docentes por Provincia
                                        self.render(containerId, dataDistritos, null, 'Porcentaje de Plazas Docentes en la Provincia de ' + point.name, 'distritos');
                                    }
                                }
                                else if (self.currentLevel === 'distritos') {
                                    var distHcKey = point.properties['hc-key'];
                                    var mappedData = self.construirMapaDistritoAislado(distHcKey);

                                    if (mappedData && mappedData.features.length > 0) {
                                        self.historyStack.push({ level: 'distritos', title: self.chart.options.title.text, subtitle: self.chart.options.subtitle.text, geo: self.chart.options.chart.map });
                                        self.currentLevel = 'distrito_aislado';
                                        self.actualizarBotonAtras('Volver Atrás', true);
                                        self.render(containerId, mappedData, null, 'Porcentaje de Plazas Docentes en el Distrito de ' + point.name, 'distrito_aislado');
                                    }
                                }
                                point.lastClick = 0;
                            } else {
                                point.lastClick = t;
                            }
                        }
                    }
                }
            }],
            credits: { enabled: false }
        });
    }
};
