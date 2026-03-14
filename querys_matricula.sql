SELECT 
    CASE 
        WHEN nroced = '1A' AND cuadro = 'C201' THEN '1A EBR inicial C201'
        WHEN nroced = '2A' AND cuadro = 'C201' THEN '2A EBR inicial C201'
        WHEN nroced = '3AP' AND cuadro = 'C201' THEN '3AP EBR primaria C201'
        WHEN nroced = '3AS' AND cuadro = 'C201' THEN '3AS EBR secundaria C201'
        WHEN nroced = '4AI' AND cuadro = 'C201' THEN '4AI EBA INICIAL E INTERMEDIO C201'
        WHEN nroced = '4AA' AND cuadro = 'C201' THEN '4AA EBA AVANZADO C201'
        WHEN nroced = '4AA' AND cuadro = 'C202' THEN '4AA EBA AVANZADO C202'
        WHEN nroced = '5A' AND cuadro = 'C201' THEN '5A SNU PEDAGOGICA C201'
        WHEN nroced = '6A' AND cuadro = 'C208' THEN '6A SNU TECNOLOGICA C208'
        WHEN nroced = '7A' AND cuadro = 'C201' THEN '7A SNU ARTISTICA C201'
        WHEN nroced = '7A' AND cuadro = 'C209' THEN '7A SNU ARTISTICA C209'
        WHEN nroced = '8A' AND cuadro = 'C201' THEN '8A EBE INICIAL NO ESCOLARIZADO C201'
        WHEN nroced = '8AI' AND cuadro = 'C201' THEN '8AI EBE INICIAL ESCOLARIZADO C201'
        WHEN nroced = '8AP' AND cuadro = 'C201' THEN '8AP EBE PRIMARIA C201'
        WHEN nroced = '9A' AND cuadro = 'C201' THEN '9A SNU TECNICA C201'
    END AS modalidad,
    CASE 
        WHEN nroced IN ('1A', '2A', '3AP', '3AS') THEN 'EBR'
        WHEN nroced IN ('4AI', '4AA') THEN 'EBA'
        WHEN nroced IN ('8A', '8AI', '8AP') THEN 'EBE'
        WHEN nroced IN ('5A', '6A', '7A', '9A') THEN 'SNU'
    END AS categoria,
    SUM(
        CASE 
            WHEN (nroced IN ('1A','2A') AND cuadro = 'C201') THEN d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14
            WHEN (nroced = '3AP' AND cuadro = 'C201') THEN d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12
            WHEN (nroced = '3AS' AND cuadro = 'C201') THEN d01+d02+d03+d04+d05+d06+d07+d08+d09+d10
            WHEN (nroced = '4AI' AND cuadro = 'C201') THEN d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20
            WHEN (nroced = '4AA' AND cuadro IN ('C201','C202')) THEN d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16
            WHEN (nroced = '5A' AND cuadro = 'C201') THEN d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20
            WHEN (nroced = '6A' AND cuadro = 'C208') THEN d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12
            WHEN (nroced = '7A' AND cuadro IN ('C201','C209')) THEN d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20
            WHEN (nroced = '8A' AND cuadro = 'C201') THEN d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16
            WHEN (nroced IN ('8AI','8AP') AND cuadro = 'C201') THEN d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22
            WHEN (nroced = '9A' AND cuadro = 'C201') THEN d01+d02+d03+d04
        END
    ) AS total,
    SUM(
        CASE 
            WHEN (nroced IN ('1A','2A') AND cuadro = 'C201') THEN d01+d03+d05+d07+d09+d11+d13
            WHEN (nroced = '3AP' AND cuadro = 'C201') THEN d01+d03+d05+d07+d09+d11
            WHEN (nroced = '3AS' AND cuadro = 'C201') THEN d01+d03+d05+d07+d09
            WHEN (nroced = '4AI' AND cuadro = 'C201') THEN d01+d03+d05+d07+d09+d11+d13+d15+d17+d19
            WHEN (nroced = '4AA' AND cuadro IN ('C201','C202')) THEN d01+d03+d05+d07+d09+d11+d13+d15
            WHEN (nroced = '5A' AND cuadro = 'C201') THEN d01+d03+d05+d07+d09+d11+d13+d15+d17+d19
            WHEN (nroced = '6A' AND cuadro = 'C208') THEN d01+d03+d05+d07+d09+d11
            WHEN (nroced = '7A' AND cuadro IN ('C201','C209')) THEN d01+d03+d05+d07+d09+d11+d13+d15+d17+d19
            WHEN (nroced = '8A' AND cuadro = 'C201') THEN d01+d03+d05+d07+d09+d11+d13+d15
            WHEN (nroced IN ('8AI','8AP') AND cuadro = 'C201') THEN d01+d03+d05+d07+d09+d11+d13+d15+d17+d19+d21
            WHEN (nroced = '9A' AND cuadro = 'C201') THEN d01+d03
        END
    ) AS hombres,
    SUM(
        CASE 
            WHEN (nroced IN ('1A','2A') AND cuadro = 'C201') THEN d02+d04+d06+d08+d10+d12+d14
            WHEN (nroced = '3AP' AND cuadro = 'C201') THEN d02+d04+d06+d08+d10+d12
            WHEN (nroced = '3AS' AND cuadro = 'C201') THEN d02+d04+d06+d08+d10
            WHEN (nroced = '4AI' AND cuadro = 'C201') THEN d02+d04+d06+d08+d10+d12+d14+d16+d18+d20
            WHEN (nroced = '4AA' AND cuadro IN ('C201','C202')) THEN d02+d04+d06+d08+d10+d12+d14+d16
            WHEN (nroced = '5A' AND cuadro = 'C201') THEN d02+d04+d06+d08+d10+d12+d14+d16+d18+d20
            WHEN (nroced = '6A' AND cuadro = 'C208') THEN d02+d04+d06+d08+d10+d12
            WHEN (nroced = '7A' AND cuadro IN ('C201','C209')) THEN d02+d04+d06+d08+d10+d12+d14+d16+d18+d20
            WHEN (nroced = '8A' AND cuadro = 'C201') THEN d02+d04+d06+d08+d10+d12+d14+d16
            WHEN (nroced IN ('8AI','8AP') AND cuadro = 'C201') THEN d02+d04+d06+d08+d10+d12+d14+d16+d18+d20+d22
            WHEN (nroced = '9A' AND cuadro = 'C201') THEN d02+d04
        END
    ) AS mujeres
FROM edu_impor_censomatricula 
WHERE importacion_id = 3267 
  AND nroced IN ('1A','2A','3AP','3AS','4AI','4AA','5A','6A','7A','8A','8AI','8AP','9A')
  AND cuadro IN ('C201','C202','C208','C209')
  AND (
        (nroced IN ('1A','2A','3AP','3AS','5A','8A','8AI','8AP','9A') AND cuadro = 'C201')
     OR (nroced = '4AI' AND cuadro = 'C201')
     OR (nroced = '4AA' AND cuadro IN ('C201','C202'))
     OR (nroced = '6A' AND cuadro = 'C208')
     OR (nroced = '7A' AND cuadro IN ('C201','C209'))
    )
GROUP BY 
    CASE 
        WHEN nroced = '1A' AND cuadro = 'C201' THEN '1A EBR inicial C201'
        WHEN nroced = '2A' AND cuadro = 'C201' THEN '2A EBR inicial C201'
        WHEN nroced = '3AP' AND cuadro = 'C201' THEN '3AP EBR primaria C201'
        WHEN nroced = '3AS' AND cuadro = 'C201' THEN '3AS EBR secundaria C201'
        WHEN nroced = '4AI' AND cuadro = 'C201' THEN '4AI EBA INICIAL E INTERMEDIO C201'
        WHEN nroced = '4AA' AND cuadro = 'C201' THEN '4AA EBA AVANZADO C201'
        WHEN nroced = '4AA' AND cuadro = 'C202' THEN '4AA EBA AVANZADO C202'
        WHEN nroced = '5A' AND cuadro = 'C201' THEN '5A SNU PEDAGOGICA C201'
        WHEN nroced = '6A' AND cuadro = 'C208' THEN '6A SNU TECNOLOGICA C208'
        WHEN nroced = '7A' AND cuadro = 'C201' THEN '7A SNU ARTISTICA C201'
        WHEN nroced = '7A' AND cuadro = 'C209' THEN '7A SNU ARTISTICA C209'
        WHEN nroced = '8A' AND cuadro = 'C201' THEN '8A EBE INICIAL NO ESCOLARIZADO C201'
        WHEN nroced = '8AI' AND cuadro = 'C201' THEN '8AI EBE INICIAL ESCOLARIZADO C201'
        WHEN nroced = '8AP' AND cuadro = 'C201' THEN '8AP EBE PRIMARIA C201'
        WHEN nroced = '9A' AND cuadro = 'C201' THEN '9A SNU TECNICA C201'
    END,
    CASE 
        WHEN nroced IN ('1A', '2A', '3AP', '3AS') THEN 'EBR'
        WHEN nroced IN ('4AI', '4AA') THEN 'EBA'
        WHEN nroced IN ('8A', '8AI', '8AP') THEN 'EBE'
        WHEN nroced IN ('5A', '6A', '7A', '9A') THEN 'SNU'
    END;


    
        /* Consulta Agrupada por codooii (UGEL) */
SELECT 
    codooii,
    SUM(
        CASE 
            WHEN (nroced IN ('1A','2A') AND cuadro = 'C201') THEN d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14
            WHEN (nroced = '3AP' AND cuadro = 'C201') THEN d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12
            WHEN (nroced = '3AS' AND cuadro = 'C201') THEN d01+d02+d03+d04+d05+d06+d07+d08+d09+d10
            WHEN (nroced = '4AI' AND cuadro = 'C201') THEN d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20
            WHEN (nroced = '4AA' AND cuadro IN ('C201','C202')) THEN d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16
            WHEN (nroced = '5A' AND cuadro = 'C201') THEN d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20
            WHEN (nroced = '6A' AND cuadro = 'C208') THEN d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12
            WHEN (nroced = '7A' AND cuadro IN ('C201','C209')) THEN d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20
            WHEN (nroced = '8A' AND cuadro = 'C201') THEN d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16
            WHEN (nroced IN ('8AI','8AP') AND cuadro = 'C201') THEN d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22
            WHEN (nroced = '9A' AND cuadro = 'C201') THEN d01+d02+d03+d04
        END
    ) AS total,
    SUM(
        CASE 
            WHEN (nroced IN ('1A','2A') AND cuadro = 'C201') THEN d01+d03+d05+d07+d09+d11+d13
            WHEN (nroced = '3AP' AND cuadro = 'C201') THEN d01+d03+d05+d07+d09+d11
            WHEN (nroced = '3AS' AND cuadro = 'C201') THEN d01+d03+d05+d07+d09
            WHEN (nroced = '4AI' AND cuadro = 'C201') THEN d01+d03+d05+d07+d09+d11+d13+d15+d17+d19
            WHEN (nroced = '4AA' AND cuadro IN ('C201','C202')) THEN d01+d03+d05+d07+d09+d11+d13+d15
            WHEN (nroced = '5A' AND cuadro = 'C201') THEN d01+d03+d05+d07+d09+d11+d13+d15+d17+d19
            WHEN (nroced = '6A' AND cuadro = 'C208') THEN d01+d03+d05+d07+d09+d11
            WHEN (nroced = '7A' AND cuadro IN ('C201','C209')) THEN d01+d03+d05+d07+d09+d11+d13+d15+d17+d19
            WHEN (nroced = '8A' AND cuadro = 'C201') THEN d01+d03+d05+d07+d09+d11+d13+d15
            WHEN (nroced IN ('8AI','8AP') AND cuadro = 'C201') THEN d01+d03+d05+d07+d09+d11+d13+d15+d17+d19+d21
            WHEN (nroced = '9A' AND cuadro = 'C201') THEN d01+d03
        END
    ) AS hombres,
    SUM(
        CASE 
            WHEN (nroced IN ('1A','2A') AND cuadro = 'C201') THEN d02+d04+d06+d08+d10+d12+d14
            WHEN (nroced = '3AP' AND cuadro = 'C201') THEN d02+d04+d06+d08+d10+d12
            WHEN (nroced = '3AS' AND cuadro = 'C201') THEN d02+d04+d06+d08+d10
            WHEN (nroced = '4AI' AND cuadro = 'C201') THEN d02+d04+d06+d08+d10+d12+d14+d16+d18+d20
            WHEN (nroced = '4AA' AND cuadro IN ('C201','C202')) THEN d02+d04+d06+d08+d10+d12+d14+d16
            WHEN (nroced = '5A' AND cuadro = 'C201') THEN d02+d04+d06+d08+d10+d12+d14+d16+d18+d20
            WHEN (nroced = '6A' AND cuadro = 'C208') THEN d02+d04+d06+d08+d10+d12
            WHEN (nroced = '7A' AND cuadro IN ('C201','C209')) THEN d02+d04+d06+d08+d10+d12+d14+d16+d18+d20
            WHEN (nroced = '8A' AND cuadro = 'C201') THEN d02+d04+d06+d08+d10+d12+d14+d16
            WHEN (nroced IN ('8AI','8AP') AND cuadro = 'C201') THEN d02+d04+d06+d08+d10+d12+d14+d16+d18+d20+d22
            WHEN (nroced = '9A' AND cuadro = 'C201') THEN d02+d04
        END
    ) AS mujeres
FROM edu_impor_censomatricula 
WHERE importacion_id = 3267 
  AND nroced IN ('1A','2A','3AP','3AS','4AI','4AA','5A','6A','7A','8A','8AI','8AP','9A')
  AND cuadro IN ('C201','C202','C208','C209')
  AND (
        (nroced IN ('1A','2A','3AP','3AS','5A','8A','8AI','8AP','9A') AND cuadro = 'C201')
     OR (nroced = '4AI' AND cuadro = 'C201')
     OR (nroced = '4AA' AND cuadro IN ('C201','C202'))
     OR (nroced = '6A' AND cuadro = 'C208')
     OR (nroced = '7A' AND cuadro IN ('C201','C209'))
    )
GROUP BY codooii;



select sexo, count(*) matriculados from edu_cubo_matricula where modalidad='EBA' and anio=2025 group by sexo;

SELECT
  CASE
    WHEN edad BETWEEN  0 AND  4 THEN '00-04'
    WHEN edad BETWEEN  5 AND  9 THEN '05-09'
    WHEN edad BETWEEN 10 AND 14 THEN '10-14'
    WHEN edad BETWEEN 15 AND 19 THEN '15-19'
    WHEN edad BETWEEN 20 AND 24 THEN '20-24'
    WHEN edad BETWEEN 25 AND 29 THEN '25-29'
    WHEN edad BETWEEN 30 AND 34 THEN '30-34'
    WHEN edad BETWEEN 35 AND 39 THEN '35-39'
    WHEN edad BETWEEN 40 AND 44 THEN '40-44'
    ELSE '45 a mas'
  END AS grupos,
  COUNT(*) AS total
FROM edu_cubo_matricula
WHERE id_mod = 3 AND anio = 2025
GROUP BY grupos
ORDER BY
  CASE grupos
    WHEN '00-04' THEN 1 WHEN '05-09' THEN 2 WHEN '10-14' THEN 3 WHEN '15-19' THEN 4
    WHEN '20-24' THEN 5 WHEN '25-29' THEN 6 WHEN '30-34' THEN 7 WHEN '35-39' THEN 8
    WHEN '40-44' THEN 9 ELSE 10
  END;


CREATE TABLE `pres_impor_consulta_amigable` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `importacion_id` int(11) UNSIGNED DEFAULT NULL,
  `tipo` int(11) UNSIGNED DEFAULT NULL,
  `cod_gob_reg` int(11) UNSIGNED DEFAULT NULL,
  `gobiernos_regionales` text DEFAULT NULL,
  `pia` decimal(18,2) DEFAULT NULL,
  `pim` decimal(18,2) DEFAULT NULL,
  `certificacion` decimal(18,2) DEFAULT NULL,
  `compromiso_anual` decimal(18,2) DEFAULT NULL,
  `compromiso_mensual` decimal(18,2) DEFAULT NULL,
  `devengado` decimal(18,2) DEFAULT NULL,
  `girado` decimal(18,2) DEFAULT NULL,
  `avance` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `importacion_id` (`importacion_id`),
  KEY `cod_gob_reg` (`cod_gob_reg`),
  CONSTRAINT `pres_impor_consulta_amigable_ibfk_1`
    FOREIGN KEY (`importacion_id`) 
    REFERENCES `par_importacion` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pres_impor_consulta_amigable` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `importacion_id` int(11) UNSIGNED DEFAULT NULL,
  `tipo` int(11) UNSIGNED DEFAULT NULL,
  `cod_gob_reg` int(11) UNSIGNED DEFAULT NULL,
  `gobiernos_regionales` text DEFAULT NULL,
  `pia` decimal(18,2) DEFAULT NULL,
  `pim` decimal(18,2) DEFAULT NULL,
  `certificacion` decimal(18,2) DEFAULT NULL,
  `compromiso_anual` decimal(18,2) DEFAULT NULL,
  `compromiso_mensual` decimal(18,2) DEFAULT NULL,
  `devengado` decimal(18,2) DEFAULT NULL,
  `girado` decimal(18,2) DEFAULT NULL,
  `avance` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `importacion_id` (`importacion_id`),
  KEY `cod_gob_reg` (`cod_gob_reg`),
  CONSTRAINT `pres_impor_consulta_amigable_ibfk_1`
    FOREIGN KEY (`importacion_id`) REFERENCES `par_importacion` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;





