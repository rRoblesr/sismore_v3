#valores aleatorios
SELECT round(rand()*(13-5)+5,1);


UPDATE `alumno` SET anemia=IF(round(rand()*1,0)=1,'SI','NO') WHERE 1;

# convierte fechanumero de excel a date mkysql
select date(from_unixtime((45169+1-25569)*86400));

select lpad(floor(rand()*99999999),8,'0');

UPDATE alumno aa
INNER JOIN datax1 dd ON dd.dni = aa.dni 
AND dd.codi_modular IN (3382012, 3989603, 0584458, 1695710, 0270850, 3877633)
SET aa.fecha = DATE_FORMAT(STR_TO_DATE(dd.fecha_nacimiento, '%d/%m/%Y'), '%Y-%m-%d');

##################################################################

WITH RankedData AS (
    SELECT
        mg.id,
        anio.anio,
        ROW_NUMBER() OVER (PARTITION BY anio.anio ORDER BY mg.id DESC) AS rn
    FROM
        edu_matricula_general mg
    INNER JOIN par_importacion AS imp ON imp.id = mg.importacion_id
    INNER JOIN par_anio AS anio ON anio.id = mg.anio_id
    WHERE
        imp.estado = 'PR'
)
SELECT
    id,
    anio
FROM
    RankedData
WHERE
    rn = 1;
#################################################    

################# tablas relacionadas ##################    
SELECT TABLE_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'bdsismore' AND REFERENCED_TABLE_NAME = 'par_importacion';


#################################################    
SELECT TABLE_NAME AS tabla_relacionada, COLUMN_NAME AS columna_en_tabla_relacionada, CONSTRAINT_NAME AS nombre_llave_foranea, REFERENCED_TABLE_NAME AS tabla_referenciada, REFERENCED_COLUMN_NAME AS columna_en_tabla_referenciada FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'par_centropoblado';

#################################################    




