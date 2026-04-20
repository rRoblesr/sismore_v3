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

####################################################################################
###################################################################################
SELECT 
		257 as basegastos,		
        XD.anio,
        XD.mes,
        (	select v1.id from pres_unidadejecutora v1  
			join pres_pliego v2 on v2.id=v1.pliego_id
            join pres_sector v3 on v3.id=v2.sector_id
            join pres_tipo_gobierno v4 on v4.id=v3.tipogobierno_id
            where v1.secuencia_ejecutora=XD.sec_ejec and v1.codigo_ue=XD.cod_ue and v2.codigo=XD.cod_pliego and v3.codigo=XD.cod_sector and v4.codigo=XD.cod_niv_gob ) as unidadejecutora,		
        (select id from par_ubigeo where codigo=XD.cod_ubigeo) as ubigeo,		
        (select id from pres_meta where anio=XD.anio and sec_fun=XD.sec_func) as meta,
        (select id from pres_categoriapresupuestal where codigo=XD.cod_cat_pres) as catpres,
        (select id from pres_producto_proyecto where codigo=XD.tipo_prod_proy) as tipopp,
        (select id from pres_productos where codigo=XD.cod_prod_proy) as productos,        
        (select id from pres_proyectos where codigo=XD.cod_prod_proy) as proyectos,
        (SELECT id FROM pres_act_acc_obr where codigo=XD.tipo_act_acc_obra) as tipoaao,
        (select id from pres_obra where codigo=XD.cod_act_acc_obra) as obra,
		(select id from pres_actividades where codigo=XD.cod_act_acc_obra) as actividad,
		(select id from pres_accion where codigo=XD.cod_act_acc_obra) as accion,
        (	select v1.id from pres_grupofuncional v1 
			join pres_divisionfuncional v2 on v2.id=v1.divisionfuncional_id  
            join pres_funcion v3 on v3.id=v2.funcion_id 
            where v1.codigo=XD.cod_gru_fun and v2.codigo=XD.cod_div_fun and v3.codigo=XD.cod_fun) as grufun,
		XD.meta,
        (select id from pres_finalidad where codigo=XD.cod_fina) as finalidad,
        (	select v1.id from pres_recursos_gastos v1
			join pres_rubro v2 on v2.id=v1.rubro_id
            join pres_fuentefinanciamiento v3 on v3.id=v2.fuentefinanciamiento_id
            where v1.codigo=XD.cod_tipo_rec and v2.codigo=XD.cod_rub and v3.codigo=XD.cod_fue_fin) as recurso,
        (select id from pres_categoriagasto where codigo=XD.cod_cat_gas) as catgas,
        (	select v1.id from pres_especificadetalle_gastos v1
			join pres_especifica_gastos v2 on v2.id=v1.especifica_id
            join pres_subgenericadetalle_gastos v3 on v3.id=v2.subgenericadetalle_id 
            join pres_subgenerica_gastos v4 on v4.id=v3.subgenerica_id
            join pres_generica_gastos v5 on v5.id=v4.generica_id 
            where v1.codigo=XD.cod_esp_det and v2.codigo=XD.cod_esp and v3.codigo=XD.cod_subgen_det and v4.codigo=XD.cod_subgen and v5.codigo=XD.cod_gen ) as especifica,
		XD.pia,
		XD.pim,
		XD.certificado,
		XD.compromiso_anual,
		XD.compromiso_mensual,
		XD.devengado,
		XD.girado
	FROM pres_impor_gastos as XD
    WHERE XD.importacion_id=3232;
end;


SELECT
  bg.id                                        AS basegastos,
  XD.anio,
  XD.mes,

  ue.id                                        AS unidadejecutora,
  ub.id                                        AS ubigeo,
  mt.id                                        AS meta,
  cp.id                                        AS catpres,
  tpp.id                                       AS tipopp,
  prd.id                                       AS productos,
  pry.id                                       AS proyectos,
  taa.id                                       AS tipoaao,
  obr.id                                       AS obra,
  act.id                                       AS actividad,
  acc.id                                       AS accion,
  gf.id                                        AS grufun,
  XD.meta,
  fin.id                                       AS finalidad,
  rg.id                                        AS recurso,
  cg.id                                        AS catgas,
  ed.id                                        AS especifica,

  XD.pia,
  XD.pim,
  XD.certificado,
  XD.compromiso_anual,
  XD.compromiso_mensual,
  XD.devengado,
  XD.girado
FROM pres_impor_gastos XD
JOIN pres_base_gastos bg
  ON bg.importacion_id = XD.importacion_id
 AND bg.anio = XD.anio
 AND bg.mes = XD.mes

/* Unidad ejecutora por cadena niv_gob/sector/pliego + sec_ejec/cod_ue */
JOIN pres_unidadejecutora ue
  ON ue.secuencia_ejecutora = XD.sec_ejec
 AND ue.codigo_ue          = XD.cod_ue
JOIN pres_pliego pl
  ON pl.id = ue.pliego_id
 AND pl.codigo             = XD.cod_pliego
JOIN pres_sector se
  ON se.id = pl.sector_id
 AND se.codigo             = XD.cod_sector
JOIN pres_tipo_gobierno tg
  ON tg.id = se.tipogobierno_id
 AND tg.codigo             = XD.cod_niv_gob

/* Ubigeo y demás dimensiones (LEFT JOIN si pueden faltar) */
LEFT JOIN par_ubigeo ub
  ON ub.codigo             = XD.cod_ubigeo

LEFT JOIN pres_meta mt
  ON mt.anio               = XD.anio
 AND mt.sec_fun            = XD.sec_func

LEFT JOIN pres_finalidad fin
  ON fin.codigo            = XD.cod_fina

LEFT JOIN pres_categoriapresupuestal cp
  ON cp.codigo             = XD.cod_cat_pres

LEFT JOIN pres_producto_proyecto tpp
  ON tpp.codigo            = XD.tipo_prod_proy

LEFT JOIN pres_productos prd
  ON prd.codigo            = XD.cod_prod_proy

LEFT JOIN pres_proyectos pry
  ON pry.codigo            = XD.cod_prod_proy

LEFT JOIN pres_act_acc_obr taa
  ON taa.codigo            = XD.tipo_act_acc_obra

LEFT JOIN pres_obra obr
  ON obr.codigo            = XD.cod_act_acc_obra
LEFT JOIN pres_actividades act
  ON act.codigo            = XD.cod_act_acc_obra
LEFT JOIN pres_accion acc
  ON acc.codigo            = XD.cod_act_acc_obra

/* función/división/grupo funcional */
LEFT JOIN pres_grupofuncional gf
  ON gf.codigo             = XD.cod_gru_fun
LEFT JOIN pres_divisionfuncional df
  ON df.id                 = gf.divisionfuncional_id
 AND df.codigo             = XD.cod_div_fun
LEFT JOIN pres_funcion fu
  ON fu.id                 = df.funcion_id
 AND fu.codigo             = XD.cod_fun

/* recurso: tipo recurso + rubro + fuente */
LEFT JOIN pres_recursos_gastos rg
  ON rg.codigo             = XD.cod_tipo_rec
LEFT JOIN pres_rubro ru
  ON ru.id                 = rg.rubro_id
 AND ru.codigo             = XD.cod_rub
LEFT JOIN pres_fuentefinanciamiento ff
  ON ff.id                 = ru.fuentefinanciamiento_id
 AND ff.codigo             = XD.cod_fue_fin

LEFT JOIN pres_categoriagasto cg
  ON cg.codigo             = XD.cod_cat_gas

/* clasificadores de gasto (árbol) */
LEFT JOIN pres_especificadetalle_gastos ed
  ON ed.codigo             = XD.cod_esp_det
LEFT JOIN pres_especifica_gastos eg
  ON eg.id                 = ed.especifica_id
 AND eg.codigo             = XD.cod_esp
LEFT JOIN pres_subgenericadetalle_gastos sgd
  ON sgd.id                = eg.subgenericadetalle_id
 AND sgd.codigo            = XD.cod_subgen_det
LEFT JOIN pres_subgenerica_gastos sg
  ON sg.id                 = sgd.subgenerica_id
 AND sg.codigo             = XD.cod_subgen
LEFT JOIN pres_generica_gastos ge
  ON ge.id                 = sg.generica_id
 AND ge.codigo             = XD.cod_gen

WHERE XD.importacion_id = 3232;




