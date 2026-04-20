/* VISTA: educación/Nexus/Reportes
   Parámetros:
   :anio, :ugel, :modalidad, :nivel
   Convención de filtros:
   (:ugel = 0 OR ie.ugel_id = :ugel)
   (:modalidad = 0 OR nm.modalidad_id = :modalidad)
   (:nivel = 0 OR nm.id = :nivel)
*/

/* =============== */
/* FILTROS (SELECT) */
/* =============== */

/* Filtro UGEL por año (filtro_ugel_deanio) */
SELECT DISTINCT
  u.id,
  u.nombre
FROM edu_nexus AS n
INNER JOIN edu_nexus_institucion_educativa AS ie
  ON ie.id = n.institucioneducativa_id
INNER JOIN edu_nexus_ugel AS u
  ON u.id = ie.ugel_id
WHERE n.importacion_id = (
  SELECT id
  FROM par_importacion
  WHERE fuenteImportacion_id = 2
    AND estado = 'PR'
    AND fechaActualizacion = (
      SELECT MAX(fechaActualizacion)
      FROM par_importacion
      WHERE fuenteImportacion_id = 2
        AND YEAR(fechaActualizacion) = :anio
        AND estado = 'PR'
    )
  LIMIT 1
)
ORDER BY u.nombre;

/* Filtro Modalidad por año + ugel (filtro_modalidad_deaniougel) */
SELECT DISTINCT
  m.id,
  m.nombre
FROM edu_nexus AS n
INNER JOIN edu_nexus_institucion_educativa AS ie
  ON ie.id = n.institucioneducativa_id
INNER JOIN edu_nexus_nivel_educativo AS nm
  ON nm.id = ie.niveleducativo_id
INNER JOIN edu_nexus_modalidad AS m
  ON m.id = nm.modalidad_id
WHERE n.importacion_id = (
  SELECT id
  FROM par_importacion
  WHERE fuenteImportacion_id = 2
    AND estado = 'PR'
    AND fechaActualizacion = (
      SELECT MAX(fechaActualizacion)
      FROM par_importacion
      WHERE fuenteImportacion_id = 2
        AND YEAR(fechaActualizacion) = :anio
        AND estado = 'PR'
    )
  LIMIT 1
)
  AND (:ugel = 0 OR ie.ugel_id = :ugel)
ORDER BY m.nombre;

/* Filtro Nivel por año + ugel + modalidad (filtro_nivel_deaniougelmodalidad) */
SELECT DISTINCT
  nm.id,
  nm.nombre
FROM edu_nexus AS n
INNER JOIN edu_nexus_institucion_educativa AS ie
  ON ie.id = n.institucioneducativa_id
INNER JOIN edu_nexus_nivel_educativo AS nm
  ON nm.id = ie.niveleducativo_id
INNER JOIN edu_nexus_modalidad AS m
  ON m.id = nm.modalidad_id
WHERE n.importacion_id = (
  SELECT id
  FROM par_importacion
  WHERE fuenteImportacion_id = 2
    AND estado = 'PR'
    AND fechaActualizacion = (
      SELECT MAX(fechaActualizacion)
      FROM par_importacion
      WHERE fuenteImportacion_id = 2
        AND YEAR(fechaActualizacion) = :anio
        AND estado = 'PR'
    )
  LIMIT 1
)
  AND (:ugel = 0 OR ie.ugel_id = :ugel)
  AND (:modalidad = 0 OR m.id = :modalidad)
ORDER BY nm.nombre;

/* ===================== */
/* REPORTE: HEAD (cards) */
/* ===================== */

/* reportesreporte_head */
SELECT
  COUNT(DISTINCT CASE WHEN stt.dependencia = 1 AND stt.id IN (8, 9, 15) THEN nx.cod_plaza END) AS docentes,
  COUNT(DISTINCT CASE WHEN stt.dependencia = 1 AND stt.id = 16 THEN nx.cod_plaza END) AS auxiliar,
  COUNT(DISTINCT CASE WHEN stt.dependencia = 4 THEN nx.cod_plaza END) AS promotor,
  COUNT(DISTINCT CASE WHEN stt.dependencia IN (2, 3) THEN nx.cod_plaza END) AS administrativo
FROM edu_nexus AS nx
LEFT JOIN edu_nexus_regimen_laboral AS stt
  ON stt.id = nx.regimenlaboral_id
LEFT JOIN edu_nexus_institucion_educativa AS ie
  ON ie.id = nx.institucioneducativa_id
LEFT JOIN edu_nexus_nivel_educativo AS nm
  ON nm.id = ie.niveleducativo_id
WHERE nx.importacion_id = (
  SELECT id
  FROM par_importacion
  WHERE fuenteImportacion_id = 2
    AND estado = 'PR'
    AND fechaActualizacion = (
      SELECT MAX(fechaActualizacion)
      FROM par_importacion
      WHERE fuenteImportacion_id = 2
        AND YEAR(fechaActualizacion) = :anio
        AND estado = 'PR'
    )
  LIMIT 1
)
  AND (:ugel = 0 OR ie.ugel_id = :ugel)
  AND (:modalidad = 0 OR nm.modalidad_id = :modalidad)
  AND (:nivel = 0 OR nm.id = :nivel);

/* =================== */
/* REPORTE: ANAL 1 MAP */
/* =================== */

/* reportesreporte_anal1 */
SELECT
  CASE
    WHEN p.codigo = '2501' THEN 'pe-uc-cp'
    WHEN p.codigo = '2502' THEN 'pe-uc-at'
    WHEN p.codigo = '2503' THEN 'pe-uc-pa'
    WHEN p.codigo = '2504' THEN 'pe-uc-pr'
  END AS codigo,
  p.nombre AS provincia,
  COUNT(DISTINCT nx.cod_plaza) AS conteo
FROM edu_nexus AS nx
LEFT JOIN edu_nexus_regimen_laboral AS stt
  ON stt.id = nx.regimenlaboral_id
LEFT JOIN edu_nexus_institucion_educativa AS ie
  ON ie.id = nx.institucioneducativa_id
LEFT JOIN edu_nexus_nivel_educativo AS nm
  ON nm.id = ie.niveleducativo_id
LEFT JOIN edu_nexus_modalidad AS m
  ON m.id = nm.modalidad_id
LEFT JOIN par_ubigeo AS d
  ON d.id = ie.ubigeo_id
LEFT JOIN par_ubigeo AS p
  ON p.id = d.dependencia
WHERE stt.id IN (8, 9, 15, 17)
  AND nx.importacion_id = (
    SELECT id
    FROM par_importacion
    WHERE fuenteImportacion_id = 2
      AND estado = 'PR'
      AND fechaActualizacion = (
        SELECT MAX(fechaActualizacion)
        FROM par_importacion
        WHERE fuenteImportacion_id = 2
          AND YEAR(fechaActualizacion) = :anio
          AND estado = 'PR'
      )
    LIMIT 1
  )
  AND (:ugel = 0 OR ie.ugel_id = :ugel)
  AND (:modalidad = 0 OR nm.modalidad = :modalidad)
  AND (:nivel = 0 OR nm.id = :nivel)
GROUP BY p.codigo, p.nombre;

/* ===================== */
/* REPORTE: ANAL 2 LINEA */
/* ===================== */

/* reportesreporte_anal2 (serie mensual: toma última importación PR por mes del año) */
SELECT
  m.codigo,
  m.abreviado AS mes,
  dt.conteo AS conteo
FROM par_mes AS m
LEFT JOIN (
  SELECT
    imp.mes,
    COUNT(DISTINCT nx.cod_plaza) AS conteo
  FROM edu_nexus AS nx
  INNER JOIN edu_nexus_regimen_laboral AS stt
    ON stt.id = nx.regimenlaboral_id
   AND stt.id IN (8, 9, 15)
  INNER JOIN edu_nexus_institucion_educativa AS ie
    ON ie.id = nx.institucioneducativa_id
  INNER JOIN edu_nexus_nivel_educativo AS nm
    ON nm.id = ie.niveleducativo_id
  INNER JOIN (
    SELECT id, MONTH(fechaActualizacion) AS mes
    FROM (
      SELECT
        id,
        fechaActualizacion,
        ROW_NUMBER() OVER (
          PARTITION BY YEAR(fechaActualizacion), MONTH(fechaActualizacion)
          ORDER BY fechaActualizacion DESC
        ) AS rn
      FROM par_importacion
      WHERE fuenteImportacion_id = 2
        AND estado = 'PR'
        AND YEAR(fechaActualizacion) = :anio
    ) AS ranked
    WHERE rn = 1
  ) AS imp
    ON imp.id = nx.importacion_id
  WHERE (:ugel = 0 OR ie.ugel_id = :ugel)
    AND (:modalidad = 0 OR nm.modalidad_id = :modalidad)
    AND (:nivel = 0 OR nm.id = :nivel)
  GROUP BY imp.mes
) AS dt
  ON dt.mes = m.codigo
WHERE m.codigo BETWEEN 1 AND 12
ORDER BY m.codigo;

/* ===================== */
/* REPORTE: ANAL 3 (SEXO) */
/* ===================== */

/* reportesreporte_anal3 */
SELECT
  CASE WHEN s.nombre2 IS NULL THEN 'NO DEFINIDO' ELSE s.nombre2 END AS name,
  COUNT(DISTINCT nx.cod_plaza) AS y
FROM edu_nexus AS nx
LEFT JOIN edu_nexus_regimen_laboral AS stt
  ON stt.id = nx.regimenlaboral_id
LEFT JOIN edu_nexus_institucion_educativa AS ie
  ON ie.id = nx.institucioneducativa_id
LEFT JOIN edu_nexus_nivel_educativo AS nm
  ON nm.id = ie.niveleducativo_id
LEFT JOIN edu_nexus_modalidad AS m
  ON m.id = nm.modalidad_id
INNER JOIN edu_nexus_trabajador AS t
  ON t.id = nx.trabajador_id
LEFT JOIN par_sexo AS s
  ON s.id = t.sexo_id
LEFT JOIN par_ubigeo AS d
  ON d.id = ie.ubigeo_id
LEFT JOIN par_ubigeo AS p
  ON p.id = d.dependencia
WHERE stt.id IN (8, 9, 15)
  AND nx.importacion_id = (
    SELECT id
    FROM par_importacion
    WHERE fuenteImportacion_id = 2
      AND estado = 'PR'
      AND fechaActualizacion = (
        SELECT MAX(fechaActualizacion)
        FROM par_importacion
        WHERE fuenteImportacion_id = 2
          AND YEAR(fechaActualizacion) = :anio
          AND estado = 'PR'
      )
    LIMIT 1
  )
  AND (:ugel = 0 OR ie.ugel_id = :ugel)
  AND (:modalidad = 0 OR nm.modalidad_id = :modalidad)
  AND (:nivel = 0 OR nm.id = :nivel)
GROUP BY s.nombre2
ORDER BY y DESC;

/* =============================== */
/* REPORTE: ANAL 4 (SITUACION LAB) */
/* =============================== */

/* reportesreporte_anal4 */
SELECT
  sl.nombre AS name,
  COUNT(DISTINCT nx.cod_plaza) AS y
FROM edu_nexus AS nx
LEFT JOIN edu_nexus_situacion_laboral AS sl
  ON sl.id = nx.situacionlaboral_id
LEFT JOIN edu_nexus_institucion_educativa AS ie
  ON ie.id = nx.institucioneducativa_id
LEFT JOIN edu_nexus_nivel_educativo AS nm
  ON nm.id = ie.niveleducativo_id
LEFT JOIN edu_nexus_modalidad AS m
  ON m.id = nm.modalidad_id
LEFT JOIN par_ubigeo AS d
  ON d.id = ie.ubigeo_id
LEFT JOIN par_ubigeo AS p
  ON p.id = d.dependencia
WHERE sl.id IN (1, 6)
  AND nx.importacion_id = (
    SELECT id
    FROM par_importacion
    WHERE fuenteImportacion_id = 2
      AND estado = 'PR'
      AND fechaActualizacion = (
        SELECT MAX(fechaActualizacion)
        FROM par_importacion
        WHERE fuenteImportacion_id = 2
          AND YEAR(fechaActualizacion) = :anio
          AND estado = 'PR'
      )
    LIMIT 1
  )
  AND (:ugel = 0 OR ie.ugel_id = :ugel)
  AND (:modalidad = 0 OR nm.modalidad_id = :modalidad)
  AND (:nivel = 0 OR nm.id = :nivel)
GROUP BY sl.nombre
ORDER BY y DESC;

/* ================================== */
/* REPORTE: TABLA 1 (por UGEL) */
/* ================================== */

/* reportesreporte_tabla01 */
SELECT
  u.nombre AS ugel,
  SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id IN (1,6,7) THEN 1 ELSE 0 END) AS td,
  SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id = 6 THEN 1 ELSE 0 END) AS tdn,
  SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id = 1 THEN 1 ELSE 0 END) AS tdc,
  SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id = 7 THEN 1 ELSE 0 END) AS tdv,
  SUM(CASE WHEN subtipo.id = 16 AND sl.id IN (1,6,7) THEN 1 ELSE 0 END) AS ta,
  SUM(CASE WHEN subtipo.id = 16 AND sl.id = 6 THEN 1 ELSE 0 END) AS tan,
  SUM(CASE WHEN subtipo.id = 16 AND sl.id = 1 THEN 1 ELSE 0 END) AS tac,
  SUM(CASE WHEN subtipo.id = 16 AND sl.id = 7 THEN 1 ELSE 0 END) AS tav,
  SUM(CASE WHEN tipo.id IN (2,3) AND sl.id IN (6,1,3,7) THEN 1 ELSE 0 END) AS tad,
  SUM(CASE WHEN tipo.id IN (2,3) AND sl.id = 6 THEN 1 ELSE 0 END) AS tadn,
  SUM(CASE WHEN tipo.id IN (2,3) AND sl.id = 1 THEN 1 ELSE 0 END) AS tadc,
  SUM(CASE WHEN tipo.id IN (2,3) AND sl.id = 3 THEN 1 ELSE 0 END) AS tadd,
  SUM(CASE WHEN tipo.id IN (2,3) AND sl.id = 7 THEN 1 ELSE 0 END) AS tadv,
  SUM(CASE WHEN subtipo.id = 17 AND sl.id = 1 THEN 1 ELSE 0 END) AS tpc
FROM edu_nexus AS nx
LEFT JOIN edu_nexus_regimen_laboral AS subtipo
  ON subtipo.id = nx.regimenlaboral_id
LEFT JOIN edu_nexus_regimen_laboral AS tipo
  ON tipo.id = subtipo.dependencia
LEFT JOIN edu_nexus_institucion_educativa AS ie
  ON ie.id = nx.institucioneducativa_id
LEFT JOIN edu_nexus_nivel_educativo AS nm
  ON nm.id = ie.niveleducativo_id
LEFT JOIN edu_nexus_modalidad AS m
  ON m.id = nm.modalidad_id
LEFT JOIN edu_nexus_ugel AS u
  ON u.id = ie.ugel_id
LEFT JOIN edu_nexus_situacion_laboral AS sl
  ON sl.id = nx.situacionlaboral_id
LEFT JOIN par_ubigeo AS d
  ON d.id = ie.ubigeo_id
LEFT JOIN par_ubigeo AS p
  ON p.id = d.dependencia
WHERE nx.importacion_id = (
  SELECT id
  FROM par_importacion
  WHERE fuenteImportacion_id = 2
    AND estado = 'PR'
    AND fechaActualizacion = (
      SELECT MAX(fechaActualizacion)
      FROM par_importacion
      WHERE fuenteImportacion_id = 2
        AND YEAR(fechaActualizacion) = :anio
        AND estado = 'PR'
    )
  LIMIT 1
)
  AND (:ugel = 0 OR ie.ugel_id = :ugel)
  AND (:modalidad = 0 OR nm.modalidad_id = :modalidad)
  AND (:nivel = 0 OR nm.id = :nivel)
GROUP BY u.nombre
ORDER BY u.nombre;

/* ================================== */
/* REPORTE: TABLA 2 (por LEY) */
/* ================================== */

/* reportesreporte_tabla02 */
SELECT
  l.nombre AS ley,
  SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id IN (1,6,7) THEN 1 ELSE 0 END) AS td,
  SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id = 6 THEN 1 ELSE 0 END) AS tdn,
  SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id = 1 THEN 1 ELSE 0 END) AS tdc,
  SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id = 7 THEN 1 ELSE 0 END) AS tdv,
  SUM(CASE WHEN subtipo.id = 16 AND sl.id IN (1,6,7) THEN 1 ELSE 0 END) AS ta,
  SUM(CASE WHEN subtipo.id = 16 AND sl.id = 6 THEN 1 ELSE 0 END) AS tan,
  SUM(CASE WHEN subtipo.id = 16 AND sl.id = 1 THEN 1 ELSE 0 END) AS tac,
  SUM(CASE WHEN subtipo.id = 16 AND sl.id = 7 THEN 1 ELSE 0 END) AS tav,
  SUM(CASE WHEN tipo.id IN (2,3) AND sl.id IN (6,1,3,7) THEN 1 ELSE 0 END) AS tad,
  SUM(CASE WHEN tipo.id IN (2,3) AND sl.id = 6 THEN 1 ELSE 0 END) AS tadn,
  SUM(CASE WHEN tipo.id IN (2,3) AND sl.id = 1 THEN 1 ELSE 0 END) AS tadc,
  SUM(CASE WHEN tipo.id IN (2,3) AND sl.id = 3 THEN 1 ELSE 0 END) AS tadd,
  SUM(CASE WHEN tipo.id IN (2,3) AND sl.id = 7 THEN 1 ELSE 0 END) AS tadv,
  SUM(CASE WHEN subtipo.id = 17 AND sl.id = 1 THEN 1 ELSE 0 END) AS tpc
FROM edu_nexus AS nx
LEFT JOIN edu_nexus_regimen_laboral AS subtipo
  ON subtipo.id = nx.regimenlaboral_id
LEFT JOIN edu_nexus_regimen_laboral AS tipo
  ON tipo.id = subtipo.dependencia
LEFT JOIN edu_nexus_institucion_educativa AS ie
  ON ie.id = nx.institucioneducativa_id
LEFT JOIN edu_nexus_nivel_educativo AS nm
  ON nm.id = ie.niveleducativo_id
LEFT JOIN edu_nexus_modalidad AS m
  ON m.id = nm.modalidad_id
LEFT JOIN edu_nexus_ley AS l
  ON l.id = ie.ugel_id
LEFT JOIN edu_nexus_situacion_laboral AS sl
  ON sl.id = nx.situacionlaboral_id
LEFT JOIN par_ubigeo AS d
  ON d.id = ie.ubigeo_id
LEFT JOIN par_ubigeo AS p
  ON p.id = d.dependencia
WHERE nx.importacion_id = (
  SELECT id
  FROM par_importacion
  WHERE fuenteImportacion_id = 2
    AND estado = 'PR'
    AND fechaActualizacion = (
      SELECT MAX(fechaActualizacion)
      FROM par_importacion
      WHERE fuenteImportacion_id = 2
        AND YEAR(fechaActualizacion) = :anio
        AND estado = 'PR'
    )
  LIMIT 1
)
  AND (:ugel = 0 OR ie.ugel_id = :ugel)
  AND (:modalidad = 0 OR nm.modalidad_id = :modalidad)
  AND (:nivel = 0 OR nm.id = :nivel)
GROUP BY l.nombre
ORDER BY l.nombre;

/* ================================== */
/* REPORTE: TABLA 3 (por DISTRITO) */
/* ================================== */

/* reportesreporte_tabla03 */
SELECT
  d.nombre AS distrito,
  SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id IN (1,6,7) THEN 1 ELSE 0 END) AS td,
  SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id = 6 THEN 1 ELSE 0 END) AS tdn,
  SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id = 1 THEN 1 ELSE 0 END) AS tdc,
  SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id = 7 THEN 1 ELSE 0 END) AS tdv,
  SUM(CASE WHEN subtipo.id = 16 AND sl.id IN (1,6,7) THEN 1 ELSE 0 END) AS ta,
  SUM(CASE WHEN subtipo.id = 16 AND sl.id = 6 THEN 1 ELSE 0 END) AS tan,
  SUM(CASE WHEN subtipo.id = 16 AND sl.id = 1 THEN 1 ELSE 0 END) AS tac,
  SUM(CASE WHEN subtipo.id = 16 AND sl.id = 7 THEN 1 ELSE 0 END) AS tav,
  SUM(CASE WHEN tipo.id IN (2,3) AND sl.id IN (6,1,3,7) THEN 1 ELSE 0 END) AS tad,
  SUM(CASE WHEN tipo.id IN (2,3) AND sl.id = 6 THEN 1 ELSE 0 END) AS tadn,
  SUM(CASE WHEN tipo.id IN (2,3) AND sl.id = 1 THEN 1 ELSE 0 END) AS tadc,
  SUM(CASE WHEN tipo.id IN (2,3) AND sl.id = 3 THEN 1 ELSE 0 END) AS tadd,
  SUM(CASE WHEN tipo.id IN (2,3) AND sl.id = 7 THEN 1 ELSE 0 END) AS tadv,
  SUM(CASE WHEN subtipo.id = 17 AND sl.id = 1 THEN 1 ELSE 0 END) AS tpc
FROM edu_nexus AS nx
LEFT JOIN edu_nexus_regimen_laboral AS subtipo
  ON subtipo.id = nx.regimenlaboral_id
LEFT JOIN edu_nexus_regimen_laboral AS tipo
  ON tipo.id = subtipo.dependencia
LEFT JOIN edu_nexus_institucion_educativa AS ie
  ON ie.id = nx.institucioneducativa_id
LEFT JOIN edu_nexus_nivel_educativo AS nm
  ON nm.id = ie.niveleducativo_id
LEFT JOIN edu_nexus_modalidad AS m
  ON m.id = nm.modalidad_id
LEFT JOIN edu_nexus_ley AS l
  ON l.id = ie.ugel_id
LEFT JOIN edu_nexus_situacion_laboral AS sl
  ON sl.id = nx.situacionlaboral_id
LEFT JOIN par_ubigeo AS d
  ON d.id = ie.ubigeo_id
LEFT JOIN par_ubigeo AS p
  ON p.id = d.dependencia
WHERE nx.importacion_id = (
  SELECT id
  FROM par_importacion
  WHERE fuenteImportacion_id = 2
    AND estado = 'PR'
    AND fechaActualizacion = (
      SELECT MAX(fechaActualizacion)
      FROM par_importacion
      WHERE fuenteImportacion_id = 2
        AND YEAR(fechaActualizacion) = :anio
        AND estado = 'PR'
    )
  LIMIT 1
)
  AND (:ugel = 0 OR ie.ugel_id = :ugel)
  AND (:modalidad = 0 OR nm.modalidad_id = :modalidad)
  AND (:nivel = 0 OR nm.id = :nivel)
GROUP BY d.nombre
ORDER BY d.codigo;

/* ================================== */
/* REPORTE: TABLA 4 (por IIEE) */
/* ================================== */

/* reportesreporte_tabla04 */
SELECT
  ie.cod_mod AS modular,
  ie.institucion_educativa AS iiee,
  tie.nombre AS tipo,
  nm.nombre AS nivel,
  g.nombre AS gestion,
  z.nombre AS zona,
  d.nombre AS distrito,
  COUNT(DISTINCT nx.cod_plaza) AS conteo,
  COUNT(DISTINCT CASE WHEN subtipo.dependencia = 1 AND subtipo.id IN (8,9,15) THEN nx.cod_plaza END) AS docentes,
  COUNT(DISTINCT CASE WHEN subtipo.dependencia = 1 AND subtipo.id = 16 THEN nx.cod_plaza END) AS auxiliar,
  COUNT(DISTINCT CASE WHEN subtipo.dependencia = 4 THEN nx.cod_plaza END) AS promotor,
  COUNT(DISTINCT CASE WHEN subtipo.dependencia IN (2,3) THEN nx.cod_plaza END) AS administrativo
FROM edu_nexus AS nx
LEFT JOIN edu_nexus_regimen_laboral AS subtipo
  ON subtipo.id = nx.regimenlaboral_id
LEFT JOIN edu_nexus_institucion_educativa AS ie
  ON ie.id = nx.institucioneducativa_id
LEFT JOIN edu_nexus_nivel_educativo AS nm
  ON nm.id = ie.niveleducativo_id
LEFT JOIN edu_nexus_modalidad AS m
  ON m.id = nm.modalidad_id
LEFT JOIN edu_nexus_situacion_laboral AS sl
  ON sl.id = nx.situacionlaboral_id
LEFT JOIN edu_nexus_tipo_ie AS tie
  ON tie.id = ie.tipoie_id
LEFT JOIN edu_nexus_gestion AS g
  ON g.id = ie.gestion_id
LEFT JOIN edu_nexus_zona AS z
  ON z.id = ie.zona_id
LEFT JOIN par_ubigeo AS d
  ON d.id = ie.ubigeo_id
LEFT JOIN par_ubigeo AS p
  ON p.id = d.dependencia
WHERE nx.importacion_id = (
  SELECT id
  FROM par_importacion
  WHERE fuenteImportacion_id = 2
    AND estado = 'PR'
    AND fechaActualizacion = (
      SELECT MAX(fechaActualizacion)
      FROM par_importacion
      WHERE fuenteImportacion_id = 2
        AND YEAR(fechaActualizacion) = :anio
        AND estado = 'PR'
    )
  LIMIT 1
)
  AND (:ugel = 0 OR ie.ugel_id = :ugel)
  AND (:modalidad = 0 OR nm.modalidad_id = :modalidad)
  AND (:nivel = 0 OR nm.id = :nivel)
GROUP BY
  ie.cod_mod,
  ie.institucion_educativa,
  tie.nombre,
  nm.nombre,
  g.nombre,
  z.nombre,
  d.nombre;
