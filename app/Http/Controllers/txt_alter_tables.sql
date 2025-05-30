ALTER TABLE par_indicador_general ADD numerador INT NULL DEFAULT NULL AFTER descripcion;
ALTER TABLE par_indicador_general ADD denominador INT NULL DEFAULT NULL AFTER numerador;

ALTER TABLE par_indicador_general ADD numerador TEXT NULL DEFAULT NULL AFTER descripcion, ADD denominador TEXT NULL DEFAULT NULL AFTER numerador;

ALTER TABLE par_indicador_general CHANGE codigo codigo VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;

ALTER TABLE par_indicador_general_meta ADD distrito INT NULL DEFAULT NULL AFTER periodo;
ALTER TABLE par_indicador_general_meta ADD anio_base INT(4) NOT NULL AFTER distrito, ADD valor_base VARCHAR(15)  NOT NULL AFTER anio_base;

ALTER TABLE par_indicador_general CHANGE numerador numerador TEXT NULL DEFAULT NULL, CHANGE denominador denominador TEXT NULL DEFAULT NULL;


# inicio procedimiento almacenado

CREATE DEFINER=root@localhost PROCEDURE datay.edu_pa_procesarImporMatriculaGeneral(v_importacion_id INT, v_matriculageneral_id INT)
BEGIN

	UPDATE edu_impor_matricula_general SET
		id_anio=ltrim(rtrim(ifnull(id_anio,''))),
		cod_mod=ltrim(rtrim(ifnull(cod_mod,''))),
		modalidad=ltrim(rtrim(ifnull(modalidad,''))),
		id_nivel=ltrim(rtrim(ifnull(id_nivel,''))),
		id_gestion=ltrim(rtrim(ifnull(id_gestion,''))),
		pais_nacimiento=ltrim(rtrim(ifnull(pais_nacimiento,''))),
		fecha_nacimiento=ltrim(rtrim(ifnull(fecha_nacimiento,''))),
		sexo=ltrim(rtrim(ifnull(sexo,''))),
		lengua_materna=ltrim(rtrim(ifnull(lengua_materna,''))),
		segunda_lengua=ltrim(rtrim(ifnull(segunda_lengua,''))),
		di_leve=ltrim(rtrim(ifnull(di_leve,''))),
		di_moderada=ltrim(rtrim(ifnull(di_moderada,''))),
		di_severo=ltrim(rtrim(ifnull(di_severo,''))),
		discapacidad_fisica=ltrim(rtrim(ifnull(discapacidad_fisica,''))),
		trastorno_espectro_autista=ltrim(rtrim(ifnull(trastorno_espectro_autista,''))),
		dv_baja_vision=ltrim(rtrim(ifnull(dv_baja_vision,''))),
		dv_ceguera=ltrim(rtrim(ifnull(dv_ceguera,''))),
		da_hipoacusia=ltrim(rtrim(ifnull(da_hipoacusia,''))),
		da_sordera=ltrim(rtrim(ifnull(da_sordera,''))),
		sordoceguera=ltrim(rtrim(ifnull(sordoceguera,''))),
		otra_discapacidad=ltrim(rtrim(ifnull(otra_discapacidad,''))),
		situacion_matricula=ltrim(rtrim(ifnull(situacion_matricula,''))),
		estado_matricula=ltrim(rtrim(ifnull(estado_matricula,''))),
		fecha_matricula=ltrim(rtrim(ifnull(fecha_matricula,''))),
		id_grado=ltrim(rtrim(ifnull(id_grado,''))),
		dsc_grado=ltrim(rtrim(ifnull(dsc_grado,''))),
		id_seccion=ltrim(rtrim(ifnull(id_seccion,''))),
		dsc_seccion=ltrim(rtrim(ifnull(dsc_seccion,''))),
		fecha_registro=ltrim(rtrim(ifnull(fecha_registro,''))),
		fecha_retiro=ltrim(rtrim(ifnull(fecha_retiro,''))),
		motivo_retiro=ltrim(rtrim(ifnull(motivo_retiro,''))),
		sf_regular=ltrim(rtrim(ifnull(sf_regular,''))),
		sf_promocion_guiada=ltrim(rtrim(ifnull(sf_promocion_guiada,'')))
	WHERE importacion_id=v_importacion_id;



    BEGIN
		INSERT INTO edu_matricula_general_detalle(
		matriculageneral_id,	institucioneducativa_id, 	cod_mod, 				modalidad, 					nivel,
		gestion, 				pais_nacimiento, 			fecha_nacimiento, 		edad, 						sexo,
        lengua_materna, 		segunda_lengua, 			situacion_matricula, 	estado_matricula, 			fecha_matricula,
        id_grado, 				dsc_grado, 					id_seccion, 			dsc_seccion, 				fecha_registro,
        fecha_retiro, 			motivo_retiro, 				sf_regular, 			sf_promocion_guiada,		di_leve,
        di_moderada, 			di_severo, 					discapacidad_fisica, 	trastorno_espectro_autista, dv_baja_vision,
        dv_ceguera, 			da_hipoacusia, 				da_sordera, 			sordoceguera, 				otra_discapacidad
        )
		SELECT
			v_matriculageneral_id,
			(select id from edu_institucioneducativa  where codModular=imp.cod_mod),
			imp.cod_mod,
            imp.modalidad,
            (select id from edu_nivelmodalidad where codigo=imp.id_nivel ),

            (select id from edu_tipogestion where codigo=imp.id_gestion ),
			imp.pais_nacimiento,
			if(imp.fecha_nacimiento='NULL' or imp.fecha_nacimiento='',null,str_to_date(imp.fecha_nacimiento,'%Y-%m-%d')),
            YEAR(CURDATE())-YEAR(str_to_date(fecha_nacimiento,'%Y-%m-%d')) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(str_to_date(fecha_nacimiento,'%Y-%m-%d'),'%m-%d'), 0 , -1 ),
			imp.sexo,

			imp.lengua_materna,
			imp.segunda_lengua,
			imp.situacion_matricula,
			imp.estado_matricula,
			if(imp.fecha_matricula='NULL' or imp.fecha_matricula='',null,str_to_date(imp.fecha_matricula,'%Y-%m-%d')),

			imp.id_grado,
			imp.dsc_grado,
			imp.id_seccion,
			imp.dsc_seccion,
			if(imp.fecha_registro='NULL' or imp.fecha_registro='',null,str_to_date(imp.fecha_registro,'%Y-%m-%d')),

			if(imp.fecha_retiro='NULL' or imp.fecha_retiro='',null,str_to_date(imp.fecha_retiro,'%Y-%m-%d')),
			imp.motivo_retiro,
			imp.sf_regular,
			imp.sf_promocion_guiada,
			if(imp.di_leve='NULL' or di_leve='',null,di_leve),

			if(imp.di_moderada='NULL' or di_moderada='',null,di_moderada),
			if(imp.di_severo='NULL' or di_severo='',null,di_severo),
			if(imp.discapacidad_fisica='NULL' or discapacidad_fisica='',null,discapacidad_fisica),
			if(imp.trastorno_espectro_autista='NULL' or trastorno_espectro_autista='',null,trastorno_espectro_autista),
			if(imp.dv_baja_vision='NULL' or dv_baja_vision='',null,dv_baja_vision),

			if(imp.dv_ceguera='NULL' or dv_ceguera='',null,dv_ceguera),
			if(imp.da_hipoacusia='NULL' or da_hipoacusia='',null,da_hipoacusia),
			if(imp.da_sordera='NULL' or da_sordera='',null,da_sordera),
			if(imp.sordoceguera='NULL' or sordoceguera='',null,sordoceguera),
			if(imp.otra_discapacidad='NULL' or otra_discapacidad='',null,otra_discapacidad)

		FROM edu_impor_matricula_general as imp WHERE imp.importacion_id=v_importacion_id;

		update par_importacion  set estado = 'PR' where id = v_importacion_id;

		truncate edu_impor_matricula_general;


    END;


END
# fin


# query para crear tabla padron actas

CREATE TABLE sal_impor_padron_actas (
  id int(11) NOT NULL,
  importacion_id int(11) DEFAULT NULL,
   nombre_municipio	varchar(40) DEFAULT NULL,
			departamento	varchar(10) DEFAULT NULL,
			provincia	varchar(30) DEFAULT NULL,
			distrito	varchar(50) DEFAULT NULL,
			fecha_inicial	varchar(10) DEFAULT NULL,
			fecha_final	varchar(10) DEFAULT NULL,
			fecha_envio	varchar(10) DEFAULT NULL,
			dni_usuario_envio	varchar(8) DEFAULT NULL,
			primer_apellido	varchar(20) DEFAULT NULL,
			segundo_apellido	varchar(20) DEFAULT NULL,
			prenombres	varchar(30) DEFAULT NULL,
			numero_archivos	int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE sal_impor_padron_actas ADD PRIMARY KEY(id);
ALTER TABLE sal_impor_padron_actas CHANGE id id INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE sal_impor_padron_actas ADD INDEX(importacion_id);

ALTER TABLE sal_impor_padron_actas CHANGE dni_usuario_envio dni_usuario_envio VARCHAR(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;


########################################################

#query para modifcar table fuenteimportacion
########################################################


#

CREATE TABLE sal_data_pacto1 (
  id int(11) NOT NULL,
  importacion_id int(11) DEFAULT NULL,
  anio int(11) DEFAULT NULL,
  distrito varchar(50) DEFAULT NULL,
  mes int(11) DEFAULT NULL,
  estado int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE sal_data_pacto1 ADD PRIMARY KEY(id);
ALTER TABLE sal_data_pacto1 CHANGE id id INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE sal_data_pacto1 ADD INDEX(importacion_id);

#
CREATE PROCEDURE bdsismore.sal_pa_procesarPacto1(v_importacion int,v_anio int)
begin

/*
	delete from sal_data_pacto1 where anio=v_anio;
	drop table if exists temp1;
	create temporary table temp1(importacion int, anio int,distrito varchar(50),fechai date,fechaf date,fechae date,estado int);

	insert into temp1
	select imp.id, year(imp.fechaActualizacion),pa.distrito ,pa.fecha_inicial ,pa.fecha_final, pa.fecha_envio ,
		if(pa.fecha_envio between pa.fecha_inicial and date_format(date_add(pa.fecha_inicial,interval 1 month),'%Y-%m-07') ,1,0) as estado
	from sal_impor_padron_actas pa
	left join par_importacion imp on imp.id =pa.importacion_id
	where year(imp.fechaActualizacion)=v_anio and imp.id=v_importacion and year(pa.fecha_final)=year(imp.fechaActualizacion)
	order by pa.distrito,pa.fecha_inicial ;

	insert into sal_data_pacto1(importacion_id, anio, distrito, mes, estado)
	select importacion, anio, distrito, month(fechai) mes, max(estado) from temp1 group by importacion,anio,distrito,mes;

	update par_importacion set estado="PR" where id=v_importacion;
*/

	delete from sal_data_pacto1 where anio=v_anio;

	drop table if exists temp1;
	drop table if exists temp2;
	create temporary table temp1(importacion int, anio int,distrito varchar(50),fechai date,fechaf date,fechae date,estado int);
	create temporary table temp2(importacion int, anio int,distrito varchar(50),fechai date,fechaf date,fechae date,estado int);

	insert into temp1
	select imp.id, year(imp.fechaActualizacion),pa.distrito ,pa.fecha_inicial ,pa.fecha_final, pa.fecha_envio ,
		if(pa.fecha_envio between pa.fecha_inicial and date_format(date_add(pa.fecha_inicial,interval 1 month),'%Y-%m-07') ,1,0) as estado
	from sal_impor_padron_actas pa
	left join par_importacion imp on imp.id =pa.importacion_id
	where year(imp.fechaActualizacion)=v_anio and imp.id=v_importacion and (year(pa.fecha_inicial)=year(imp.fechaActualizacion) and year(pa.fecha_final)=year(imp.fechaActualizacion))
	order by pa.distrito,pa.fecha_inicial ;

	insert into temp2
	select importacion, anio, distrito , fechai , fechaf, fechae ,
	IF(estado=0,
		IF(month(fechaf)-month(fechai) in(1,2,3) and month(fechaf)=month(fechae),
			IF(fechae between fechaf and date_format(date_add(fechaf,interval 1 month),'%Y-%m-07') ,1,0)
		,0)
	,1)
	 as estado
	from temp1;

	insert into sal_data_pacto1(importacion_id, anio, distrito, mes, estado)
	select importacion, anio, distrito, month(if(year(fechai)=anio,fechai,if(year(fechaf)=anio,fechaf,fechae))) mes, max(estado) from temp2 group by importacion, anio, distrito, mes;

	update par_importacion set estado="PR" where id=v_importacion;

end

#
ALTER TABLE par_indicador_general CHANGE update_at update_at TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;


#

UPDATE par_ubigeo SET nombre = 'RAIMONDI' WHERE par_ubigeo.id = 45;

#
ALTER TABLE sal_impor_padron_actas ADD FOREIGN KEY (importacion_id) REFERENCES par_importacion(id) ON DELETE RESTRICT ON UPDATE RESTRICT;

#creacion de menu importar padron actas

#

CREATE DEFINER=root@localhost PROCEDURE bdsismore.sal_pa_procesarPacto1x(IN v_importacion INT, IN v_anio INT)
begin
	drop table if exists temp1;
	drop table if exists temp2;
	create temporary table temp1(importacion int, anio int,distrito varchar(50),fechai date,fechaf date,fechae date,estado int);
	create temporary table temp2(importacion int, anio int,distrito varchar(50),fechai date,fechaf date,fechae date,estado int);

	select count(*) 'numero de envios' from sal_impor_padron_actas where importacion_id =v_importacion;
	select distinct year(fecha_inicial),year(fecha_final),year(fecha_envio) 'fechas' from sal_impor_padron_actas where importacion_id =v_importacion;

	insert into temp1
	select imp.id, year(imp.fechaActualizacion),pa.distrito ,pa.fecha_inicial ,pa.fecha_final, pa.fecha_envio ,
		if(pa.fecha_envio between pa.fecha_inicial and date_format(date_add(pa.fecha_inicial,interval 1 month),'%Y-%m-07') ,1,0) as estado
	from sal_impor_padron_actas pa
	left join par_importacion imp on imp.id =pa.importacion_id
	where year(imp.fechaActualizacion)=v_anio and imp.id=v_importacion
	order by pa.distrito,pa.fecha_inicial ;

	select * from temp1;

	insert into temp2
	select importacion, anio, distrito , fechai , fechaf, fechae ,
	IF(estado=0,
		IF(month(fechaf)-month(fechai) in(1,2) and month(fechaf)=month(fechae),
			IF(fechae between fechaf and date_format(date_add(fechaf,interval 1 month),'%Y-%m-07') ,1,0)
		,0)
	,1)
	 as estado
	from temp1;

	select * from temp2;

end

#############
# EDUCACION #
#############

#
ALTER TABLE edu_impor_matricula_general
DROP di_severo, DROP discapacidad_fisica,
DROP trastorno_espectro_autista,
DROP dv_baja_vision,
DROP dv_ceguera,
DROP da_hipoacusia,
DROP da_sordera,
DROP sordoceguera,
DROP otra_discapacidad;


#
ALTER TABLE edu_impor_matricula_general
CHANGE di_leve id_discapacidad INT(11) NULL DEFAULT NULL,
CHANGE di_moderada discapacidad VARCHAR(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;

#
ALTER TABLE edu_impor_matricula_general
CHANGE modalidad id_mod INT NULL DEFAULT NULL,
CHANGE sexo id_sexo INT NULL DEFAULT NULL,
CHANGE dsc_grado grado VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
CHANGE dsc_seccion seccion VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
CHANGE sf_promocion_guiada sf_recuperacion VARCHAR(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;

#
ALTER TABLE edu_impor_matricula_general CHANGE id_discapacidad id_discapacidad VARCHAR(5) NULL DEFAULT NULL;


#
ALTER TABLE edu_matricula_general_detalle DROP di_severo, DROP discapacidad_fisica, DROP trastorno_espectro_autista, DROP dv_baja_vision, DROP dv_ceguera, DROP da_hipoacusia, DROP da_sordera, DROP sordoceguera, DROP otra_discapacidad;

#
CREATE DEFINER=root@localhost PROCEDURE bdsismore.edu_pa_procesarImporMatriculaGeneral(v_importacion_id INT, v_matricula_id INT,v_fecha_actualizacion varchar(10))
begin

	DROP TABLE IF EXISTS tmp1;
	CREATE TEMPORARY TABLE tmp1 (id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,codigo varchar(10) DEFAULT NULL,nombre varchar(100) DEFAULT NULL);

	UPDATE edu_impor_matricula_general SET
		id_anio=ltrim(rtrim(ifnull(id_anio,''))),
		cod_mod=ltrim(rtrim(ifnull(cod_mod,''))),
		id_mod=ltrim(rtrim(ifnull(id_mod,''))),
		id_nivel=ltrim(rtrim(ifnull(id_nivel,''))),
		id_gestion=ltrim(rtrim(ifnull(id_gestion,''))),
		id_sexo=ltrim(rtrim(ifnull(id_sexo,''))),
		fecha_nacimiento=ltrim(rtrim(ifnull(fecha_nacimiento,''))),
		pais_nacimiento=ltrim(rtrim(ifnull(pais_nacimiento,''))),
		lengua_materna=ltrim(rtrim(ifnull(lengua_materna,''))),
		segunda_lengua=ltrim(rtrim(ifnull(segunda_lengua,''))),
		id_discapacidad=ltrim(rtrim(ifnull(id_discapacidad,''))),
		discapacidad=ltrim(rtrim(ifnull(discapacidad,''))),
		situacion_matricula=ltrim(rtrim(ifnull(situacion_matricula,''))),
		estado_matricula=ltrim(rtrim(ifnull(estado_matricula,''))),
		fecha_matricula=ltrim(rtrim(ifnull(fecha_matricula,''))),
		id_grado=ltrim(rtrim(ifnull(id_grado,''))),
		grado=ltrim(rtrim(ifnull(grado,''))),
		id_seccion=ltrim(rtrim(ifnull(id_seccion,''))),
		seccion=ltrim(rtrim(ifnull(seccion,''))),
		fecha_registro=ltrim(rtrim(ifnull(fecha_registro,''))),
		fecha_retiro=if(fecha_retiro='NULL','',ltrim(rtrim(ifnull(fecha_retiro,'')))),
		motivo_retiro=ltrim(rtrim(ifnull(motivo_retiro,''))),
		sf_regular=ltrim(rtrim(ifnull(sf_regular,''))),
		sf_recuperacion=ltrim(rtrim(ifnull(sf_recuperacion,'')))
	WHERE importacion_id=v_importacion_id;


	insert into tmp1 (codigo) select codigo from edu_discapacidad;
	insert into edu_discapacidad(codigo,nombre,estado)
    select distinct id_discapacidad,discapacidad,"AC" from edu_impor_matricula_general where discapacidad!="" and id_discapacidad not in (select codigo from tmp1) order by discapacidad;
	/*truncate tmp1;*/


	drop table tmp1;

    begin

		INSERT INTO edu_matricula_general_detalle(
		matriculageneral_id, 	institucioneducativa_id, 	cod_mod, 			modalidad_id, 			nivel_codigo,
		gestion_id, 			pais_nacimiento, 			fecha_nacimiento, 	edad, 					sexo_id,
		lengua_materna, 		segunda_lengua, 			discapacidad_id, 	situacion_matricula, 	estado_matricula,
		fecha_matricula, 		grado_id, 					grado, 				seccion_id, 			seccion,
		fecha_registro, 		fecha_retiro, 				motivo_retiro, 		sf_regular, 			sf_recuperacion
		)
		SELECT
			v_matricula_id,
			(select id from edu_institucioneducativa  where codModular=imp.cod_mod) as iiee,
			/*imp.id_anio,*/
			imp.cod_mod,
			imp.id_mod,
			imp.id_nivel,

			imp.id_gestion,
			imp.pais_nacimiento,
			/*imp.fecha_nacimiento, */
			if(imp.fecha_nacimiento='NULL' or imp.fecha_nacimiento='',null,str_to_date(imp.fecha_nacimiento,'%Y-%m-%d')) as fecha_nacimiento,
			if(imp.fecha_nacimiento='NULL' or imp.fecha_nacimiento='',null,
	            YEAR(v_fecha_actualizacion)-YEAR(str_to_date(imp.fecha_nacimiento,'%Y-%m-%d')) +
	            IF(DATE_FORMAT(v_fecha_actualizacion,'%m-%d') > DATE_FORMAT(str_to_date(imp.fecha_nacimiento,'%Y-%m-%d'),'%m-%d'), 0 , -1 )
			) as edad,
			imp.id_sexo,

			imp.lengua_materna,
			imp.segunda_lengua,
			if(imp.id_discapacidad="NULL" or imp.id_discapacidad="",null,(select id from edu_discapacidad where codigo=imp.id_discapacidad)) as discapacidad,
			imp.situacion_matricula,
			imp.estado_matricula,

			if(imp.fecha_matricula='NULL' or imp.fecha_matricula='',null,str_to_date(imp.fecha_matricula,'%Y-%m-%d')) as fecha_matricula,
			/*imp.fecha_matricula, */
			imp.id_grado,
			imp.grado,
			imp.id_seccion,
			imp.seccion,

			if(imp.fecha_registro='NULL' or imp.fecha_registro='',null,str_to_date(imp.fecha_registro,'%Y-%m-%d')) as fecha_registro,
			if(imp.fecha_retiro='NULL' or imp.fecha_retiro='',null,str_to_date(imp.fecha_retiro,'%Y-%m-%d')) as fecha_retiro,
			/*imp.fecha_registro, */
			/*imp.fecha_retiro, */
			imp.motivo_retiro,
			imp.sf_regular,
			imp.sf_recuperacion
		FROM edu_impor_matricula_general as imp WHERE imp.importacion_id=v_importacion_id;

		update par_importacion  set estado = 'PR' where id = v_importacion_id;

		/*truncate edu_impor_matricula_general;*/


    END;

   	/*truncate tmp1;*/
	/*delete from tmp1;*/
	/*drop table tmp1;*/
END

#
ALTER TABLE edu_matricula_general_detalle CHANGE modalidad modalidad_id INT(1) NULL DEFAULT NULL;

#
ALTER TABLE edu_matricula_general_detalle CHANGE dsc_grado grado VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL, CHANGE dsc_seccion seccion VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL, CHANGE sf_promocion_guiada sf_recuperacion VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;

#
ALTER TABLE edu_matricula_general_detalle CHANGE nivel nivel_codigo INT(11) NULL DEFAULT NULL, CHANGE gestion gestion_id INT(11) NULL DEFAULT NULL, CHANGE sexo sexo_id INT(11) NULL DEFAULT NULL;

#
ALTER TABLE edu_matricula_general_detalle ADD discapacidad_id INT NULL DEFAULT NULL AFTER segunda_lengua;

#
ALTER TABLE edu_matricula_general_detalle CHANGE id_grado grado_id INT(11) NULL DEFAULT NULL, CHANGE id_seccion seccion_id INT(11) NULL DEFAULT NULL;

#
ALTER TABLE edu_matricula_general_detalle CHANGE nivel_codigo nivel_codigo VARCHAR(3) NULL DEFAULT NULL;

##################
#   padron web   #
##################

CREATE DEFINER=root@localhost PROCEDURE datosy.edu_pa_procesarPadronWeb(IN v_importacion_id INT, IN v_usuario_id INT)
BEGIN

	declare recorre, existe, v_id int;
    declare v_codigo, v_codMod varchar(20);
    declare v_nombre varchar(200);
    drop table if exists tabla_tempo,forma_tempo,tipogestiondet_tempo,
    centropoblado_tempo,padronweb_tempo_completo;

    create temporary table tabla_tempo( id  INT AUTO_INCREMENT PRIMARY KEY, codigo varchar(20),nombre varchar(200),id_deTabla_enTempo int);
    create temporary table forma_tempo( id  INT NOT NULL AUTO_INCREMENT PRIMARY KEY,nombre varchar(200), id_deTabla_enTempo int);
    create temporary table tipogestiondet_tempo( id  INT NOT NULL AUTO_INCREMENT PRIMARY KEY,  codigoCab varchar(20),codigo varchar(20),nombre varchar(200), id_deTabla_enTempo int);
    create temporary table centropoblado_tempo (id  INT NOT NULL AUTO_INCREMENT PRIMARY KEY, codUbigeo varchar(20),codINEI varchar(20),codUEMinedu varchar(20), nombre varchar(200), id_deTabla_enTempo int);
    create temporary table padronweb_tempo_completo ( nivelmodalidad_id int,forma_id int,caracteristica_id int,genero_id int,tipogestion_id int,
    ugel_id int, area_id int,estadoinsedu_id int, turno_id int, centropoblado_id int, institucion_id int,cod_Mod varchar(100),cod_Local varchar(100),
    cen_Edu varchar(200),director varchar(200), telefono varchar(45),/*email varchar(100),*/dir_Cen varchar(200),nLat_IE decimal(15,4),
    nLong_IE decimal(15,4),/*fechaReg timestamp null,fecha_Act timestamp null,*/created_at timestamp null,updated_at timestamp null, estado char(2));

   /***
    * modalidad
    * email
    * localidad
    * fechaReg
    * fecha_Act
    */

    update edu_impor_padronweb set
    cod_Mod= ltrim(rtrim( ifnull(cod_Mod,''))),
	cod_Local= ltrim(rtrim( ifnull(cod_Local,''))),
	cen_Edu= ltrim(rtrim( ifnull(cen_Edu,''))),
	niv_Mod= ltrim(rtrim( ifnull(niv_Mod,''))),
	d_Niv_Mod= ltrim(rtrim( ifnull(d_Niv_Mod,''))),
	d_Forma= ltrim(rtrim( ifnull(d_Forma,''))),
	cod_Car= ltrim(rtrim( ifnull(cod_Car,''))),
	d_Cod_Car= ltrim(rtrim( ifnull(d_Cod_Car,''))),
	TipsSexo= ltrim(rtrim( ifnull(TipsSexo,''))),
	d_TipsSexo= ltrim(rtrim( ifnull(d_TipsSexo,''))),
	gestion= ltrim(rtrim( ifnull(gestion,''))),
	d_Gestion= ltrim(rtrim( ifnull(d_Gestion,''))),
	ges_Dep= ltrim(rtrim( ifnull(ges_Dep,''))),
	d_Ges_Dep= ltrim(rtrim( ifnull(d_Ges_Dep,''))),
	director= ltrim(rtrim( ifnull(director,''))),
	telefono= ltrim(rtrim( ifnull(telefono,''))),
	dir_Cen= ltrim(rtrim( ifnull(dir_Cen,''))),
	codcp_Inei= ltrim(rtrim( ifnull(codcp_Inei,''))),
	codccpp= ltrim(rtrim( ifnull(codccpp,''))),
	cen_Pob= ltrim(rtrim( ifnull(cen_Pob,''))),
	area_Censo= ltrim(rtrim( ifnull(area_Censo,''))),
	d_areaCenso= ltrim(rtrim( ifnull(d_areaCenso,''))),
	codGeo= ltrim(rtrim( ifnull(codGeo,''))),
	d_Prov= ltrim(rtrim( ifnull(d_Prov,''))),
	d_Dist= ltrim(rtrim( ifnull(d_Dist,''))),
	region= ltrim(rtrim( ifnull(region,''))),
	codOOII= ltrim(rtrim( ifnull(codOOII,''))),
	d_DreUgel= ltrim(rtrim( ifnull(d_DreUgel,''))),
	nLat_IE= ltrim(rtrim( ifnull(nLat_IE,''))),
	nLong_IE= ltrim(rtrim( ifnull(nLong_IE,''))),
	cod_Tur= ltrim(rtrim( ifnull(cod_Tur,''))),
	D_Cod_Tur= ltrim(rtrim( ifnull(D_Cod_Tur,''))),
	estado= ltrim(rtrim( ifnull(estado,''))),
	d_Estado= ltrim(rtrim( ifnull(d_Estado,''))),
	tAlum_Hom= ltrim(rtrim( ifnull(tAlum_Hom,''))),
	tAlum_Muj= ltrim(rtrim( ifnull(tAlum_Muj,''))),
	tAlumno= ltrim(rtrim( ifnull(tAlumno,''))),
	tDocente= ltrim(rtrim( ifnull(tDocente,''))),
	tSeccion= ltrim(rtrim( ifnull(tSeccion,'')))
	where  importacion_id = v_importacion_id;


    begin
		DELETE FROM tabla_tempo where id > 0;

		insert tabla_tempo (codigo,nombre,id_deTabla_enTempo)
		select pw.codigo,pw.nombre,tab.id from
		(
			select distinct niv_Mod as codigo,d_Niv_Mod as nombre from edu_impor_padronweb
			where importacion_id=v_importacion_id
		) as pw
		left join edu_nivelmodalidad as tab on pw.codigo = tab.codigo;

		update edu_nivelmodalidad as tab
		left join tabla_tempo as tt on tab.id = tt.id_deTabla_enTempo
		set estado = 'EL'
		where id_deTabla_enTempo is null;

		update edu_nivelmodalidad as tab
		inner join tabla_tempo as tt on tab.id = tt.id_deTabla_enTempo
		set
		tab.nombre = tt.nombre,
		tab.updated_at= now(),
		tab.estado = 'AC';

		insert into edu_nivelmodalidad (codigo,nombre,created_at,estado)
		select codigo,nombre,now(),'AC' FROM tabla_tempo
		where id_deTabla_enTempo is null;
    end ;



	begin
        insert forma_tempo (nombre,id_deTabla_enTempo)
		select pw.nombre,tab.id from
		(
			select distinct d_forma as nombre from edu_impor_padronweb
			where importacion_id=v_importacion_id
		) as pw
		left join edu_forma as tab on pw.nombre = tab.nombre;

        update edu_forma as tab
		left join forma_tempo as tt on tab.id = tt.id_deTabla_enTempo
		set estado = 'EL', updated_at=now()
		where id_deTabla_enTempo is null;

        update edu_forma as tab
		inner join forma_tempo as tt on tab.id = tt.id_deTabla_enTempo
		set
		tab.updated_at= now(),
		tab.estado = 'AC';

        insert into edu_forma (nombre,created_at,estado)
		select nombre,now(),'AC' FROM forma_tempo
		where id_deTabla_enTempo is null;
    end;



    begin
		delete from tabla_tempo where id > 0;

		insert tabla_tempo (codigo,nombre,id_deTabla_enTempo)
		select pw.codigo,pw.nombre,tab.id from
		(
			select distinct cod_Car as codigo,d_Cod_Car as nombre from edu_impor_padronweb
			where importacion_id=v_importacion_id
		) as pw
		left join edu_caracteristica as tab on pw.codigo = tab.codigo;

		update edu_caracteristica as tab
		left join tabla_tempo as tt on tab.id = tt.id_deTabla_enTempo
		set estado = 'EL'
		where id_deTabla_enTempo is null;

		update edu_caracteristica as tab
		inner join tabla_tempo as tt on tab.id = tt.id_deTabla_enTempo
		set
		tab.nombre = tt.nombre,
		tab.updated_at= now(),
		tab.estado = 'AC';

		insert into edu_caracteristica (codigo,nombre,created_at,estado)
		select codigo,nombre,now(),'AC' FROM tabla_tempo
		where id_deTabla_enTempo is null;
    end;



    begin
		delete from tabla_tempo where id > 0;

		insert tabla_tempo (codigo,nombre,id_deTabla_enTempo)
		select pw.codigo,pw.nombre,tab.id from
		(
			select distinct TipsSexo as codigo,d_TipsSexo as nombre from edu_impor_padronweb
			where importacion_id=v_importacion_id
		) as pw
		left join edu_genero as tab on pw.codigo = tab.codigo;

		update edu_genero as tab
		left join tabla_tempo as tt on tab.id = tt.id_deTabla_enTempo
		set estado = 'EL'
		where id_deTabla_enTempo is null;

		update edu_genero as tab
		inner join tabla_tempo as tt on tab.id = tt.id_deTabla_enTempo
		set
		tab.nombre = tt.nombre,
		tab.updated_at= now(),
		tab.estado = 'AC';

		insert into edu_genero (codigo,nombre,created_at,estado)
		select codigo,nombre,now(),'AC' FROM tabla_tempo
		where id_deTabla_enTempo is null;
    end;



	begin
		delete from tabla_tempo where id > 0;

		insert tabla_tempo (codigo,nombre,id_deTabla_enTempo)
		select pw.codigo,pw.nombre,tab.id from
		(
			select distinct gestion as codigo,d_Gestion as nombre from edu_impor_padronweb
			where importacion_id=v_importacion_id
		) as pw
		left join edu_tipogestion as tab on pw.codigo = tab.codigo and dependencia is  null;

		update edu_tipogestion as tab
		left join tabla_tempo as tt on tab.id = tt.id_deTabla_enTempo
		set estado = 'EL'
		where id_deTabla_enTempo is null
        and dependencia is null;

		update edu_tipogestion as tab
		inner join tabla_tempo as tt on tab.id = tt.id_deTabla_enTempo
		set
		tab.nombre = tt.nombre,
		tab.updated_at= now(),
		tab.estado = 'AC';

		insert into edu_tipogestion (codigo,nombre,dependencia,created_at,estado)
		select codigo,nombre,null,now(),'AC' FROM tabla_tempo
		where id_deTabla_enTempo is null;
    end;



    begin

		insert into tipogestiondet_tempo(codigoCab,codigo,nombre,id_deTabla_enTempo)
        select pw.codigoCab,pw.codigo,pw.nombre,tab.id from
		(
			select distinct gestion as codigoCab ,ges_Dep  as codigo ,d_Ges_Dep as nombre from edu_impor_padronweb
			where importacion_id=v_importacion_id
		) as pw
		left join edu_tipogestion as tab on pw.codigo = tab.codigo and dependencia is not null;

        update edu_tipogestion as tab
		left join tipogestiondet_tempo as tt on tab.id = tt.id_deTabla_enTempo
		set estado = 'EL'
		where id_deTabla_enTempo is null
        and dependencia is not null;

        update edu_tipogestion as tab
		inner join tipogestiondet_tempo as tt on tab.id = tt.id_deTabla_enTempo
		set
		tab.codigo = tt.codigo,
		tab.nombre = tt.nombre,
		tab.updated_at= now(),
		tab.estado = 'AC';

		insert into edu_tipogestion (codigo,nombre,dependencia,created_at,estado)
		select a.codigo,a.nombre,b.id,now(),'AC' FROM tipogestiondet_tempo as a
		inner join edu_tipogestion b on a.codigoCab = b.codigo
		where id_deTabla_enTempo is null;
    end;



	begin
		delete from tabla_tempo where id > 0;

		insert tabla_tempo (codigo,nombre,id_deTabla_enTempo)
		select pw.codigo,pw.nombre,tab.id from
		(
			select distinct estado as codigo,d_Estado as nombre from edu_impor_padronweb
			where importacion_id=v_importacion_id
		) as pw
		left join edu_estadoinsedu as tab on pw.codigo = tab.codigo;

		update edu_estadoinsedu as tab
		left join tabla_tempo as tt on tab.id = tt.id_deTabla_enTempo
		set estado = 'EL'
		where id_deTabla_enTempo is null;

		update edu_estadoinsedu as tab
		inner join tabla_tempo as tt on tab.id = tt.id_deTabla_enTempo
		set
		tab.nombre = tt.nombre,
		tab.updated_at= now(),
		tab.estado = 'AC';

		insert into edu_estadoinsedu (codigo,nombre,created_at,estado)
		select codigo,nombre,now(),'AC' FROM tabla_tempo
		where id_deTabla_enTempo is null;
    end;



    begin
		delete from tabla_tempo where id > 0;

		insert tabla_tempo (codigo,nombre,id_deTabla_enTempo)
		select pw.codigo,pw.nombre,tab.id from
		(
			select distinct codOOII as codigo,d_DreUgel as nombre from edu_impor_padronweb
			where importacion_id=v_importacion_id
		) as pw
		left join edu_ugel as tab on pw.codigo = tab.codigo;

		update edu_ugel as tab
		left join tabla_tempo as tt on tab.id = tt.id_deTabla_enTempo
		set estado = 'EL'
		where id_deTabla_enTempo is null;

		update edu_ugel as tab
		inner join tabla_tempo as tt on tab.id = tt.id_deTabla_enTempo
		set
		tab.nombre = tt.nombre,
		tab.updated_at= now(),
		tab.estado = 'AC';

		insert into edu_ugel (codigo,nombre,created_at,estado)
		select codigo,nombre,now(),'AC' FROM tabla_tempo
		where id_deTabla_enTempo is null;
    end;



    begin
		delete from tabla_tempo where id > 0;

		insert tabla_tempo (codigo,nombre,id_deTabla_enTempo)
		select pw.codigo,pw.nombre,tab.id from
		(
			select distinct area_Censo as codigo,d_areaCenso as nombre from edu_impor_padronweb
			where importacion_id=v_importacion_id
		) as pw
		left join edu_area as tab on pw.codigo = tab.codigo;

		update edu_area as tab
		left join tabla_tempo as tt on tab.id = tt.id_deTabla_enTempo
		set estado = 'EL'
		where id_deTabla_enTempo is null;

		update edu_area as tab
		inner join tabla_tempo as tt on tab.id = tt.id_deTabla_enTempo
		set
		tab.nombre = tt.nombre,
		tab.updated_at= now(),
		tab.estado = 'AC';

		insert into edu_area (codigo,nombre,created_at,estado)
		select codigo,nombre,now(),'AC' FROM tabla_tempo
		where id_deTabla_enTempo is null;
    end;



   begin
    delete from tabla_tempo where id > 0;

    insert tabla_tempo (codigo,nombre,id_deTabla_enTempo)
	select pw.codigo,pw.nombre,tab.id from
	(
		select distinct cod_Tur as codigo,D_Cod_Tur as nombre from edu_impor_padronweb
		where importacion_id=v_importacion_id
	) as pw
	left join edu_turno as tab on pw.codigo = tab.codigo;

    update edu_turno as tab
    left join tabla_tempo as tt on tab.id = tt.id_deTabla_enTempo
    set estado = 'EL'
    where id_deTabla_enTempo is null;

    update edu_turno as tab
    inner join tabla_tempo as tt on tab.id = tt.id_deTabla_enTempo
    set
    tab.nombre = tt.nombre,
    tab.updated_at= now(),
    tab.estado = 'AC';

    insert into edu_turno (codigo,nombre,created_at,estado)
    select codigo,nombre,now(),'AC' FROM tabla_tempo
    where id_deTabla_enTempo is null;
    end;




    insert into centropoblado_tempo(codUbigeo,codINEI,codUEMinedu,nombre,id_deTabla_enTempo)
	select pw.codUbigeo,pw.codINEI,pw.codUEMinedu,pw.nombre,tab.id from
	(
		select distinct codGeo as codUbigeo,codcp_Inei as codINEI ,codccpp as codUEMinedu,cen_Pob as nombre from edu_impor_padronweb
		where importacion_id=v_importacion_id
	) as pw
	left join
	(
		select cen.id,ub.codigo,cen.nombre from edu_centropoblado cen
		inner join par_ubigeo ub on cen.Ubigeo_id = ub.id
	) as tab on pw.codUbigeo = tab.codigo and pw.nombre = tab.nombre;

	update edu_centropoblado as tab
    inner join centropoblado_tempo as tt on tab.id = tt.id_deTabla_enTempo
    set
    tab.codINEI = tt.codINEI,
    tab.codUEMinedu = tt.codUEMinedu,
    tab.updated_at= now(),
    tab.estado = 'AC';

	insert into edu_centropoblado (Ubigeo_id,codINEI,codUEMinedu,nombre,created_at,estado)

    select ubigeo.id,a.codINEI,a.codUEMinedu,a.nombre,now(),'AC' FROM centropoblado_tempo as a
    inner join par_ubigeo ubigeo on a.codUbigeo = ubigeo.codigo
    where id_deTabla_enTempo is null;




	INSERT padronweb_tempo_completo
	select
		(select id from edu_nivelmodalidad where codigo = pw.niv_Mod    limit 1) as nivelmodalidad_id,
	    (select id from edu_forma          where nombre = pw.d_forma    limit 1) as forma_id,
		(select id from edu_caracteristica where codigo = pw.cod_Car    limit 1) as caracteristica_id,
		(select id from edu_genero         where codigo = pw.TipsSexo   limit 1) as genero_id,
		(select id from edu_tipogestion    where codigo = pw.ges_Dep    limit 1) as tipogestion_id,
		(select id from edu_ugel           where codigo = pw.codOOII    limit 1) as ugel_id,
		(select id from edu_area           where codigo = pw.area_Censo limit 1) as area_id,
		(select id from edu_estadoinsedu   where codigo = pw.estado     limit 1) as estadoinsedu_id,
		(select id from edu_turno          where codigo = pw.cod_Tur    limit 1) as turno_id,

       	(select cen.id from edu_centropoblado  cen inner join par_ubigeo ub on cen.Ubigeo_id = ub.id where cen.nombre = pw.cen_Pob and ub.codigo = pw.codGeo limit 1) as centropoblado_id,
         inst.id as institucion_id,
         pw.cod_Mod,
         pw.cod_Local,
         pw.cen_Edu,
         pw.director,
         pw.telefono,
         /*pw.email,*/
         pw.dir_Cen,
         pw.nLat_IE,
         pw.nLong_IE,
         /*pw.fechaReg,*/
         /*pw.fecha_Act,*/
         now(),
         now(),
         'AC'
	from edu_impor_padronweb as pw
	left join edu_institucioneducativa as inst on pw.cod_Mod = inst.codModular
	where importacion_id=v_importacion_id;


    update edu_institucioneducativa as ins
    left join padronweb_tempo_completo as pw on ins.id = pw.institucion_id
    set ins.estado = 'EL'
    where institucion_id is null;


    update edu_institucioneducativa as inst
    inner join padronweb_tempo_completo as pw on inst.id = pw.institucion_id
    set
    inst.NivelModalidad_id = pw.nivelmodalidad_id,
    inst.Forma_id = pw.forma_id,
    inst.Caracteristica_id = pw.caracteristica_id,
    inst.Genero_id = pw.genero_id,
    inst.TipoGestion_id = pw.tipogestion_id,
    inst.Ugel_id = pw.ugel_id,
    inst.Area_id = pw.area_id,
    inst.EstadoInsEdu_id = pw.estadoinsedu_id,
    inst.Turno_id = pw.turno_id,
    inst.CentroPoblado_id = pw.centropoblado_id,
    inst.codLocal = pw.cod_Local,
    inst.nombreInstEduc = pw.cen_Edu,
    inst.nombreDirector = pw.director,
    inst.telefono = pw.telefono,
    /*inst.email = pw.email,*/
    inst.direccion = pw.dir_Cen,
    inst.coorGeoLatitud = pw.nLat_IE,
    inst.coordGeoLongitud = pw.nLong_IE,
    /*inst.fechaReg = pw.fechaReg,*/
    /*inst.fechaAct = pw.fecha_Act,*/
    inst.created_at = pw.created_at,
    inst.updated_at = pw.updated_at,
    inst.estado = pw.estado;



    insert into edu_institucioneducativa (NivelModalidad_id,Forma_id,Caracteristica_id,Genero_id,TipoGestion_id,Ugel_id,
			Area_id,EstadoInsEdu_id,Turno_id,CentroPoblado_id,codModular,codLocal,nombreInstEduc,nombreDirector,telefono,/*email,*/
			direccion,coorGeoLatitud,coordGeoLongitud,/*fechaReg,fechaAct,*/created_at,updated_at,estado)
    SELECT nivelmodalidad_id ,forma_id,caracteristica_id ,genero_id ,tipogestion_id,ugel_id , area_id ,estadoinsedu_id , turno_id ,
    centropoblado_id , cod_Mod ,cod_Local ,cen_Edu ,director , telefono ,/*email ,*/dir_Cen ,nLat_IE ,
    nLong_IE ,/*fechaReg ,fecha_Act ,*/created_at ,updated_at , estado
    FROM padronweb_tempo_completo
    where institucion_id is null;


    update par_importacion set estado = 'PR' where id = v_importacion_id;

    update edu_institucioneducativa set es_eib = 'SI'
	where id in (
				select inst.id from edu_institucioneducativa inst
				inner join edu_padron_eib eib on eib.institucioneducativa_id=inst.id
				where  eib.importacion_id in (
						select id from (
								select row_number() OVER (partition BY fuenteImportacion_id  ORDER BY anio desc, fechaActualizacion desc ) AS posicion,imp.id
								from edu_padron_eib eib
								inner join par_importacion imp on eib.importacion_id = imp.id
								inner join par_anio vanio on eib.anio_id = vanio.id
								where imp.estado = 'PR'
						) as dd
						where posicion = 1
				)
	);

    drop table if exists tabla_tempo,forma_tempo,tipogestiondet_tempo,
    centropoblado_tempo,padronweb_tempo_completo;

    INSERT INTO edu_padronweb(
		importacion_id,
		institucioneducativa_id,
        estadoinsedu_id,
		/*localidad, */
		total_alumno_m,
		total_alumno_f,
		total_alumno,
		total_docente,
		total_seccion/*,
		fecha_actual*/
        )
    select
		ipw.importacion_id,
		(select id from edu_institucioneducativa where codModular=ipw.cod_Mod),
        (select id from edu_estadoinsedu where codigo=ipw.estado),
		/*ipw.localidad,*/
		ipw.tAlum_Hom,
		ipw.tAlum_Muj,
		ipw.tAlumno,
		ipw.tDocente,
		ipw.tSeccion/*,
		ipw.fecha_Act */
    from edu_impor_padronweb as ipw
    where ipw.importacion_id=v_importacion_id;
END


# sirve para exportar padron web

SELECT
	ie.codModular as cod_mod,      ie.codLocal     as cod_local,                  ie.nombreInstEduc as institucion_educativa, nm.codigo as cod_nivelmod,         nm.nombre as nivel_modalidad,
	ff.nombre as forma,            cc.codigo       as cod_car,                    cc.nombre as carasteristica,                gg.codigo as cod_genero,           gg.nombre as genero,
    tg1.codigo as cod_gest,        tg1.nombre      as gestion,                    tg2.codigo as cod_ges_dep,                  tg2.nombre as gestion_dependencia, ie.nombreDirector as director,
    ie.telefono,                   ie.direccion    as direccion_centro_educativo, cp.codINEI as codcp_inei,                   cp.codUEMinedu as cod_ccpp,        cp.nombre as centro_poblado,
    aa.codigo as cod_area,         aa.nombre       as area_geografica,            ub1.codigo as codgeo,                       ub2.nombre as provincia,           ub1.nombre as distrito,
    uu2.nombre as d_region,        uu1.codigo      as codooii,                    uu1.nombre as ugel,                         ie.coorGeoLatitud as nlat_ie,      ie.coordGeoLongitud as nlong_ie,
    tt.codigo as cod_tur,          tt.nombre       as turno,                      ei.codigo as cod_estado,                    ei.nombre as estado,               pw.total_alumno_m as talum_hom,
    pw.total_alumno_f as talum_muj,pw.total_alumno as talumno,                    pw.total_docente as tdocente,               pw.total_seccion as tseccion
FROM edu_padronweb pw
inner join edu_institucioneducativa ie 	on ie.id=pw.institucioneducativa_id
inner join edu_nivelmodalidad nm 		on nm.id=ie.NivelModalidad_id
inner join edu_forma ff 				on ff.id=ie.Forma_id
inner join edu_caracteristica cc 		on cc.id=ie.Caracteristica_id
inner join edu_genero gg 				on gg.id=ie.Genero_id
inner join edu_tipogestion tg1 			on tg1.id=ie.TipoGestion_id
inner join edu_tipogestion tg2 			on tg2.id=tg1.dependencia
inner join edu_ugel uu1 				on uu1.id=ie.Ugel_id
left join edu_ugel uu2 				on uu2.id=uu1.dependencia
inner join edu_area aa 					on aa.id=ie.Area_id
inner join edu_estadoinsedu ei 			on ei.id=ie.EstadoInsEdu_id
inner join edu_turno tt 				on tt.id=ie.Turno_id
inner join edu_centropoblado cp 		on cp.id=ie.CentroPoblado_id
inner join par_ubigeo ub1 				on ub1.id=cp.Ubigeo_id
inner join par_ubigeo ub2 				on ub2.id=ub1.dependencia
WHERE pw.importacion_id=383;

####

#
ALTER TABLE edu_sfl ADD fecha_inscripcion DATE NULL DEFAULT NULL AFTER fecha_registro;

#
    select
    	iiee.codLocal as local, max(iiee.id) as iiee, max(uu.nombre) as ugel, max(pv.nombre) as provincia, max(dt.nombre) as distrito, max(aa.nombre) as area,count(codModular) as iiees,
    	sfl.estado, sfl.tipo, sfl.fecha_registro
    from (
    	select iiee.id, iiee.CentroPoblado_id, iiee.codLocal, iiee.codModular, iiee.Area_id, iiee.Ugel_id
    	from edu_institucionEducativa as iiee
    	where iiee.EstadoInsEdu_id = 3 and iiee.TipoGestion_id in (4, 5, 7, 8) and iiee.estado = 'AC' and iiee.NivelModalidad_id not in (14, 15)
    	) as iiee
    inner join edu_centropoblado as cp on cp.id = iiee.CentroPoblado_id
    inner join edu_area as aa on aa.id = iiee.Area_id
    inner join edu_ugel as uu on uu.id = iiee.Ugel_id
    inner join par_ubigeo as dt on dt.id = cp.Ubigeo_id
    inner join par_ubigeo as pv on pv.id = dt.dependencia
    left join edu_sfl as sfl on sfl.institucioneducativa_id = iiee.id
    group by local;


#
CREATE DEFINER=root@localhost PROCEDURE bdsismore.sal_pa_procesarPacto1(IN v_importacion INT, IN v_anio INT)
BEGIN


	/*delete from sal_data_pacto1 where anio=v_anio;*/

	drop table if exists temp1;
	drop table if exists temp2;
	drop table if exists temp3;
	create temporary table temp1(importacion int, anio int,distrito varchar(50),fechai date,fechaf date,fechae date,mes int,estado int);
	create temporary table temp2(importacion int, anio int,distrito varchar(50),fechai date,fechaf date,fechae date,mes int,estado int);

	insert into temp1
	select imp.id, year(imp.fechaActualizacion),pa.distrito ,pa.fecha_inicial ,pa.fecha_final, pa.fecha_envio ,
		IF(date_format(pa.fecha_envio,'%Y-%m-07'),
			month(fecha_envio)
		,0) ,
		IF(year(fecha_inicial)=v_anio and year(fecha_final)=v_anio,
			if(pa.fecha_envio between pa.fecha_inicial and date_format(date_add(pa.fecha_inicial,interval 1 month),'%Y-%m-07') ,1,0)
		,0) as estado
	from sal_impor_padron_actas pa
	left join par_importacion imp on imp.id =pa.importacion_id
	where year(imp.fechaActualizacion)=v_anio and imp.id=v_importacion /*and (year(pa.fecha_inicial)=year(imp.fechaActualizacion) and year(pa.fecha_final)=year(imp.fechaActualizacion))*/
	order by pa.distrito,pa.fecha_envio,fecha_inicial,fecha_final ;

	select * from temp1;

	insert into temp2
	select importacion, anio, distrito , fechai , fechaf, fechae ,0,
	IF(estado=0,/*month(fechaf)-month(fechai) in(1,2,3) and */
		IF(month(fechaf)=month(fechae) or month(fechaf)+1=month(fechae),
			IF(fechae between date_format(fechaf,'%Y-%m-08') and date_format(date_add(fechaf,interval 1 month),'%Y-%m-07') ,1,0)
		,0)
	,1)
	 as estado
	from temp1;

	select * from temp2;


	/*insert into sal_data_pacto1(importacion_id, anio, distrito, mes, estado)
	select importacion, anio, distrito, month(if(year(fechai)=anio,fechai,if(year(fechaf)=anio,fechaf,fechae))) mes, max(estado) from temp2 group by importacion, anio, distrito, mes;

	update par_importacion set estado="PR" where id=v_importacion;*/


END

#
 select count(matriculageneral_id) as basica, sum(IF(modalidad_id=1,1,0)) as ebr, sum(IF(modalidad_id=2,1,0)) as ebe, sum(IF(modalidad_id=3,1,0)) as eba
 from edu_matricula_general_detalle
 inner join edu_institucioneducativa as ie on ie.id = edu_matricula_general_detalle.institucioneducativa_id
 inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id
 inner join par_ubigeo as dt on dt.id = cp.Ubigeo_id
 inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id
 where dt.dependencia = 35 and matriculageneral_id = 15;


 select count(matriculageneral_id) as basica, sum(IF(modalidad_id=1,1,0)) as ebr, sum(IF(modalidad_id=2,1,0)) as ebe, sum(IF(modalidad_id=3,1,0)) as eba
 from edu_matricula_general_detalle
 left join (
 	select ie.id, dd.id as distrito, dd.dependencia as provincia, tg.dependencia as gestion, aa.id as area
 	from edu_institucioneducativa as ie
 	left join edu_centropoblado as cp on cp.id=ie.CentroPoblado_id
 	inner join par_ubigeo as dd on dd.id=cp.Ubigeo_id
 	inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id
 	inner join edu_area as aa on aa.id = ie.Area_id
 	where dd.dependencia = 35
 	) as ie on ie.id=edu_matricula_general_detalle.institucioneducativa_id
 where matriculageneral_id = 15 ;

select distinct institucioneducativa_id  from edu_matricula_general_detalle where matriculageneral_id = 15;



#

CREATE DEFINER=root@localhost PROCEDURE datosx.sal_pa_procesarPacto1(IN v_importacion INT, IN v_anio INT)
BEGIN

	/*delete from sal_data_pacto1 where anio=v_anio;*/

	drop table if exists temp1;
	drop table if exists temp2;
	drop table if exists temp3;
	create temporary table temp1(importacion int, anio int,distrito varchar(50),fechai date,fechaf date,fechae date,mes int,estado int);
	create temporary table temp2(importacion int, anio int,distrito varchar(50),fechai date,fechaf date,fechae date,mes int,estado int);

	insert into temp1
	select imp.id, year(imp.fechaActualizacion),pa.distrito ,pa.fecha_inicial ,pa.fecha_final, pa.fecha_envio ,
		IF(month(pa.fecha_envio)=month(pa.fecha_final),
			IF(month(pa.fecha_final)-month(pa.fecha_inicial)=1,month(pa.fecha_inicial),month(pa.fecha_envio))
		,0) ,
		IF(year(fecha_inicial)=v_anio and year(fecha_final)=v_anio,
			if(pa.fecha_envio between pa.fecha_inicial and date_format(date_add(pa.fecha_inicial,interval 1 month),'%Y-%m-07') ,1,0)
		,0) as estado
	from sal_impor_padron_actas pa
	left join par_importacion imp on imp.id =pa.importacion_id
	where year(imp.fechaActualizacion)=v_anio and imp.id=v_importacion /*and (year(pa.fecha_inicial)=year(imp.fechaActualizacion) and year(pa.fecha_final)=year(imp.fechaActualizacion))*/
	order by pa.distrito,pa.fecha_envio,fecha_inicial,fecha_final ;

	select * from temp1;

	insert into temp2
	select importacion, anio, distrito , fechai , fechaf, fechae ,
	IF(mes=0,
		IF(month(fechae)-1=month(fechaf),
			month(fechaf)
		,0)
	,mes),
	IF(estado=0,
		IF(month(fechaf)=month(fechae) or month(fechaf)+1=month(fechae),
			IF(fechae between date_format(fechaf,'%Y-%m-08') and date_format(date_add(fechaf,interval 1 month),'%Y-%m-07') ,1,0)
		,0)
	,1)
	 as estado
	from temp1;

	select * from temp2;


	/*insert into sal_data_pacto1(importacion_id, anio, distrito, mes, estado)
	select importacion, anio, distrito, month(if(year(fechai)=anio,fechai,if(year(fechaf)=anio,fechaf,fechae))) mes, max(estado) from temp2 group by importacion, anio, distrito, mes;

	update par_importacion set estado="PR" where id=v_importacion;*/

END

#
    public static function iiee($anio, $cedula)
    {
        $query = ImporCensoMatricula::distinct()->select('cod_mod')
            ->join('par_importacion as imp', 'imp.id', '=', 'edu_impor_censomatricula.importacion_id')
            ->where(DB::raw('year(imp.fechaActualizacion)'), $anio)->where('nroced', $cedula)->get();
        foreach ($query as $key => $value) {
            $value->nombre = InstitucionEducativa::where('codModular', $value->cod_mod)->first()->nombreInstEduc;
        }
        // $query->orderBy('nombre');
        return $query;
    }


#
SELECT sum(d01+d02+d03+d04+d05+d06) FROM edu_impor_censodocente where importacion_id=2160 and nroced='8A' and cuadro='C303' and tipdato in('01','02');/*8*/
SELECT sum(d01+d02+d03+d04+d05+d06) FROM edu_impor_censodocente where importacion_id=2160 and nroced='8A' and cuadro='C304' and tipdato in('01','02');/*8*/
SELECT sum(d01+d02+d03+d04+d05+d06) FROM edu_impor_censodocente where importacion_id=2160 and nroced='8A' and cuadro='C306' and tipdato in('01','02','03','04','05','06');/*0*/

SELECT sum(d01+d02+d03+d04+d05+d06+d07+d08) FROM edu_impor_censodocente where importacion_id=2160 and nroced='8AI' and cuadro='C303' and tipdato in('01','02');/*194*/
SELECT sum(d01+d02+d03+d04+d05+d06+d07+d08) FROM edu_impor_censodocente where importacion_id=2160 and nroced='8AI' and cuadro='C304' and tipdato in('01','02');/*194*/
SELECT sum(d01+d02+d03+d04+d05+d06+d07+d08) FROM edu_impor_censodocente where importacion_id=2160 and nroced='8AI' and cuadro='C305' and tipdato in('01','05');/*197*/
SELECT sum(d01+d02+d03+d04+d05+d06+d07+d08) FROM edu_impor_censodocente where importacion_id=2160 and nroced='8AI' and cuadro='C305' and tipdato in('02','03','04');/*171*/
SELECT sum(d01+d02+d03+d04+d05+d06+d07+d08) FROM edu_impor_censodocente where importacion_id=2160 and nroced='8AI' and cuadro='C305' and tipdato in('06','07','08','09','10');/*23*/

SELECT sum(d01+d02+d03+d04+d05+d06+d07+d08) FROM edu_impor_censodocente where importacion_id=2160 and nroced='8AP' and cuadro='C303' and tipdato in('01','02');/*226*/
SELECT sum(d01+d02+d03+d04+d05+d06+d07+d08) FROM edu_impor_censodocente where importacion_id=2160 and nroced='8AP' and cuadro='C304' and tipdato in('01','02');/*226*/
SELECT sum(d01+d02+d03+d04+d05+d06+d07+d08) FROM edu_impor_censodocente where importacion_id=2160 and nroced='8AP' and cuadro='C305' and tipdato in('01','05');/*236*/
SELECT sum(d01+d02+d03+d04+d05+d06+d07+d08) FROM edu_impor_censodocente where importacion_id=2160 and nroced='8AP' and cuadro='C305' and tipdato in('02','03','04');/*206*/
SELECT sum(d01+d02+d03+d04+d05+d06+d07+d08) FROM edu_impor_censodocente where importacion_id=2160 and nroced='8AP' and cuadro='C305' and tipdato in('06','07','08','09','10');/*20*/



##





CREATE DEFINER=root@localhost PROCEDURE datosx.sal_pa_procesarPacto1(IN v_importacion INT, IN v_anio INT)
BEGIN



	drop table if exists temp1;
	drop table if exists temp2;
	drop table if exists temp3;
	create temporary table temp1(importacion int, anio int,distrito varchar(50),fechai date,fechaf date,fechae date,mes int,estado int);
	create temporary table temp2(importacion int, anio int,distrito varchar(50),fechai date,fechaf date,fechae date,mes int,estado int);

	insert into temp1
	select imp.id, year(imp.fechaActualizacion),pa.distrito ,pa.fecha_inicial ,pa.fecha_final, pa.fecha_envio ,
		IF(month(pa.fecha_envio)=month(pa.fecha_final) and fecha_envio<date_format(date_add(pa.fecha_final,interval 1 month),'%Y-%m-08'),
			IF(month(pa.fecha_final)-month(pa.fecha_inicial)=1 and pa.fecha_final<date_format(pa.fecha_final,'%Y-%m-08') ,month(pa.fecha_inicial),month(pa.fecha_envio))
		,0) ,
		IF(year(fecha_inicial)=v_anio and year(fecha_final)=v_anio,
			if(pa.fecha_envio between pa.fecha_inicial and date_format(date_add(pa.fecha_inicial,interval 1 month),'%Y-%m-07') ,1,0)
		,0) as estado
	from sal_impor_padron_actas pa
	left join par_importacion imp on imp.id =pa.importacion_id
	where year(imp.fechaActualizacion)=v_anio and imp.id=v_importacion
	order by pa.distrito,pa.fecha_envio,fecha_inicial,fecha_final ;

	select * from temp1;

	insert into temp2
	select importacion, anio, distrito , fechai , fechaf, fechae ,
	IF(mes=0,
		IF(month(fechae)-1=month(fechaf),
			month(fechaf)
		,0)
	,mes),
	IF(estado=0,
		IF(month(fechaf)=month(fechae) or month(fechaf)+1=month(fechae),
			IF(fechae between date_format(fechaf,'%Y-%m-08') and date_format(date_add(fechaf,interval 1 month),'%Y-%m-07') ,1,0)
		,0)
	,1)
	 as estado
	from temp1;

	select * from temp2;




END

###

CREATE DEFINER=root@localhost PROCEDURE datosx.sal_pa_procesarPacto1(IN v_importacion INT, IN v_anio INT)
BEGIN

	delete from sal_data_pacto1 where anio=v_anio;

	drop table if exists temp1;
	drop table if exists temp2;
	drop table if exists temp3;
	create temporary table temp1(importacion int, anio int,distrito varchar(50),fechai date,fechaf date,fechae date,mes int,estado int);
	create temporary table temp2(importacion int, anio int,distrito varchar(50),fechai date,fechaf date,fechae date,mes int,estado int);

	insert into temp1
	select imp.id, year(imp.fechaActualizacion),pa.distrito ,pa.fecha_inicial ,pa.fecha_final, pa.fecha_envio ,
		IF(pa.fecha_envio<date_format(pa.fecha_envio,'%Y-%m-08'),
			month(pa.fecha_envio)-1
		,month(pa.fecha_envio)) as mes,
		0 as estado
	from sal_impor_padron_actas pa
	left join par_importacion imp on imp.id =pa.importacion_id
	where year(imp.fechaActualizacion)=v_anio and imp.id=v_importacion
	order by pa.distrito,pa.fecha_envio,fecha_inicial,fecha_final ;

	select * from temp1;

	insert into temp2
	select importacion, anio, distrito , fechai , fechaf, fechae , mes,
	IF(mes>0,
		IF(fechae between fechai and IF(month(fechaf)=mes,date_format(date_add(fechaf,interval 1 month),'%Y-%m-07'),date_format(fechaf,'%Y-%m-07'))
		,1
		,0)
	,0)
	 as estado
	from temp1;

	select * from temp2;

    insert into sal_data_pacto1(importacion_id, anio, distrito, mes, estado)
	select importacion, anio, distrito, mes, max(estado) from temp2 where mes!=0 group by importacion, anio, distrito, mes;

	update par_importacion set estado="PR" where id=v_importacion;


END


###

ALTER TABLE par_indicador_general ADD usuario INT NULL DEFAULT NULL AFTER ficha_tecnica;


##

CREATE DEFINER=root@localhost PROCEDURE datosx.sal_pa_procesarPacto1(IN v_importacion INT, IN v_anio INT)
BEGIN

	delete from sal_data_pacto1 where anio=v_anio;

	drop table if exists temp1;
	drop table if exists temp2;
	drop table if exists temp3;
	create temporary table temp1(importacion int, anio int,distrito varchar(50),fechai date,fechaf date,fechae date,mes int,estado int);
	create temporary table temp2(importacion int, anio int,distrito varchar(50),fechai date,fechaf date,fechae date,mes int,estado int);

	insert into temp1
	select imp.id,IF(month(pa.fecha_envio)-1=0,if(pa.fecha_envio>date_format(pa.fecha_envio,'%Y-%m-07'),year(imp.fechaActualizacion),year(imp.fechaActualizacion)-1),year(imp.fechaActualizacion)) ,pa.distrito ,pa.fecha_inicial ,pa.fecha_final, pa.fecha_envio ,
		IF(pa.fecha_envio<date_format(pa.fecha_envio,'%Y-%m-08'),
			IF(month(pa.fecha_envio)-1=0,12,month(pa.fecha_envio)-1)
		,month(pa.fecha_envio)) as mes,
		0 as estado
	from sal_impor_padron_actas pa
	left join par_importacion imp on imp.id =pa.importacion_id
	where year(imp.fechaActualizacion)=v_anio and imp.id=v_importacion
	order by pa.distrito,pa.fecha_envio,fecha_inicial,fecha_final ;

	select * from temp1;

	insert into temp2
	select importacion, anio, distrito , fechai , fechaf, fechae , mes,
	IF(mes>0,
		IF(fechae between fechai and IF(month(fechaf)=mes,date_format(date_add(fechaf,interval 1 month),'%Y-%m-07'),date_format(fechaf,'%Y-%m-07'))
		,1
		,0)
	,0)
	 as estado
	from temp1;

	select * from temp2;

    insert into sal_data_pacto1(importacion_id, anio, distrito, mes, estado)
	select importacion, anio, distrito, mes, max(estado) from temp2 where mes!=0 group by importacion, anio, distrito, mes;

	update par_importacion set estado="PR" where id=v_importacion;

END

##

CREATE DEFINER=root@localhost PROCEDURE datosx.sal_pa_procesarPacto1(IN v_importacion INT, IN v_anio INT)
BEGIN

	delete from sal_data_pacto1 where anio=v_anio;

	drop table if exists temp1;
	drop table if exists temp2;
	drop table if exists temp3;
	create temporary table temp1(importacion int, anio int,distrito varchar(50),fechai date,fechaf date,fechae date,mes int,estado int);
	create temporary table temp2(importacion int, anio int,distrito varchar(50),fechai date,fechaf date,fechae date,mes int,estado int);

	insert into temp1
	select imp.id,IF(month(pa.fecha_envio)-1=0,if(pa.fecha_envio>date_format(pa.fecha_envio,'%Y-%m-07'),year(imp.fechaActualizacion),year(imp.fechaActualizacion)-1),year(imp.fechaActualizacion)) ,pa.distrito ,pa.fecha_inicial ,pa.fecha_final, pa.fecha_envio ,
		IF(pa.fecha_envio<date_format(pa.fecha_envio,'%Y-%m-08'),
			IF(month(pa.fecha_envio)-1=0,12,month(pa.fecha_envio)-1)
		,month(pa.fecha_envio)) as mes,
		0 as estado
	from sal_impor_padron_actas pa
	left join par_importacion imp on imp.id =pa.importacion_id
	where year(imp.fechaActualizacion)=v_anio and imp.id=v_importacion
	order by pa.distrito,pa.fecha_envio,fecha_inicial,fecha_final ;

	select * from temp1;

	insert into temp2
	select importacion, anio, distrito , fechai , fechaf, fechae , mes,
	IF(mes>0,
		IF(fechae<fechaf,0,
		IF(fechae between fechai and IF(month(fechaf)=mes,date_format(date_add(fechaf,interval 1 month),'%Y-%m-07'),date_format(fechaf,'%Y-%m-07'))
		,1
		,0)
		)
	,0)
	 as estado
	from temp1;

	select * from temp2;

    insert into sal_data_pacto1(importacion_id, anio, distrito, mes, estado)
	select importacion, anio, distrito, mes, max(estado) from temp2 where mes!=0 group by importacion, anio, distrito, mes;

	update par_importacion set estado="PR" where id=v_importacion;

END


####################

CREATE DEFINER=root@localhost FUNCTION datosx.buscar_estado(v_local varchar(10)) RETURNS int(11)
begin
	set @ee1:=(select count(*) conteo from edu_sfl sfl inner join edu_institucioneducativa ie on ie.id=sfl.institucioneducativa_id where ie.codLocal=v_local and ie.EstadoInsEdu_id=3 and sfl.estado=1);
	set @ee2:=(select count(*) conteo from edu_sfl sfl inner join edu_institucioneducativa ie on ie.id=sfl.institucioneducativa_id where ie.codLocal=v_local and ie.EstadoInsEdu_id=3 and sfl.estado=2);
	set @ee3:=(select count(*) conteo from edu_sfl sfl inner join edu_institucioneducativa ie on ie.id=sfl.institucioneducativa_id where ie.codLocal=v_local and ie.EstadoInsEdu_id=3 and sfl.estado=3);
	set @ee4:=(select count(*) conteo from edu_sfl sfl inner join edu_institucioneducativa ie on ie.id=sfl.institucioneducativa_id where ie.codLocal=v_local and ie.EstadoInsEdu_id=3 and sfl.estado=4);
	set @eet:=(select count(*) conteo from edu_sfl sfl inner join edu_institucioneducativa ie on ie.id=sfl.institucioneducativa_id where ie.codLocal=v_local and ie.EstadoInsEdu_id=3);

	return
	IF(@eet=@ee1,1,
		IF(@eet=@ee2,2,
			IF(@eet=@ee3,3,
				IF(@eet=@ee4,4,2)
			)
		)
	);
end

#######################

CREATE DEFINER=root@localhost PROCEDURE datosx.edu_pa_sfl_porlocal(IN v_ugel INT, IN v_provincia INT, IN v_distrito INT, IN v_estado INT)
BEGIN
	set @query='
	select local, ugel, provincia, distrito, area, estado from (
		select local, ugel, provincia, distrito,area,ugelid,provinciaid,distritoid,areaid, buscar_estado(local) as estado from (
			select
				local, max(ugel_n) as ugel, max(provincia_n) as provincia, max(distrito_n) as distrito, max(distrito_n) as area,
				max(ugel_id) ugelid, max(provincia_id) provinciaid, max(distrito_id) distritoid, max(area_id) areaid
			from (
				select
					iiee.id iiee_id, iiee.codLocal local, iiee.Ugel_id ugel_id, pv.id provincia_id, dt.id distrito_id, iiee.Area_id area_id,
					uu.nombre ugel_n, pv.nombre provincia_n, dt.nombre distrito_n, aa.nombre area_n
				from edu_institucionEducativa as iiee
				inner join edu_centropoblado as cp on cp.id = iiee.CentroPoblado_id
				inner join edu_area as aa on aa.id = iiee.Area_id
				inner join edu_ugel as uu on uu.id = iiee.Ugel_id
				inner join par_ubigeo as dt on dt.id = cp.Ubigeo_id
				inner join par_ubigeo as pv on pv.id = dt.dependencia
				where iiee.EstadoInsEdu_id = 3 and iiee.TipoGestion_id in (4, 5, 7, 8) and iiee.estado = "AC" and iiee.NivelModalidad_id not in (14, 15)
			) as iiee ';
	set @query=concat(@query,' group by local ) as tb order by estado )as tbx');
	set @query=concat(@query,' where 1');
	set @query=concat(@query,if(v_ugel>0     ,concat(' and ugelid=',v_ugel),''));
	set @query=concat(@query,if(v_provincia>0,concat(' and provinciaid=',v_provincia),''));
	set @query=concat(@query,if(v_distrito>0 ,concat(' and distritoid=',v_distrito),''));
	set @query=concat(@query,if(v_estado>0   ,concat(' and estado=',v_estado),''));

	prepare xxx from @query;
	execute xxx;
	deallocate prepare xxx;

END

################################
ALTER TABLE adm_usuario
ADD sector VARCHAR(45) NULL DEFAULT NULL AFTER cargo,
ADD nivel VARCHAR(45) NULL DEFAULT NULL AFTER sector,
ADD codigo_institucion VARCHAR(45) NULL DEFAULT NULL AFTER nivel;



select * from edu_tipogestion where dependencia in(1,2);
select * from edu_estadoinsedu;
select * from edu_nivelmodalidad en ;
select count(*) from (
select distinct codLocal  from edu_institucioneducativa where estado='AC' and EstadoInsEdu_id=3 and TipoGestion_id in (4, 5, 7, 8) and NivelModalidad_id not in (14, 15)
) as tb;

select importacion_id,count(codLocal) from (
select pw.importacion_id,ie.codLocal from edu_padronweb pw
inner join edu_institucioneducativa ie on ie.id=pw.institucioneducativa_id
where ie.codLocal!='' and ie.TipoGestion_id in (4, 5, 7, 8) and ie.NivelModalidad_id not in (14, 15)and EstadoInsEdu_id=3
group by pw.importacion_id,ie.codLocal
) as tb group by importacion_id


select /*pw.importacion_id,ie.codModular,*/ie.codLocal from edu_padronweb pw
inner join par_importacion ii on ii.id=pw.importacion_id
inner join edu_institucioneducativa ie on ie.id=pw.institucioneducativa_id
where 1 and ie.TipoGestion_id in (4, 5, 7, 8) and ie.NivelModalidad_id not in (14, 15) and pw.estadoinsedu_id=3 and year(ii.fechaActualizacion)=2024 /*and pw.importacion_id=2505*/ /*and ie.codModular IN('0514232','0273086','1792795')*/
group by /*pw.importacion_id,*/ie.codLocal
ORDER BY /*pw.importacion_id desc,*/ie.codLocal asc;

# modificar edu_padronweb con x_padronweb
update edu_padronweb pw
inner join par_importacion ii on ii.id=pw.importacion_id
inner join edu_institucioneducativa ie on ie.id=pw.institucioneducativa_id
set pw.estadoinsedu_id=(select IF(cod_estado=1,3,IF(cod_estado=2,2,1)) from x_padronweb where cod_mod=ie.codModular and importacion_id=ii.id)
WHERE 1 and year(ii.fechaActualizacion)=2024


update adm_entidad set tipoentidad_id=7 where length(codigo)=6;

##############

ALTER TABLE adm_usuario ADD login_count INT NOT NULL AFTER codigo_institucion;

#######################

CREATE TABLE adm_login_records ( id INT AUTO_INCREMENT PRIMARY KEY, user_id INT, login_at TIMESTAMP NULL, logout_at TIMESTAMP NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP );

CREATE TABLE adm_login_records (
  id int(11) auto_increment primary key,
  usuario int(11) DEFAULT NULL,
  login timestamp NULL DEFAULT NULL,
  logout timestamp NULL DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  updated_at timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  foreign key (usuario) references adm_usuario(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
##################################
CREATE TABLE adm_login_navigation (
    id BIGINT  AUTO_INCREMENT PRIMARY KEY,
    usuario int  NOT NULL,
    url VARCHAR(255) NOT NULL,
    visited_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario) REFERENCES adm_usuario(id)
);


###############################################

SELECT * FROM sal_establecimiento where estado='ACTIVO' and ubigeo_id in(57,56,49);

consulta y modificaion huipoca
SELECT * FROM sal_establecimiento where estado='ACTIVO' and cod_unico in(5400 ,5410 ,30638 ,30639 );
update sal_establecimiento set ubigeo_id=56 where estado='ACTIVO' and cod_unico in(5400 ,5410 ,30638 ,30639 );

consulta y modificaion boqueron
SELECT * FROM sal_establecimiento where estado='ACTIVO' and cod_unico in(5399 ,5401 ,5397 ,5403 ,5406 );
update sal_establecimiento set ubigeo_id=57 where estado='ACTIVO' and cod_unico in(5399 ,5401 ,5397 ,5403 ,5406 );



##############################################

CREATE TABLE sal_padron_actas (
  id int(11) auto_increment primary key,
  ubigeo_id int(11) DEFAULT NULL,
  establecimiento_id int(11) DEFAULT NULL,
  usuario_id int(11) DEFAULT NULL,
  fecha_inicial date DEFAULT NULL,
  fecha_final date DEFAULT NULL,
  fecha_envio date DEFAULT NULL,
  nro_archivos int(11) DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  updated_at timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  foreign key(ubigeo_id) references par_ubigeo(id),
  foreign key(establecimiento_id) references sal_establecimiento(id),
  foreign key(usuario_id) references adm_usuario(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

#################################################
<!DOCTYPE html>
<html>
<head>
    <title>Gráfico de Columnas Apiladas</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
</head>
<body>
    <div id="container" style="width:100%; height:400px;"></div>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            Highcharts.chart('container', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Resultado de logros de aprendizaje por años, según nivel de desempeño'
                },
                xAxis: {
                    categories: ['2019', '2022', '2023']
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Porcentaje'
                    }
                },
                legend: {
                    reversed: true
                },
                plotOptions: {
                    series: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: true,
                            format: '{point.y}%' // Muestra el valor con un símbolo de porcentaje
                        }
                    }
                },
                series: [{
                    name: 'Previo al inicio',
                    data: [30, 25, 20],
                    color: '#CCCCCC'
                }, {
                    name: 'En inicio',
                    data: [15, 16, 20],
                    color: '#FF5733'
                }, {
                    name: 'En proceso',
                    data: [25, 20, 20],
                    color: '#FFC300'
                }, {
                    name: 'Satisfactorio',
                    data: [30, 40, 38],
                    color: '#33FF57'
                }]
            });
        });
    </script>
</body>
</html>


################################################################

CREATE TABLE establecimiento (
  id int(11) auto_increment primary key,
  nombre	varchar(100)	NULL DEFAULT NULL,
ubigeo	varchar(100)	NULL DEFAULT NULL,
Codigo_Disa	varchar(100)	NULL DEFAULT NULL,
disa	varchar(100)	NULL DEFAULT NULL,
cod_red	varchar(100)	NULL DEFAULT NULL,
red	varchar(100)	NULL DEFAULT NULL,
cod_microred	varchar(100)	NULL DEFAULT NULL,
microred	varchar(100)	NULL DEFAULT NULL,
cod_unico	varchar(100)	NULL DEFAULT NULL,
cod_sector	varchar(100)	NULL DEFAULT NULL,
descripcion	varchar(100)	NULL DEFAULT NULL,
departamento	varchar(100)	NULL DEFAULT NULL,
provincia	varchar(100)	NULL DEFAULT NULL,
distrito	varchar(100)	NULL DEFAULT NULL,
categoria	varchar(100)	NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

################################################

SELECT ue.* FROM pres_unidadejecutora ue join pres_pliego pl on pl.id=ue.pliego_id join pres_sector se on se.id=pl.sector_id where se.id=22;

##################################################


CREATE TABLE edu_impor_evaluacion_muestral (
  id int(11) auto_increment primary key,
  importacion_id int(11) DEFAULT NULL,
  anio int(11) DEFAULT NULL,
  cod_mod varchar(7) DEFAULT NULL,
  institucion_educativa varchar(300) DEFAULT NULL,
  nivel varchar(100) DEFAULT NULL,
  grado varchar(100) DEFAULT NULL,
  seccion varchar(100) DEFAULT NULL,
  gestion varchar(100) DEFAULT NULL,
  caracteristica varchar(100) DEFAULT NULL,
  codooii varchar(6) DEFAULT NULL,
  codgeo varchar(6) DEFAULT NULL,
  area_geografica varchar(100) DEFAULT NULL,
  sexo varchar(100) DEFAULT NULL,
  medida_l double DEFAULT NULL,
  grupo_l varchar(15) DEFAULT NULL,
  peso_l double DEFAULT NULL,
  medida_m double DEFAULT NULL,
  grupo_m varchar(15) DEFAULT NULL,
  peso_m double DEFAULT NULL,
  medida_cn double DEFAULT NULL,
  grupo_cn varchar(15) DEFAULT NULL,
  peso_cn double DEFAULT NULL,
  medida_cs double DEFAULT NULL,
  grupo_cs varchar(15) DEFAULT NULL,
  peso_cs double DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  updated_at timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  foreign key(importacion_id) references par_importacion(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

###################################################

update edu_impor_evaluacion_muestral set caracteristica='UNIDOCENTE / MULTIGRADO' where caracteristica='UNIDOCENTE/MULTIGRADO'


########################
select aa.dni dnix,concat(aa.nombres,' ',aa.apellido_paterno,' ',aa.apellido_materno) alumnox,ie.cod_mod cod_modular, ie.nombre_ie iiee,sum(if(ss.estado='SI',1,0)) si,sum(if(ss.estado='NO',1,0)) no, if(sum(if(ss.estado='SI',1,0))>21,'SI','NO') CUMPLE from alumno aa inner join iiee ie on ie.id=aa.iiee_id inner join seguimiento ss on ss.alumno=aa.id group by cod_modular,iiee,dnix,alumnox;

############################

CREATE TABLE datax1 (
id int(11) auto_increment primary key,
nombre_pronoei varchar(100) NULL DEFAULT NULL,
codi_modular varchar(10) NULL DEFAULT NULL,
seccion varchar(100) NULL DEFAULT NULL,
direccion varchar(100) NULL DEFAULT NULL,
profesora_coordinadora varchar(100) NULL DEFAULT NULL,
celular_prof_coord varchar(10) NULL DEFAULT NULL,
nombre_promotora varchar(100) NULL DEFAULT NULL,
celular_promotora varchar(20) NULL DEFAULT NULL,
apellido_nombre_alumno varchar(100) NULL DEFAULT NULL,
dni varchar(12) NULL DEFAULT NULL,
fecha_nacimiento varchar(10) NULL DEFAULT NULL,
red_interviene varchar(100) NULL DEFAULT NULL,
microred_interviene varchar(100) NULL DEFAULT NULL,
eess_interviene varchar(100) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

##########################################

uniendo 2 tablas de pronoei

SELECT
    t1.cod_modular,
    t1.conteo1,
    t2.conteo2
FROM
    (SELECT ie.cod_mod cod_modular, COUNT(aa.id) conteo1
     FROM alumno aa
     INNER JOIN iiee ie ON ie.id=aa.iiee_id
     GROUP BY ie.cod_mod) t1
LEFT JOIN
    (SELECT LPAD(codi_modular, 7, '0') cod_modular, COUNT(id) conteo2
     FROM datax1
     GROUP BY cod_modular) t2
ON t1.cod_modular = t2.cod_modular
ORDER BY t1.cod_modular;



##################################################

SELECT mm.anio,uu.codigo,mm.distrito distrito_id,uu.nombre distrito,mm.valor meta FROM par_indicador_general_meta mm
inner join par_ubigeo uu on uu.id=mm.distrito where indicadorgeneral=19 order by anio,codigo;



#######################################################

select
	anio,
	case
		when codigo = "A2" OR codigo = "A3" OR codigo = "A5" then "Inicial"
		else nombre
	end as nivel,
	count(anio) as conteo
from
	(
	select mg.anio as anio, mgd.institucioneducativa_id
	from edu_matricula_general_detalle as mgd
	left join (
		SELECT id, anio
		FROM
		    (SELECT
		        mg.id,
		        anio.anio,
		        ROW_NUMBER() OVER (PARTITION BY anio.anio ORDER BY mg.id DESC) AS rn
		    FROM
		        edu_matricula_general mg
		    INNER JOIN par_importacion AS imp ON imp.id = mg.importacion_id
		    INNER JOIN par_anio AS anio ON anio.id = mg.anio_id
		    WHERE imp.estado = 'PR') as tb
		WHERE rn = 1 ) as mg on mg.id = mgd.matriculageneral_id ) as mgd
inner join (
	select
		ie.id,
		dd.id as distrito,
		dd.dependencia as provincia,
		tg.dependencia as gestion,
		aa.id as area,
		nm.codigo,
		nm.nombre
	from edu_institucioneducativa as ie
	inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id
	inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id
	inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id
	inner join edu_area as aa on aa.id = ie.Area_id
	inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id
	where nm.tipo = "EBR" ) as ie on ie.id = mgd.institucioneducativa_id -- where anio > 0
group by anio, nivel
order by anio asc;



select count(*)-- mg.anio as anio, mgd.institucioneducativa_id
from edu_matricula_general_detalle as mgd
left join (
	select id, anio
	from (
		select mg.id, anio.anio, row_number() over (partition by anio.anio
		order by mg.id desc) as rn
		from edu_matricula_general mg
		inner join par_importacion as imp on imp.id = mg.importacion_id
		inner join par_anio as anio on anio.id = mg.anio_id
		where imp.estado = 'PR') as tb
	where rn = 1 ) as mg on mg.id = mgd.matriculageneral_id where anio is null -- group by mg.anio

	##########################################################################################################

select
	anio,
	case
		when codigo = "A2" OR codigo = "A3" OR codigo = "A5" then "Inicial"
		else nombre
	end as nivel,
	count(anio) as conteo
from
	(
	select mg.anio as anio, mgd.institucioneducativa_id
	from edu_matricula_general_detalle as mgd
	inner join (
		SELECT id, anio
		FROM
		    (SELECT
		        mg.id,
		        anio.anio,
		        ROW_NUMBER() OVER (PARTITION BY anio.anio ORDER BY mg.id DESC) AS rn
		    FROM
		        edu_matricula_general mg
		    INNER JOIN par_importacion AS imp ON imp.id = mg.importacion_id
		    INNER JOIN par_anio AS anio ON anio.id = mg.anio_id
		    WHERE imp.estado = 'PR') as tb
		WHERE rn = 1 ) as mg on mg.id = mgd.matriculageneral_id ) as mgd
inner join (
	select
		ie.id,
		dd.id as distrito,
		dd.dependencia as provincia,
		tg.dependencia as gestion,
		aa.id as area,
		nm.codigo,
		nm.nombre
	from edu_institucioneducativa as ie
	inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id
	inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id
	inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id
	inner join edu_area as aa on aa.id = ie.Area_id
	inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id
	where nm.tipo = "EBR" ) as ie on ie.id = mgd.institucioneducativa_id where anio > 0
group by anio, nivel
order by anio asc;
#####################################################################################################


SELECT ig.nombre,igm.distrito iddistrito,uu.nombre distrito, igm.anio,igm.valor FROM par_indicador_general ig
inner join par_indicador_general_meta igm on igm.indicadorgeneral=ig.id
inner join par_ubigeo uu on uu.id=igm.distrito WHERE sector_id=14;

#######################################################################################################

CREATE TABLE pvica (
    id int(11) auto_increment primary key,
    ubigeo_ccpp varchar(10) NULL DEFAULT NULL,
    nombre_ccpp varchar(100) NULL DEFAULT NULL,
    departamento_cpp varchar(100) NULL DEFAULT NULL,
    provincia_ccpp varchar(100) NULL DEFAULT NULL,
    distrito_ccpp varchar(100) NULL DEFAULT NULL,
    ambito_ccpp varchar(100) NULL DEFAULT NULL,
    pob_total_ccpp int(11) NULL DEFAULT NULL,
    pop_servida_ccpp int(11) NULL DEFAULT NULL,
    quintil_ccpp varchar(2) NULL DEFAULT NULL,
    dd_ccpp varchar(100) NULL DEFAULT NULL,
    da_ccpp varchar(4) NULL DEFAULT NULL,
    codigo_ipress varchar(8) NULL DEFAULT NULL,
    nombre_ipress varchar(100) NULL DEFAULT NULL,
    diresa_ipress varchar(100) NULL DEFAULT NULL,
    red_ipress varchar(100) NULL DEFAULT NULL,
    microred_ipress varchar(100) NULL DEFAULT NULL,
    id_sistema_abstecimiento_agua int(11) NULL DEFAULT NULL,
    tipo_sistema_abstecimiento_agua varchar(100) NULL DEFAULT NULL,
    nombre_sistema_abstecimiento_agua varchar(100) NULL DEFAULT NULL,
    id_proveedor_agua int(11) NULL DEFAULT NULL,
    tipo_proveedor_agua varchar(100) NULL DEFAULT NULL,
    nombre_proveedor_agua varchar(100) NULL DEFAULT NULL,
    muestreo int(11) NULL DEFAULT NULL,
    muestra int(11) NULL DEFAULT NULL,
    este_coordenadas_muestra int(11) NULL DEFAULT NULL,
    norte_coordenadas_muestra int(11) NULL DEFAULT NULL,
    huso_banda_coordenadas_muestra varchar(4) NULL DEFAULT NULL,
    altura_coordenadas_muestra int(11) NULL DEFAULT NULL,
    estado_muestreo varchar(100) NULL DEFAULT NULL,
    fecha_muestreo varchar(10) NULL DEFAULT NULL,
    fecha_finalizado varchar(20) NULL DEFAULT NULL,
    id_lugar_muestreo varchar(100) NULL DEFAULT NULL,
    ubicacion_lugar_muestreo varchar(100) NULL DEFAULT NULL,
    nombre_lugar_muestreo varchar(100) NULL DEFAULT NULL,
    horas_dia_continuidad int(11) NULL DEFAULT NULL,
    dias_semana_continuidad int(11) NULL DEFAULT NULL,
    cloro_parametros_decreto double NULL DEFAULT NULL,
    conductividad_parametros_decreto int(11) NULL DEFAULT NULL,
    ph_parametros_decreto double NULL DEFAULT NULL,
    temperatura_parametros_decreto double NULL DEFAULT NULL,
    turbiedad_parametros_decreto double NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

###################################################################
CREATE TABLE centropoblado_ninios (
    id int(11) auto_increment primary key,
    ubigeo varchar(10) NULL DEFAULT NULL,
    cantidad int(11) NULL DEFAULT NULL,
    distrito varchar(100) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

####################################################################

CREATE TABLE sal_padron_pvica (
    id int(11) auto_increment primary key,
				importacion_id int(11) DEFAULT NULL,
    ubigeo_ccpp varchar(10) NULL DEFAULT NULL,
    nombre_ccpp varchar(100) NULL DEFAULT NULL,
    departamento_cpp varchar(100) NULL DEFAULT NULL,
    distrito_ccpp varchar(100) NULL DEFAULT NULL,
    ambito_ccpp varchar(100) NULL DEFAULT NULL,
    nombre_ipress varchar(100) NULL DEFAULT NULL,
    diresa_ipress varchar(100) NULL DEFAULT NULL,
    red_ipress varchar(100) NULL DEFAULT NULL,
    microred_ipress varchar(100) NULL DEFAULT NULL,
    ubicacion_lugar_muestreo varchar(100) NULL DEFAULT NULL,
    nombre_lugar_muestreo varchar(100) NULL DEFAULT NULL,
    horas_dia_continuidad int(11) NULL DEFAULT NULL,
    dias_semana_continuidad int(11) NULL DEFAULT NULL,
    cloro_parametros_decreto double NULL DEFAULT NULL,
    conductividad_parametros_decreto int(11) NULL DEFAULT NULL,
    ph_parametros_decreto double NULL DEFAULT NULL,
    temperatura_parametros_decreto double NULL DEFAULT NULL,
    turbiedad_parametros_decreto double NULL DEFAULT NULL,
				foreign key(importacion_id) references par_importacion(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE sal_reporte_pn05 (
    id int(11) auto_increment primary key,
				importacion_id int(11) DEFAULT NULL,
    distrito varchar(6) NULL DEFAULT NULL,
    centro_poblado varchar(10) NULL DEFAULT NULL,
    nro_ninios int(11) NULL DEFAULT NULL,
				foreign key(importacion_id) references par_importacion(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

#########################################################################

SELECT cpn.distrito,sum(cpn.cantidad) niños_clorados,cpd.cantidad niños_vivientes,round(100*sum(cpn.cantidad)/cpd.cantidad,2) FROM(
    SELECT ubigeo_ccpp,nombre_ccpp FROM pvica
    where cloro_parametros_decreto>=0.5
    group by ubigeo_ccpp,nombre_ccpp order by ubigeo_ccpp
) as cp
inner join centropoblado_ninios cpn on cpn.ubigeo=cp.ubigeo_ccpp
inner join (select distrito ,sum(cantidad) cantidad from centropoblado_ninios group by distrito) cpd on cpd.distrito=cpn.distrito
group by cpn.distrito,cpd.distrito;

select distrito ,sum(cantidad) from centropoblado_ninios group by distrito;


SELECT cpn.distrito,sum(cpn.nro_ninios) niños_clorados,cpd.cantidad niños_vivientes,round(100*sum(cpn.nro_ninios)/cpd.cantidad,2) FROM(
    SELECT importacion_id,ubigeo_ccpp,nombre_ccpp FROM sal_padron_pvica
    where cloro_parametros_decreto>=0.5 and importacion_id =2582
    group by importacion_id,ubigeo_ccpp,nombre_ccpp order by ubigeo_ccpp
) as cp
inner join sal_reporte_pn05 cpn on cpn.centro_poblado =cp.ubigeo_ccpp and cpn.importacion_id=2587
inner join (select distrito ,sum(nro_ninios) cantidad from sal_reporte_pn05 where importacion_id =2587 group by distrito) cpd on cpd.distrito=cpn.distrito
group by cpn.distrito,cpd.distrito;


###################################################################################

CREATE TABLE par_poblacion_proyectada (
    id int(11) auto_increment primary key,
				importacion_id int(11) DEFAULT NULL,
				anio int(11) DEFAULT NULL,
				fuente varchar(20) NULL DEFAULT NULL,
				departamento varchar(100) NULL DEFAULT NULL,
				edad int(11) DEFAULT NULL,
				rango varchar(20) NULL DEFAULT NULL,
				mujer int(11) DEFAULT NULL,
				hombre int(11) DEFAULT NULL,
				total int(11) DEFAULT NULL,
				foreign key(importacion_id) references par_importacion(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE par_poblacion_padron_nominal (
    id int(11) auto_increment primary key,
				importacion_id int(11) DEFAULT NULL,
				anio int(11) DEFAULT NULL,
				mes varchar(20) NULL DEFAULT NULL,
				ubigeo varchar(6) NULL DEFAULT NULL,
				cnv int(11) DEFAULT NULL,
				seguro int(11) DEFAULT NULL,
				sexo int(11) DEFAULT NULL,
				28dias int(11) DEFAULT NULL,
				0_5meses int(11) DEFAULT NULL,
				6_11meses int(11) DEFAULT NULL,
				0_12meses int(11) DEFAULT NULL,
				0a int(11) DEFAULT NULL,
				1a int(11) DEFAULT NULL,
				2a int(11) DEFAULT NULL,
				3a int(11) DEFAULT NULL,
				4a int(11) DEFAULT NULL,
				5a int(11) DEFAULT NULL,
				foreign key(importacion_id) references par_importacion(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE par_poblacion_diresa (
    id int(11) auto_increment primary key,
				importacion_id int(11) DEFAULT NULL,
				ubigeo  varchar(6) NULL DEFAULT NULL,
				sexo  varchar(20) NULL DEFAULT NULL,
				edad  varchar(20) NULL DEFAULT NULL,
				rango  varchar(20) NULL DEFAULT NULL,
				total int(11) DEFAULT NULL,
				foreign key(importacion_id) references par_importacion(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE par_pueblos_indigenas (
    id int(11) auto_increment primary key,
				importacion_id int(11) DEFAULT NULL,
				nombre_localidad varchar(100) NULL DEFAULT NULL,
				tipo_localidad varchar(100) NULL DEFAULT NULL,
				ambito_pueblo_indígena varchar(100) NULL DEFAULT NULL,
				nombre_pueblo_indígena varchar(100) NULL DEFAULT NULL,
				referencia varchar(100) NULL DEFAULT NULL,
				ubigeo varchar(6) NULL DEFAULT NULL,
				ubigeo_cp varchar(10) NULL DEFAULT NULL,
				fuente varchar(200) NULL DEFAULT NULL,
				resolucion_reconocimiento varchar(100) NULL DEFAULT NULL,
				fecha_reconocimiento date NULL DEFAULT NULL,
				resolucion_plano varchar(200) NULL DEFAULT NULL,
				fecha_plano date NULL DEFAULT NULL,
				resolución_titulo varchar(100) NULL DEFAULT NULL,
				fecha_titulo date NULL DEFAULT NULL,
				nro_titulo varchar(100) NULL DEFAULT NULL,
				partida_electronica int(11)  NULL DEFAULT NULL,
				area_titulada double NULL DEFAULT NULL,
				area_uso double NULL DEFAULT NULL,
				area_protegida double NULL DEFAULT NULL,
				total_superfie double NULL DEFAULT NULL,
				estado varchar(100) NULL DEFAULT NULL,
				observacion varchar(300) NULL DEFAULT NULL,
				edad_0_4 int(11)  NULL DEFAULT NULL,
				edad_5_14 int(11)  NULL DEFAULT NULL,
				edad_15_29 int(11)  NULL DEFAULT NULL,
				edad_30_64 int(11)  NULL DEFAULT NULL,
				edad_65_mas int(11)  NULL DEFAULT NULL,
				poblacion_hombre int(11)  NULL DEFAULT NULL,
				poblacion_mujer int(11)  NULL DEFAULT NULL,
				poblacion_total int(11)  NULL DEFAULT NULL,
				hogares int(11)  NULL DEFAULT NULL,
				viviendas int(11)  NULL DEFAULT NULL,
				viviendas_sin_agua int(11)  NULL DEFAULT NULL,
				viviendas_sin_desague int(11)  NULL DEFAULT NULL,
				viviendas_sin_electricidad int(11)  NULL DEFAULT NULL,
				codigo_ipress varchar(8) NULL DEFAULT NULL,
				nombre_ipress varchar(150) NULL DEFAULT NULL,
				categoría_ipress varchar(100) NULL DEFAULT NULL,
				estado_ipress varchar(100) NULL DEFAULT NULL,
				tipo_ipress varchar(100) NULL DEFAULT NULL,
				red_salud varchar(150) NULL DEFAULT NULL,
				codigo_modulo varchar(7) NULL DEFAULT NULL,
				niveles_educativos varchar(150) NULL DEFAULT NULL,
				inicial int(11)  NULL DEFAULT NULL,
				primaria int(11)  NULL DEFAULT NULL,
				secundaria int(11)  NULL DEFAULT NULL,
				modalidad varchar(20) NULL DEFAULT NULL,
				total_EIB int(11)  NULL DEFAULT NULL,
				total_EBR int(11)  NULL DEFAULT NULL,
				forma varchar(150) NULL DEFAULT NULL,
				escolarizada int(11)  NULL DEFAULT NULL,
				no_escolarizada int(11)  NULL DEFAULT NULL,
				foreign key(importacion_id) references par_importacion(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE par_centropobladox (
    id int(11) auto_increment primary key,
				importacion_id int(11) DEFAULT NULL,
				departamento varchar(150) NULL DEFAULT NULL,
				provincia varchar(150) NULL DEFAULT NULL,
				distrito varchar(150) NULL DEFAULT NULL,
				ubigeo varchar(6) NULL DEFAULT NULL,
				ubigeo_cp varchar(10) NULL DEFAULT NULL,
				centro_poblado varchar(200) NULL DEFAULT NULL,
				tipo_administrativo varchar(150) NULL DEFAULT NULL,
				categoria varchar(150) NULL DEFAULT NULL,
				pueblo_indigena varchar(200) NULL DEFAULT NULL,
				zonautmenwgs84 varchar(20) NULL DEFAULT NULL,
				coordenadaseste double DEFAULT NULL,
				coordenadasnorte double DEFAULT NULL,
				altitud double DEFAULT NULL,
				longitud double DEFAULT NULL,
				latitud double DEFAULT NULL,
				area_residencia varchar(150) NULL DEFAULT NULL,
				total_poblacion int(11) DEFAULT NULL,
				poblacion_hombre int(11) DEFAULT NULL,
				poblacion_mujer int(11) DEFAULT NULL,
				poblacion_con_viviendas int(11) DEFAULT NULL,
				poblacion_con_agua int(11) DEFAULT NULL,
				poblacion_con_desague int(11) DEFAULT NULL,
				total_viviendas int(11) DEFAULT NULL,
				viviendas_con_agua int(11) DEFAULT NULL,
				viviendas_con_desague int(11) DEFAULT NULL,
				foreign key(importacion_id) references par_importacion(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Highcharts Drilldown Map of Peru</title>
    <script src="https://code.highcharts.com/highmaps.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/map-collections/countries/pe/pe-all.js"></script>
    <!-- Aquí podrías incluir los mapas de detalle para los departamentos -->
    <script src="https://code.highcharts.com/map-collections/countries/pe/pe-uc.js"></script> <!-- Ucayali por ejemplo -->
</head>
<body>
    <div id="container" style="height: 600px; width: 100%;"></div>

    <script>
        Highcharts.mapChart('container', {
            chart: {
                map: 'countries/pe/pe-all'
            },

            title: {
                text: 'Mapa de Perú con Drilldown'
            },

            subtitle: {
                text: 'Haga clic en un departamento para ver el detalle'
            },

            mapNavigation: {
                enabled: true,
                buttonOptions: {
                    verticalAlign: 'top'
                }
            },

            colorAxis: {
                min: 0,
                max: 1000000,
                minColor: '#E6E7E8',
                maxColor: '#005645'
            },

            series: [{
                data: [
                    ['pe-uc', 1000], // Ucayali
                    ['pe-lo', 5000], // Lima
                    // Otros departamentos...
                ],
                name: 'Población',
                states: {
                    hover: {
                        color: '#BADA55'
                    }
                },
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                },
                point: {
                    events: {
                        click: function() {
                            var drilldownMap = null;
                            if (this.drilldown) {
                                drilldownMap = 'countries/pe/' + this.drilldown;
                            }
                            this.series.chart.addSeriesAsDrilldown(this, {
                                name: this.name,
                                mapData: Highcharts.maps[drilldownMap],
                                data: [
                                    // Aquí agregarías los datos detallados del drilldown
                                    ['pe-uc-ucayali', 500],
                                    ['pe-uc-coronel-portillo', 300],
                                    // Más datos...
                                ],
                                dataLabels: {
                                    enabled: true,
                                    format: '{point.name}'
                                }
                            });
                        }
                    }
                }
            }],

            drilldown: {
                series: [],
                breadcrumbs: {
                    format: '< Back to {series.name}',
                    buttonTheme: {
                        fill: '#f7f7f7',
                        padding: 8,
                        stroke: '#cccccc',
                        'stroke-width': 1
                    },
                    floating: true,
                    position: {
                        align: 'right'
                    },
                    showFullPath: false
                }
            },

            credits: {
                enabled: false
            }
        });
    </script>
</body>
</html>

UPDATE par_grupoedad
SET etapa = CASE
    WHEN edad >= 0 AND edad <= 11 THEN '1'
    WHEN edad >= 12 AND edad <= 17 THEN '2'
    WHEN edad >= 18 AND edad <= 29 THEN '3'
    WHEN edad >= 30 AND edad <= 59 THEN '4'
    WHEN edad >= 60 THEN '5'
    ELSE etapa -- Esto mantiene el valor actual si no cumple ninguna condición
END


var dataxx = {
    categoria: ['2021', '2022', '2023', '2024', '2025', '2026', '2027', '2028', '2029', '2030'],
    serie: [5, 6, 6.5, 6.2, 6.8, 7, 7.1, 6.9, 6.7, 6.4],
    }
var datax = {
    categoria: ["2021", "2022", "2023", "2024", "2025", "2026", "2027", "2028", "2029", "2030"],
    serie: [330, 334, 337, 340, 344, 347, 350, 352, 355, 358]
    }

CREATE TABLE edu_sfl_pacto02 (
    id int(11) auto_increment primary key,
				local varchar(10) DEFAULT NULL,
				ugel  varchar(100) NULL DEFAULT NULL,
				provincia  varchar(100) NULL DEFAULT NULL,
				distrito  varchar(100) NULL DEFAULT NULL,
				area  varchar(20) NULL DEFAULT NULL,
				estado int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4


DELIMITER $$
CREATE DEFINER=root@localhost PROCEDURE edu_pa_sfl_porlocal(IN v_ugel INT, IN v_provincia INT, IN v_distrito INT, IN v_estado INT)
BEGIN
	set @query='\r\n\t INSERT INTO edu_sfl_pacto02(  local, ugel, provincia, distrito, area, estado) select local, ugel, provincia, distrito, area, estado from (\r\n\t\tselect local, ugel, provincia, distrito,area,ugelid,provinciaid,distritoid,areaid, buscar_estado(local) as estado from (\r\n\t\t\tselect \r\n\t\t\t\tlocal, max(ugel_n) as ugel, max(provincia_n) as provincia, max(distrito_n) as distrito, max(distrito_n) as area,\r\n\t\t\t\tmax(ugel_id) ugelid, max(provincia_id) provinciaid, max(distrito_id) distritoid, max(area_id) areaid\r\n\t\t\tfrom (       \r\n\t\t\t\tselect \r\n\t\t\t\t\tiiee.id iiee_id, iiee.codLocal local, iiee.Ugel_id ugel_id, pv.id provincia_id, dt.id distrito_id, iiee.Area_id area_id, \r\n\t\t\t\t\tuu.nombre ugel_n, pv.nombre provincia_n, dt.nombre distrito_n, aa.nombre area_n \r\n\t\t\t\tfrom edu_institucionEducativa as iiee \r\n\t\t\t\tinner join edu_centropoblado as cp on cp.id = iiee.CentroPoblado_id \r\n\t\t\t\tinner join edu_area as aa on aa.id = iiee.Area_id \r\n\t\t\t\tinner join edu_ugel as uu on uu.id = iiee.Ugel_id \r\n\t\t\t\tinner join par_ubigeo as dt on dt.id = cp.Ubigeo_id \r\n\t\t\t\tinner join par_ubigeo as pv on pv.id = dt.dependencia \r\n\t\t\t\twhere iiee.EstadoInsEdu_id = 3 and iiee.TipoGestion_id in (4, 5, 7, 8) and iiee.estado = "AC" and iiee.NivelModalidad_id not in (14, 15)\r\n\t\t\t) as iiee ';
	set @query=concat(@query,' group by local ) as tb order by estado )as tbx');
	set @query=concat(@query,' where 1');
	set @query=concat(@query,if(v_ugel>0     ,concat(' and ugelid=',v_ugel),''));
	set @query=concat(@query,if(v_provincia>0,concat(' and provinciaid=',v_provincia),''));
	set @query=concat(@query,if(v_distrito>0 ,concat(' and distritoid=',v_distrito),''));
	set @query=concat(@query,if(v_estado>0   ,concat(' and estado=',v_estado),''));

	prepare xxx from @query;
	execute xxx;
	deallocate prepare xxx;

END$$
DELIMITER ;


CREATE TABLE edu_cubo_pacto1_matriculados (
    id int(11) auto_increment primary key,
    anio int(11) DEFAULT NULL,
    mes varchar(100) NULL DEFAULT NULL,
    mes_id int(11) DEFAULT NULL,
    provincia varchar(100) NULL DEFAULT NULL,
    provincia_id int(11) DEFAULT NULL,
    distrito varchar(100) NULL DEFAULT NULL,
    distrito_id int(11) DEFAULT NULL,
    cod_modular varchar(10) NULL DEFAULT NULL,
    nombre_iiee varchar(100) NULL DEFAULT NULL,
    nivelmodalidad_id  int(11) DEFAULT NULL,
    nivelmodalidad_codigo varchar(3) NULL DEFAULT NULL,
    nivelmodalidad varchar(50) NULL DEFAULT NULL,
    sexo_id  int(11) DEFAULT NULL,
    sexo varchar(15) NULL DEFAULT NULL,
    edad int(11) DEFAULT NULL,
    total int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4



update par_poblacion_proyectada set codigo=(select codigo from par_ubigeo where nombre=departamento and length(codigo)=2)


update edu_matricula_general_detalle mgd inner join edu_matricula_general mg on mg.id=mgd.matriculageneral_id inner join par_anio as a on a.id=mg.anio_id set mgd.edad=TIMESTAMPDIFF(YEAR, mgd.fecha_nacimiento, concat(a.anio,'-03-31') );



CREATE TABLE salud_padron_nominal (
    id int(11) auto_increment primary key,tipo_documento  varchar(200) NULL DEFAULT NULL,
código_padron_nominal  varchar(200) NULL DEFAULT NULL,
numero_certificado  varchar(200) NULL DEFAULT NULL,
código_unico_identidad  varchar(200) NULL DEFAULT NULL,
número_documento  varchar(200) NULL DEFAULT NULL,
estado_tramite  varchar(200) NULL DEFAULT NULL,
fecha_tramite  varchar(200) NULL DEFAULT NULL,
apellido_paterno_ninio  varchar(200) NULL DEFAULT NULL,
apellido_materno_ninio  varchar(200) NULL DEFAULT NULL,
nombres_ninio  varchar(200) NULL DEFAULT NULL,
sexo_ninio  varchar(200) NULL DEFAULT NULL,
fecha_nacimiento_ninio  varchar(200) NULL DEFAULT NULL,
edad_anio  varchar(200) NULL DEFAULT NULL,
edad_ninio  varchar(200) NULL DEFAULT NULL,
eje_vial  varchar(200) NULL DEFAULT NULL,
descripcion  varchar(200) NULL DEFAULT NULL,
referencia_dirección  varchar(200) NULL DEFAULT NULL,
ubigeo_distrito  varchar(200) NULL DEFAULT NULL,
departamento  varchar(200) NULL DEFAULT NULL,
provincia  varchar(200) NULL DEFAULT NULL,
distrito  varchar(200) NULL DEFAULT NULL,
ubigeo_centro_poblado  varchar(200) NULL DEFAULT NULL,
nombre_centro_poblado  varchar(200) NULL DEFAULT NULL,
area_centro_poblado  varchar(200) NULL DEFAULT NULL,
menor_visitado  varchar(200) NULL DEFAULT NULL,
menor_encontrado  varchar(200) NULL DEFAULT NULL,
fecha_visita  varchar(200) NULL DEFAULT NULL,
fuente_datos  varchar(200) NULL DEFAULT NULL,
fecha_fuente_datos  varchar(200) NULL DEFAULT NULL,
código_eess_nacimiento  varchar(200) NULL DEFAULT NULL,
nombre_eess_nacimiento  varchar(200) NULL DEFAULT NULL,
código_eess  varchar(200) NULL DEFAULT NULL,
nombre_eess  varchar(200) NULL DEFAULT NULL,
frecuencia_atencion  varchar(200) NULL DEFAULT NULL,
código_eess_adscripción  varchar(200) NULL DEFAULT NULL,
nombre_eess_adscripción  varchar(200) NULL DEFAULT NULL,
tipo_seguro  varchar(200) NULL DEFAULT NULL,
programas_sociales_niño  varchar(200) NULL DEFAULT NULL,
codigo_institucion_educativa  varchar(200) NULL DEFAULT NULL,
nombre_institucion_ educativa  varchar(200) NULL DEFAULT NULL,
relacion_apoderado_menor  varchar(200) NULL DEFAULT NULL,
tipo_documento_madre  varchar(200) NULL DEFAULT NULL,
numero_documento_madre  varchar(200) NULL DEFAULT NULL,
apellido_paterno_madre  varchar(200) NULL DEFAULT NULL,
apellido_materno_madre  varchar(200) NULL DEFAULT NULL,
nombres_madre  varchar(200) NULL DEFAULT NULL,
celular_madre  varchar(200) NULL DEFAULT NULL,
email_madre  varchar(200) NULL DEFAULT NULL,
grado_instrucción_madre  varchar(200) NULL DEFAULT NULL,
lengua_habitual_madre  varchar(200) NULL DEFAULT NULL,
relacion_jefe_familia_menor  varchar(200) NULL DEFAULT NULL,
tipo_documento_jefe  varchar(200) NULL DEFAULT NULL,
numero_documento_jefe  varchar(200) NULL DEFAULT NULL,
apellido_paterno_jefe  varchar(200) NULL DEFAULT NULL,
apellido_materno_jefe  varchar(200) NULL DEFAULT NULL,
nombres_jefe  varchar(200) NULL DEFAULT NULL,
estado_registro  varchar(200) NULL DEFAULT NULL,
fecha_creacion_registro  varchar(200) NULL DEFAULT NULL,
usuario_crea  varchar(200) NULL DEFAULT NULL,
fecha_modificación_registro  varchar(200) NULL DEFAULT NULL,
usuario_modifica  varchar(200) NULL DEFAULT NULL,
entidad  varchar(200) NULL DEFAULT NULL,
tipo_registro  varchar(200) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4

update sal_establecimiento set codigo_unico=LPAD(cod_unico, 8, '0')
7163,4141,34032,4151,4208



CREATE TABLE sal_impor_padron_nominal (
    id int(11) auto_increment primary key,
				importacion_id int(11) DEFAULT NULL,
				padron	 varchar(100) NULL DEFAULT NULL,
				cnv	 varchar(100) NULL DEFAULT NULL,
				cui	 varchar(100) NULL DEFAULT NULL,
				dni	 varchar(100) NULL DEFAULT NULL,
				apellido_paterno	 varchar(100) NULL DEFAULT NULL,
				apellido_materno	 varchar(100) NULL DEFAULT NULL,
				nombre	 varchar(100) NULL DEFAULT NULL,
				genero	 varchar(100) NULL DEFAULT NULL,
				fecha_nacimiento	 varchar(100) NULL DEFAULT NULL,
				direccion	 varchar(200) NULL DEFAULT NULL,
				ubigeo	 varchar(100) NULL DEFAULT NULL,
				centro_poblado	 varchar(100) NULL DEFAULT NULL,
				codigo_unico_nacimiento	 varchar(100) NULL DEFAULT NULL,
				codigo_unico_atencion	 varchar(100) NULL DEFAULT NULL,
				seguro	 varchar(100) NULL DEFAULT NULL,
				tipo_doc_madre	 varchar(100) NULL DEFAULT NULL,
				num_doc_madre	 varchar(100) NULL DEFAULT NULL,
				apellido_paterno_madre	 varchar(100) NULL DEFAULT NULL,
				celular_ma	 varchar(100) NULL DEFAULT NULL,
				lengua_ma	 varchar(100) NULL DEFAULT NULL,
				visita	 varchar(100) NULL DEFAULT NULL,
				menor_encontrado	 varchar(100) NULL DEFAULT NULL,
				foreign key(importacion_id) references par_importacion(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4

CREATE TABLE sal_padron_nominal (
    id int(11) auto_increment primary key,
    importacion_id int(11) DEFAULT NULL,
	padron	varchar(12) NULL DEFAULT NULL,
	cnv	 varchar(12) NULL DEFAULT NULL,
	cui	 varchar(12) NULL DEFAULT NULL,
	dni	 varchar(12) NULL DEFAULT NULL,
	apellido_paterno	 varchar(50) NULL DEFAULT NULL,
	apellido_materno	 varchar(50) NULL DEFAULT NULL,
	nombre	 varchar(50) NULL DEFAULT NULL,
	genero	 varchar(1) NULL DEFAULT NULL,
	fecha_nacimiento	date NULL DEFAULT NULL,
    edad int NULL DEFAULT NULL,
	direccion	 varchar(200) NULL DEFAULT NULL,
	ubigeo	 varchar(6) NULL DEFAULT NULL,
	centro_poblado	 varchar(10) NULL DEFAULT NULL,
	codigo_unico_nacimiento	 varchar(6) NULL DEFAULT NULL,
	codigo_unico_atencion	 varchar(6) NULL DEFAULT NULL,
	seguro	 varchar(15) NULL DEFAULT NULL,
	tipo_doc_madre	 varchar(25) NULL DEFAULT NULL,
	num_doc_madre	 varchar(15) NULL DEFAULT NULL,
	apellido_paterno_madre	 varchar(50) NULL DEFAULT NULL,
	celular_ma	 varchar(15) NULL DEFAULT NULL,
	lengua_ma	 varchar(25) NULL DEFAULT NULL,
	visita	 int NULL DEFAULT NULL,
	menor_encontrado	int NULL DEFAULT NULL,
	foreign key(importacion_id) references par_importacion(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4

SELECT max(length(padron)),max(length(cnv)),max(length(cui)),max(length(dni)),max(length(apellido_paterno)),max(length(apellido_materno)), max(length(nombre)),max(length(genero)),max(length(fecha_nacimiento)),max(length(direccion)),max(length(ubigeo)) ,max(length(centro_poblado)),max(length(codigo_unico_nacimiento)),max(length(codigo_unico_atencion)),max(length(seguro)),max(length(tipo_doc_madre)) ,max(length(num_doc_madre)),max(length(apellido_paterno_madre)),max(length(celular_ma)),max(length(lengua_ma)) ,max(length(visita)),max(length(menor_encontrado)) FROM sal_impor_padron_nominal;

////

CREATE TABLE lotex (
    id int(11) auto_increment primary key,
				nombre	 varchar(100) NULL DEFAULT NULL,
				categoria	 varchar(100) NULL DEFAULT NULL,
				cantidad	 int NULL DEFAULT NULL,
				laboratorio	 varchar(100) NULL DEFAULT NULL,
				lote	 varchar(100) NULL DEFAULT NULL,
				vencimiento	  varchar(100) NULL DEFAULT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4


//////////////////


CREATE TABLE productoxx (
    id int(11) auto_increment primary key,
    productox varchar(100) NULL DEFAULT NULL,
    categoriax varchar(100) NULL DEFAULT NULL,
    cantidad int(11) DEFAULT NULL,
    precio varchar(100) NULL DEFAULT NULL,
    laboratoriox varchar(10) NULL DEFAULT NULL,
    lotex varchar(100) NULL DEFAULT NULL,
    vencimiento varchar(3) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4

////////////////////////////////


update edu_sfl set zona_registral='ZONA N° VI - OFICINA REGISTRAL DE PUCALLPA';


ALTER TABLE edu_sfl ADD anotacion INT NOT NULL AFTER zona_registral;


// sirve para enumerar repetidos en un consulta
select * from (SELECT id, importacion_id, padron, num_doc, tipo_doc, ROW_NUMBER() OVER(PARTITION BY tipo_doc, num_doc ORDER BY id) AS repetido FROM sal_impor_padron_nominal where importacion_id=2703 ORDER BY repetido DESC) as tb where num_doc='93939106';

SELECT num_doc, COUNT(*) AS cantidad_repetidos FROM sal_impor_padron_nominal where importacion_id=2708 GROUP BY num_doc HAVING cantidad_repetidos > 2;



CREATE TABLE sal_calidad_criterio_nombres (
    id int(11) auto_increment primary key,
    nombre varchar(100) NULL DEFAULT NULL,
    estado int(11) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4

               (1,'REGISTRO DE NIÑOS Y NIÑAS SIN NÚMERO DE DOCUMENTO (DNI, CNV, CUI)','0'),
               (2, 'REGISTRO DE NIÑOS Y NIÑAS DUPLICADOS DEL NÚMERO DE DOCUMENTO','0' ),
               (3,'REGISTRO DE NIÑOS Y NIÑAS SIN NOMBRE COMPLETOS','0' ),
               (4,'REGISTRO DE NIÑOS Y NIÑAS SIN SEGURO DE SALUD','0' ),
               (5,'REGISTRO DE NIÑOS Y NIÑAS SIN VISITAS DOMICILIARIAS','0' ),
               (6,'REGISTRO DE NIÑOS Y NIÑAS VISITADOS Y NO ENCONTRADOS','0' ),
               (7,'REGISTRO DE NIÑOS Y NIÑAS SIN ESTABLECIMIENTO DE ATENCIÓN','0' ),
               (8,'REGISTRO DE NIÑOS Y NIÑAS CON ESTABLECIMIENTO DE ATENCIÓN DE OTRA REGIÓN','0' ),
               (9,'REGISTRO DE NIÑOS Y NIÑAS CON ESTABLECIMIENTO DE SALUD  DE OTRO DISTRITO','0' ),
               (10,'REGISTRO DE NIÑOS Y NIÑAS SIN NOMBRES COMPLETO DE LA MADRE ','0' ),
               (11,'REGISTRO DE NIÑOS Y NIÑAS SIN GRADO DE INSTRUCCIÓN DE LA MADRE ','0' ),
               (12,'REGISTRO DE NIÑOS Y NIÑAS SIN LENGUA HABITUAL DE LA MADRE ','0' ),
               (13,'REGISTRO DE NIÑOS Y NIÑAS SIN CELULAR DE LA MADRE ','0' ),


/////////////////////////
cambios sismore 
ALTER TABLE adm_usuario ADD creado INT NULL DEFAULT NULL AFTER codigo_institucion;



ALTER TABLE sal_calidad_criterio_nombres ADD pos INT NULL DEFAULT NULL AFTER nombre;
update sal_calidad_criterio_nombres set pos=id+1;



CREATE TABLE sal_cubo_pacto1_padron_nominal (
    id int(11) auto_increment primary key,
    importacion int(11) NULL DEFAULT NULL,
anio int(11) NULL DEFAULT NULL,
mes int(11) NULL DEFAULT NULL,
tipo_doc varchar(10) NULL DEFAULT NULL,
num_doc varchar(20) NULL DEFAULT NULL,
nombre_completo varchar(200) NULL DEFAULT NULL,
fecha_nacimiento date NULL DEFAULT NULL,
edad int(11) NULL DEFAULT NULL,
tipo_edad varchar(2) NULL DEFAULT NULL,
direccion varchar(200) NULL DEFAULT NULL,
distrito_id int(11) NULL DEFAULT NULL,
provincia_id int(11) NULL DEFAULT NULL,
seguro varchar(50) NULL DEFAULT NULL,
cui_atencion int(11) NULL DEFAULT NULL,
nombre_establecimiento varchar(200) NULL DEFAULT NULL,
num_doc_madre varchar(20) NULL DEFAULT NULL,
nombre_completo_madre varchar(200) NULL DEFAULT NULL,
critero01 int(11) NULL DEFAULT NULL,
critero02 int(11) NULL DEFAULT NULL,
critero03 int(11) NULL DEFAULT NULL,
critero04 int(11) NULL DEFAULT NULL,
critero05 int(11) NULL DEFAULT NULL,
critero06 int(11) NULL DEFAULT NULL,
critero07 int(11) NULL DEFAULT NULL,
critero08 int(11) NULL DEFAULT NULL,
critero09 int(11) NULL DEFAULT NULL,
critero10 int(11) NULL DEFAULT NULL,
num int(11) NULL DEFAULT NULL,
den int(11) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
ALTER TABLE sal_cubo_pacto1_padron_nominal ADD distrito VARCHAR(50) NULL DEFAULT NULL AFTER provincia_id;
ALTER TABLE sal_cubo_pacto1_padron_nominal ADD lengua_madre VARCHAR(100) NULL DEFAULT NULL AFTER nombre_completo_madre;


CREATE TABLE sal_cubo_pacto3_padron_materno (
    id int(11) auto_increment primary key,
    importacion_id int(11) NULL DEFAULT NULL,
				anio int(11) NULL DEFAULT NULL,
				mes int(11) NULL DEFAULT NULL,
				num_doc varchar(10) NULL DEFAULT NULL,
				fecha_parto varchar(10) NULL DEFAULT NULL,
				semana_nac int(11) NULL DEFAULT NULL,
				gest_37sem int(11) NULL DEFAULT NULL,
				codigo_unico int(11) NULL DEFAULT NULL,
				red varchar(100) NULL DEFAULT NULL,
				microred varchar(100) NULL DEFAULT NULL,
				eess_parto varchar(100) NULL DEFAULT NULL,
				provincia varchar(100) NULL DEFAULT NULL,
				ubigeo_distrito varchar(6) NULL DEFAULT NULL,
				distrito varchar(100) NULL DEFAULT NULL,
				denominador int(11) NULL DEFAULT NULL,
				numerador int(11) NULL DEFAULT NULL,
				num_exam_hb int(11) NULL DEFAULT NULL,
				num_exam_sifilis int(11) NULL DEFAULT NULL,
				num_exam_vih int(11) NULL DEFAULT NULL,
				num_exam_bacteriuria int(11) NULL DEFAULT NULL,
				num_perfil_obstetrico int(11) NULL DEFAULT NULL,
				num_exam_aux int(11) NULL DEFAULT NULL,
				num_apn1_1trim int(11) NULL DEFAULT NULL,
				num_apn1_2trim int(11) NULL DEFAULT NULL,
				num_apn2_2trim int(11) NULL DEFAULT NULL,
				num_apn1_3trim int(11) NULL DEFAULT NULL,
				num_apn2_3trim int(11) NULL DEFAULT NULL,
				num_apn3_3trim int(11) NULL DEFAULT NULL,
				num_apn int(11) NULL DEFAULT NULL,
				num_entrega1_sfaf int(11) NULL DEFAULT NULL,
				num_entrega2_sfaf int(11) NULL DEFAULT NULL,
				num_entrega3_sfaf int(11) NULL DEFAULT NULL,
				num_entrega4_sfaf int(11) NULL DEFAULT NULL,
				num_entrega5_sfaf int(11) NULL DEFAULT NULL,
				num_entrega_sfaf int(11) NULL DEFAULT NULL,
				foreign key(importacion_id) references par_importacion(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4

ALTER TABLE sal_cubo_pacto3_padron_materno ADD didstrito_id INT NULL DEFAULT NULL AFTER distrito, ADD provincia_id INT NULL DEFAULT NULL AFTER didstrito_id;


INSERT INTO par_indicador_general_meta (indicadorgeneral, periodo, distrito, anio_base, valor_base, anio, valor, created_at, updated_at) VALUES
( 37, '', 36, 2022, '0', 2024, '87.7', '2024-11-12 16:31:31', '2024-11-12 16:31:31'),
( 37, '', 37, 2022, '0', 2024, '92.7', '2024-11-12 16:31:31', '2024-11-12 16:31:31'),
( 37, '', 38, 2022, '0', 2024, '96.3', '2024-11-12 16:31:31', '2024-11-12 16:31:31'),
( 37, '', 39, 2022, '0', 2024, '89.7', '2024-11-12 16:31:31', '2024-11-12 16:31:31'),
( 37, '', 40, 2022, '0', 2024, '62.4', '2024-11-12 16:31:31', '2024-11-12 16:31:31'),
( 37, '', 41, 2022, '0', 2024, '78.6', '2024-11-12 16:31:31', '2024-11-12 16:31:31'),
( 37, '', 42, 2022, '0', 2024, '84.1', '2024-11-12 16:31:31', '2024-11-12 16:31:31'),
( 37, '', 44, 2022, '0', 2024, '86.0', '2024-11-12 16:31:31', '2024-11-12 16:31:31'),
( 37, '', 45, 2022, '0', 2024, '74.6', '2024-11-12 16:31:31', '2024-11-12 16:31:31'),
( 37, '', 46, 2022, '0', 2024, '85.3', '2024-11-12 16:31:31', '2024-11-12 16:31:31'),
( 37, '', 47, 2022, '0', 2024, '38.6', '2024-11-12 16:31:31', '2024-11-12 16:31:31'),
( 37, '', 49, 2022, '0', 2024, '82.4', '2024-11-12 16:31:31', '2024-11-12 16:31:31'),
( 37, '', 50, 2022, '0', 2024, '78.7', '2024-11-12 16:31:31', '2024-11-12 16:31:31'),
( 37, '', 51, 2022, '0', 2024, '78.2', '2024-11-12 16:31:31', '2024-11-12 16:31:31'),
( 37, '', 52, 2022, '0', 2024, '44.5', '2024-11-12 16:31:31', '2024-11-12 16:31:31'),
( 37, '', 53, 2022, '0', 2024, '63.6', '2024-11-12 16:31:31', '2024-11-12 16:31:31'),
( 37, '', 56, 2022, '0', 2024, '94.0', '2024-11-12 16:31:31', '2024-11-12 16:31:31'),
( 37, '', 57, 2022, '0', 2024, '93.3', '2024-11-12 16:31:31', '2024-11-12 16:31:31'),
( 37, '', 55, 2022, '0', 2024, '84.0', '2024-11-12 16:31:31', '2024-11-12 16:31:31');

CREATE TABLE sal_cubo_pacto4_padron_12meses (
    id int(11) auto_increment primary key,
    importacion_id int(11) NULL DEFAULT NULL,
				anio int(11) NULL DEFAULT NULL,
				mes int(11) NULL DEFAULT NULL,
				codigo_disa int(11) NULL DEFAULT NULL,
				codigo_red int(11) NULL DEFAULT NULL,
				codigo_unico int(11) NULL DEFAULT NULL,
				tipo_documento varchar(20) NULL DEFAULT NULL,
				numero_documento_identidad varchar(20) NULL DEFAULT NULL,
				nombre_nino varchar(200) NULL DEFAULT NULL,
				tipo_seguro varchar(50) NULL DEFAULT NULL,
				fecha_nacimiento date NULL DEFAULT NULL,
				edad_mes int(11) NULL DEFAULT NULL,
				edad_dias int(11) NULL DEFAULT NULL,
				fecha_inicio date NULL DEFAULT NULL,
				fecha_final date NULL DEFAULT NULL,
				num_dni30d int(11) NULL DEFAULT NULL,
				num_dni60d int(11) NULL DEFAULT NULL,
				num_cred_rn int(11) NULL DEFAULT NULL,
				num_cred_mensual int(11) NULL DEFAULT NULL,
				cumple_cred int(11) NULL DEFAULT NULL,
				num_neumo int(11) NULL DEFAULT NULL,
				num_rota int(11) NULL DEFAULT NULL,
				num_polio int(11) NULL DEFAULT NULL,
				num_penta int(11) NULL DEFAULT NULL,
				cumple_vacuna int(11) NULL DEFAULT NULL,
				cumple_esq_4m int(11) NULL DEFAULT NULL,
				cumple_esq_6m int(11) NULL DEFAULT NULL,
				cumple_suplemento int(11) NULL DEFAULT NULL,
				cumple_dosaje_hb int(11) NULL DEFAULT NULL,
				cumple_dni_enitido_30d int(11) NULL DEFAULT NULL,
				cumple_dni_enitido_60d int(11) NULL DEFAULT NULL,
				den int(11) NULL DEFAULT NULL,
				num int(11) NULL DEFAULT NULL,
				numero_documento_madre varchar(20) NULL DEFAULT NULL,
				nombre_madre varchar(200) NULL DEFAULT NULL,
				nrocel_madre varchar(20) NULL DEFAULT NULL,
				ubigeo varchar(6) NULL DEFAULT NULL,
				provincia varchar(200) NULL DEFAULT NULL,
				distrito varchar(200) NULL DEFAULT NULL,
				red varchar(200) NULL DEFAULT NULL,
				microred varchar(200) NULL DEFAULT NULL,
				eess varchar(200) NULL DEFAULT NULL,
				foreign key(importacion_id) references par_importacion(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4

CREATE TABLE sal_cubo_pacto4_padron_12meses (
    id int(11) auto_increment primary key,
    importacion_id int(11) NULL DEFAULT NULL,
				anio int(11) NULL DEFAULT NULL,
				mes int(11) NULL DEFAULT NULL,
				mesl varchar(20) NULL DEFAULT NULL,
				tipo_doc varchar(20) NULL DEFAULT NULL,
				num_doc varchar(20) NULL DEFAULT NULL,
				fecha_nac date NULL DEFAULT NULL,
				sexo varchar(20) NULL DEFAULT NULL,
				ubigeo varchar(6) NULL DEFAULT NULL,
				seguro varchar(10) NULL DEFAULT NULL,
				edad_dias int(11) NULL DEFAULT NULL,
				edad_mes int(11) NULL DEFAULT NULL,
				peso_cnv int(11) NULL DEFAULT NULL,
				semana_gest_cnv int(11) NULL DEFAULT NULL,
				denominador int(11) NULL DEFAULT NULL,
				numerador int(11) NULL DEFAULT NULL,
				numerador_sindni int(11) NULL DEFAULT NULL,
				num_cred int(11) NULL DEFAULT NULL,
				num_cred_rn int(11) NULL DEFAULT NULL,
				fecha_cred_rn1 date NULL DEFAULT NULL,
				fecha_cred_rn2 date NULL DEFAULT NULL,
				fecha_cred_rn3 date NULL DEFAULT NULL,
				fecha_cred_rn4 date NULL DEFAULT NULL,
				num_cred_mensual int(11) NULL DEFAULT NULL,
				fecha_cred_mes1 date NULL DEFAULT NULL,
				fecha_cred_mes2 date NULL DEFAULT NULL,
				fecha_cred_mes3 date NULL DEFAULT NULL,
				fecha_cred_mes4 date NULL DEFAULT NULL,
				fecha_cred_mes5 date NULL DEFAULT NULL,
				fecha_cred_mes6 date NULL DEFAULT NULL,
				fecha_cred_mes7 date NULL DEFAULT NULL,
				fecha_cred_mes8 date NULL DEFAULT NULL,
				fecha_cred_mes9 date NULL DEFAULT NULL,
				fecha_cred_mes10 date NULL DEFAULT NULL,
				fecha_cred_mes11 date NULL DEFAULT NULL,
				num_vac int(11) NULL DEFAULT NULL,
				num_vac_antineumococica int(11) NULL DEFAULT NULL,
				fecha_vac_antineumococica1 date NULL DEFAULT NULL,
				fecha_vac_antineumococica2 date NULL DEFAULT NULL,
				num_vac_antipolio int(11) NULL DEFAULT NULL,
				fecha_vac_antipolio1 date NULL DEFAULT NULL,
				fecha_vac_antipolio2 date NULL DEFAULT NULL,
				fecha_vac_antipolio3 date NULL DEFAULT NULL,
				num_vac_pentavalente int(11) NULL DEFAULT NULL,
				fecha_vac_pentavalente1 date NULL DEFAULT NULL,
				fecha_vac_pentavalente2 date NULL DEFAULT NULL,
				fecha_vac_pentavalente3 date NULL DEFAULT NULL,
				num_vac_rotavirus int(11) NULL DEFAULT NULL,
				fecha_vac_rotavirus1 date NULL DEFAULT NULL,
				fecha_vac_rotavirus2 date NULL DEFAULT NULL,
				num_esq int(11) NULL DEFAULT NULL,
				num_esq4m int(11) NULL DEFAULT NULL,
				fecha_esq4m_sup_e1 date NULL DEFAULT NULL,
				num_esq6m int(11) NULL DEFAULT NULL,
				num_esq6m_sup int(11) NULL DEFAULT NULL,
				fecha_esq6m_sup_e1 date NULL DEFAULT NULL,
				fecha_esq6m_sup_e2 date NULL DEFAULT NULL,
				num_esq6m_trat int(11) NULL DEFAULT NULL,
				fecha_esq6m_trat_e1 date NULL DEFAULT NULL,
				fecha_esq6m_trat_e2 date NULL DEFAULT NULL,
				fecha_esq6m_trat_e3 date NULL DEFAULT NULL,
				num_esq6m_multi int(11) NULL DEFAULT NULL,
				fecha_esq6m_multi_e1 date NULL DEFAULT NULL,
				fecha_esq6m_multi_e2 date NULL DEFAULT NULL,
				fecha_esq6m_multi_e3 date NULL DEFAULT NULL,
				fecha_esq6m_multi_e4 date NULL DEFAULT NULL,
				fecha_esq6m_multi_e5 date NULL DEFAULT NULL,
				fecha_esq6m_multi_e6 date NULL DEFAULT NULL,
				num_hb int(11) NULL DEFAULT NULL,
				fecha_hb date NULL DEFAULT NULL,
				num_dniemision int(11) NULL DEFAULT NULL,
				fecha_dniemision date NULL DEFAULT NULL,
				num_dniemision_30d int(11) NULL DEFAULT NULL,
				num_dniemision_60d int(11) NULL DEFAULT NULL,
				cod_unico int(11) NULL DEFAULT NULL,
				eess varchar(100) NULL DEFAULT NULL,
				departamento varchar(100) NULL DEFAULT NULL,
				provincia varchar(100) NULL DEFAULT NULL,
				distrito varchar(100) NULL DEFAULT NULL,
				establecimiento_id int(11) NULL DEFAULT NULL,
				provincia_id int(11) NULL DEFAULT NULL,
				distrito_id int(11) NULL DEFAULT NULL,
				foreign key(importacion_id) references par_importacion(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4





CREATE TABLE sal_directorio_pn (
    id int(11) auto_increment primary key,
				dni varchar(20) NULL DEFAULT NULL,
				nombres varchar(100) NULL DEFAULT NULL,
				apellido_paterno varchar(100) NULL DEFAULT NULL,
				apellido_materno varchar(100) NULL DEFAULT NULL,
				profesion varchar(100) NULL DEFAULT NULL,
				cargo varchar(100) NULL DEFAULT NULL,
				condicion_laboral varchar(100) NULL DEFAULT NULL,
				red_id int NULL DEFAULT NULL,
				microred_id int NULL DEFAULT NULL,
				establecimiento_id int NULL DEFAULT NULL,
				celular varchar(100) NULL DEFAULT NULL,
				email varchar(100) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4

CREATE TABLE sal_directorio_municipal (
    id int(11) auto_increment primary key,
				dni varchar(20) NULL DEFAULT NULL,
				nombres varchar(100) NULL DEFAULT NULL,
				apellido_paterno varchar(100) NULL DEFAULT NULL,
				apellido_materno varchar(100) NULL DEFAULT NULL,
				profesion varchar(100) NULL DEFAULT NULL,
				cargo varchar(100) NULL DEFAULT NULL,
				condicion_laboral varchar(100) NULL DEFAULT NULL,
				distrito_id int NULL DEFAULT NULL,
				celular varchar(100) NULL DEFAULT NULL,
				email varchar(100) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4


ALTER TABLE adm_usuario CHANGE creado usuario_creador INT(11) NULL DEFAULT NULL;
"ALTER TABLE adm_usuario DROP usuario_creador;"?

CREATE TABLE adm_usuario_auditoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NULL,
    accion ENUM('CREADO', 'MODIFICADO', 'ELIMINADO') NOT NULL,
    datos_anteriores JSON NULL,
    datos_nuevos JSON NULL,
    usuario_responsable INT NULL,
				created_at timestamp NOT NULL DEFAULT current_timestamp(),
  		updated_at timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
);


/* modificar sal_impor_padron_establecimiento */

CREATE TABLE sal_impor_padron_establecimiento (
  id int AUTO_INCREMENT PRIMARY KEY,
  importacion_id int(11) DEFAULT NULL,
		cod_unico int(11) DEFAULT NULL,
		nombre_establecimiento varchar(120) DEFAULT NULL,
		responsable varchar(100) DEFAULT NULL,
		direccion varchar(300) DEFAULT NULL,
		ruc varchar(11) DEFAULT NULL,
		ubigeo varchar(6) DEFAULT NULL,
		telefono varchar(60) DEFAULT NULL,
		horario varchar(60) DEFAULT NULL,
		inicio_actividad date DEFAULT NULL,
		categoria varchar(7) DEFAULT NULL,
		estado varchar(6) DEFAULT NULL,
		institucion varchar(100) DEFAULT NULL,
		clasificacion_eess varchar(300) DEFAULT NULL,
		tipo_eess varchar(100) DEFAULT NULL,
		sec_ejec int(11) DEFAULT NULL,
		cod_disa int(11) DEFAULT NULL,
		disa varchar(15) DEFAULT NULL,
		cod_red varchar(2) DEFAULT NULL,
		red varchar(100) DEFAULT NULL,
		cod_microrred varchar(2) DEFAULT NULL,
		microrred varchar(100) DEFAULT NULL,
		latitud double DEFAULT NULL,
		longitud double DEFAULT NULL,
		foreign key (importacion_id) references par_importacion(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `sal_establecimiento` 
ADD `red_id` INT NULL DEFAULT NULL AFTER `disa`;

ALTER TABLE `sal_establecimiento` 
ADD `cod_red` VARCHAR(2) NULL DEFAULT NULL AFTER `disa`, 
ADD `red` VARCHAR(100) NULL DEFAULT NULL AFTER `cod_red`, 
ADD `cod_microrred` VARCHAR(2) NULL DEFAULT NULL AFTER `red`, 
ADD `microrred` VARCHAR(100) NULL DEFAULT NULL AFTER `cod_microrred`;

ALTER TABLE `sal_establecimiento` 
ADD `ruc` VARCHAR(11) NULL DEFAULT NULL AFTER `direccion`;


ALTER TABLE `sal_establecimiento` 
CHANGE `cod_disa` `cod_disa` INT NULL DEFAULT NULL;

ALTER TABLE `sal_establecimiento` 
CHANGE `norte` `latitud` DOUBLE NULL DEFAULT NULL, 
CHANGE `este` `longitud` DOUBLE NULL DEFAULT NULL;





/*  sal_establecimiento */



ALTER TABLE sal_establecimiento ADD ruc VARCHAR(11) NULL DEFAULT NULL AFTER direccion;

ALTER TABLE sal_establecimiento
  DROP cota,
  DROP camas;

ALTER TABLE sal_establecimiento CHANGE norte latitud DOUBLE NULL DEFAULT NULL, CHANGE este longitud DOUBLE NULL DEFAULT NULL;

ALTER TABLE sal_establecimiento
  DROP doc_categorizacion,
  DROP numero_documento;

ALTER TABLE sal_establecimiento CHANGE clasificacion_eess clasificacion_eess VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, CHANGE disa disa INT NOT NULL;
ALTER TABLE sal_establecimiento ADD sec_ejec INT NOT NULL AFTER tipo_eess;

ALTER TABLE sal_establecimiento CHANGE responsable responsable VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;





/*  */


CREATE TABLE `adm_directorios_auditoria` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `responsable_id` int(11) DEFAULT NULL,
  `tipo` enum('PADRON_NOMINAL','MUNICIPIOS') NOT NULL,
  `accion` enum('CREADO','MODIFICADO','ELIMINADO') NOT NULL,
  `datos_anteriores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_anteriores`)),
  `datos_nuevos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_nuevos`)),
  `usuario_responsable` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/*  */


CREATE TABLE edu_impor_padronweb (
  id int AUTO_INCREMENT PRIMARY KEY,
  importacion_id int(11) DEFAULT NULL,
		cod_mod int(11) DEFAULT NULL,
		cod_local int(11) DEFAULT NULL,
		institucion_educativa varchar(62) DEFAULT NULL,
		cod_nivelmod varchar(2) DEFAULT NULL,
		nivel_modalidad varchar(39) DEFAULT NULL,
		forma varchar(15) DEFAULT NULL,
		cod_car varchar(1) DEFAULT NULL,
		caracteristica varchar(22) DEFAULT NULL,
		cod_genero int(11) DEFAULT NULL,
		genero varchar(7) DEFAULT NULL,
		cod_gest varchar(1) DEFAULT NULL,
		gestion varchar(26) DEFAULT NULL,
		cod_ges_dep varchar(2) DEFAULT NULL,
		gestion_dependencia varchar(32) DEFAULT NULL,
		director varchar(43) DEFAULT NULL,
		telefono varchar(23) DEFAULT NULL,
		direccion_centro_educativo varchar(66) DEFAULT NULL,
		ubigeo_ccpp varchar(10) DEFAULT NULL,
		cod_ccpp varchar(6) DEFAULT NULL,
		centro_poblado varchar(40) DEFAULT NULL,
		cod_area varchar(1) DEFAULT NULL,
		area_geografica varchar(6) DEFAULT NULL,
		ubigeo varchar(6) DEFAULT NULL,
		provincia varchar(16) DEFAULT NULL,
		distrito varchar(22) DEFAULT NULL,
		dre varchar(11) DEFAULT NULL,
		cod_ugel varchar(6) DEFAULT NULL,
		ugel varchar(21) DEFAULT NULL,
		nlat_ie double DEFAULT NULL,
		nlong_ie double DEFAULT NULL,
		cod_tur int(11) DEFAULT NULL,
		turno varchar(18) DEFAULT NULL,
		cod_estado int(11) DEFAULT NULL,
		estado varchar(8) DEFAULT NULL,
		talum_hom int(11) DEFAULT NULL,
		talum_muj int(11) DEFAULT NULL,
		talumno int(11) DEFAULT NULL,
		tdocente int(11) DEFAULT NULL,
		tseccion int(11) DEFAULT NULL,
		fechareg date DEFAULT NULL,
		foreign key (importacion_id) references par_importacion(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;




campo
cod_mod
cod_local
institucion_educativa
cod_nivelmod
nivel_modalidad
forma
cod_car
caracteristica
cod_genero
genero
cod_gest
gestion
cod_ges_dep
gestion_dependencia
director
telefono
direccion_centro_educativo
ubigeo_ccpp
cod_ccpp
centro_poblado
cod_area
area_geografica
ubigeo
provincia
distrito
dre
cod_ugel
ugel
nlat_ie
nlong_ie
cod_tur
turno
cod_estado
estado
talum_hom
talum_muj
talumno
tdocente
tseccion
fechareg



ALTER TABLE `edu_institucioneducativa` ADD `fecha_reg` DATE NULL DEFAULT NULL AFTER `es_eib`;


ALTER TABLE `edu_sfl` ADD `estado_iiee` ENUM('0','1') NOT NULL AFTER `documento`;
ALTER TABLE `edu_sfl` CHANGE `estado_servicio` `estado_servicio` TINYINT NOT NULL DEFAULT '0';



CREATE TABLE sal_impor_padron_programa (
  id INT AUTO_INCREMENT PRIMARY KEY,
  importacion_id INT DEFAULT NULL,
  usuariou INT DEFAULT NULL,
  programa INT DEFAULT NULL,
  servicio VARCHAR(6) DEFAULT NULL,
  anio INT DEFAULT NULL,
  mes INT DEFAULT NULL,
  tipo_doc VARCHAR(40) DEFAULT NULL,
  num_doc_m VARCHAR(12) DEFAULT NULL,
  ape_pat_m VARCHAR(20) DEFAULT NULL,
  ape_mat_m VARCHAR(20) DEFAULT NULL,
  nombre_m VARCHAR(30) DEFAULT NULL,
  sexo INT DEFAULT NULL,
  fec_nac_m DATE DEFAULT NULL,
  telefono VARCHAR(15) DEFAULT NULL,
  direccion VARCHAR(70) DEFAULT NULL,
  referencia VARCHAR(70) DEFAULT NULL,
  ubigeo_distrito VARCHAR(6) DEFAULT NULL,
  ubigeo_ccpp VARCHAR(10) DEFAULT NULL,
  latitud DOUBLE DEFAULT NULL,
  longitud DOUBLE DEFAULT NULL,
  num_doc_a VARCHAR(12) DEFAULT NULL,
  ape_pat_a VARCHAR(20) DEFAULT NULL,
  ape_mat_a VARCHAR(20) DEFAULT NULL,
  nombre_a VARCHAR(30) DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_importacion (importacion_id),
  INDEX idx_usuariou (usuariou),
  FOREIGN KEY (importacion_id) REFERENCES par_importacion(id),
  FOREIGN KEY (usuariou) REFERENCES adm_usuario(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE sal_padron_programa_h (
  id INT AUTO_INCREMENT PRIMARY KEY,
  importacion_id INT DEFAULT NULL,
  programa INT DEFAULT NULL,
  servicio VARCHAR(6) DEFAULT NULL,
  anio INT DEFAULT NULL,
  mes INT DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_importacion (importacion_id),
  FOREIGN KEY (importacion_id) REFERENCES par_importacion(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE sal_padron_programa_b (
  id INT AUTO_INCREMENT PRIMARY KEY,
  importacion_id INT DEFAULT NULL,
  tipo_doc VARCHAR(3) DEFAULT NULL,
  num_doc_m VARCHAR(8) DEFAULT NULL,
  ape_pat_m VARCHAR(30) DEFAULT NULL,
  ape_mat_m VARCHAR(30) DEFAULT NULL,
  nombre_m VARCHAR(40) DEFAULT NULL,
  sexo INT DEFAULT NULL,
  fec_nac_m DATE DEFAULT NULL,
  telefono VARCHAR(9) DEFAULT NULL,
  direccion TEXT DEFAULT NULL,
  referencia TEXT DEFAULT NULL,
  ubigeo VARCHAR(6) DEFAULT NULL,
  ubigeo_ccpp VARCHAR(10) DEFAULT NULL,
  latitud DOUBLE DEFAULT NULL,
  longitud DOUBLE DEFAULT NULL,
  num_doc_a VARCHAR(12) DEFAULT NULL,
  ape_pat_a VARCHAR(25) DEFAULT NULL,
  ape_mat_a VARCHAR(35) DEFAULT NULL,
  nombre_a VARCHAR(40) DEFAULT NULL,
  INDEX idx_importacion (importacion_id),
  FOREIGN KEY (importacion_id) REFERENCES par_importacion(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `edu_institucioneducativa` ADD `modo_registro` INT NOT NULL AFTER `fecha_reg`;


CREATE TABLE edu_impor_padron_nominal (
  id INT AUTO_INCREMENT PRIMARY KEY,
  importacion_id INT DEFAULT NULL,
		cod_mod VARCHAR(7) DEFAULT NULL,
		modalidad VARCHAR(3) DEFAULT NULL,
		cod_estudiante VARCHAR(14) DEFAULT NULL,
		dni VARCHAR(8) DEFAULT NULL,
		validacion_dni VARCHAR(19) DEFAULT NULL,
		apellido_paterno VARCHAR(40) DEFAULT NULL,
		apellido_materno VARCHAR(40) DEFAULT NULL,
		nombres VARCHAR(60) DEFAULT NULL,
		sexo VARCHAR(6) DEFAULT NULL,
		nacionalidad VARCHAR(40) DEFAULT NULL,
		fecha_nacimiento VARCHAR(10) DEFAULT NULL,
		lengua_materna VARCHAR(50) DEFAULT NULL,
		grado VARCHAR(30) DEFAULT NULL,
		seccion VARCHAR(40) DEFAULT NULL,
		fecha_matricula VARCHAR(10) DEFAULT NULL,
		sr_regular VARCHAR(50) DEFAULT NULL,
		sf_recuperacion VARCHAR(40) DEFAULT NULL,
  INDEX idx_importacion (importacion_id),
  FOREIGN KEY (importacion_id) REFERENCES par_importacion(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE 	edu_cubo_fed_pn (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Campo id autoincremental
    importacion_id INT,
				anio INT,
				mes INT,
    dni VARCHAR(20),
    apellido_paterno VARCHAR(50),
    apellido_materno VARCHAR(50),
    nombre VARCHAR(100),
    sexo VARCHAR(10),
    fecha_nacimiento DATE,
    edad INT,
    tipo_edad VARCHAR(1),
    direccion VARCHAR(255),
    ubigeo VARCHAR(10),
    centro_poblado VARCHAR(20),
    centro_poblado_nombre VARCHAR(100),
    area_ccpp VARCHAR(20),
    codigo_ie VARCHAR(20),
    nombre_ie VARCHAR(100),
    tipo_doc_madre VARCHAR(20),
    num_doc_madre VARCHAR(20),
    apellido_paterno_madre VARCHAR(50),
    apellido_materno_madre VARCHAR(50),
    nombres_madre VARCHAR(100),
    celular_madre VARCHAR(20),
    grado_instruccion VARCHAR(50),
    lengua_madre VARCHAR(50),
    distrito_id INT,
    distrito VARCHAR(50),
    dependencia VARCHAR(50),
    provincia VARCHAR(50),
    ugel VARCHAR(100),
    cod_mod VARCHAR(20),
    den INT,
    num INT,
    numx INT
);


ALTER TABLE `edu_impor_matricula_general` CHANGE `id_anio` `anio` INT(11) NULL DEFAULT NULL;

eliminar estado_matricula


ALTER TABLE `sal_cubo_pacto1_padron_nominal` ADD `departamento` VARCHAR(20) NULL DEFAULT NULL AFTER `provincia_id`, ADD `provincia` VARCHAR(30) NULL DEFAULT NULL AFTER `departamento`;
ALTER TABLE `sal_cubo_pacto1_padron_nominal` ADD `ubigeo_cp` VARCHAR(10) NULL DEFAULT NULL AFTER `distrito`, ADD `centro_poblado` VARCHAR(60) NOT NULL AFTER `ubigeo_cp`;
ALTER TABLE `sal_cubo_pacto1_padron_nominal` CHANGE `centro_poblado` `centro_poblado` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;


= Table.ExpandTableColumn(#"Filas filtradas1", "Transformar archivo", {"RENIPRESS,C,10", "E_UBIG,C,6", "COD_DISA,C,2", "COD_RED,C,2", "COD_MRED,C,2", "FECATE,D", "HORATE,C,5", "NUMHC,C,15", 
"DOC_IDEN,C,11", "ETNIA,C,2", "FINANCIA,C,2", "SEXO,C,1", "EDAD,N,3,0", "TIPOEDAD,C,1", "NOMB,C,50", "APELL,C,50", "DIRECC,C,100", "UBIG_RESHA,C,6", "UBIG_PROCE,C,6", "ACOMPANA,C,50", 
"ADOC_IDEN,C,11", "MOTATEN,C,2", "SITOCURREN,C,6", "UPS,C,6", "CODDIAG1,C,5", "TIPDIAG1,C,1", "CODDIAG2,C,5", "TIPDIAG2,C,1", "CODDIAG3,C,5", "TIPDIAG3,C,1", "CODDIAG4,C,5", "TIPDIAG4,C,1", 
"CODCPT1,C,5", "CODCPT2,C,5", "CODCPT3,C,5", "CODCPT4,C,5", "CONDICION,C,1", "FECEGR,D", "HOREGR,C,5", "DESTINO,C,2", "DES_EESS,C,10", "DES_UPS,C,6", "CODPSAL,C,11", "OBSERV,N,1,0", "OFECING,D", 
"OHORING,C,5", "OFECEGR,D", "OHOREGR,C,5", "TOTALEST,N,3,0", "OCAMA,C,10", "OCODPSAL,C,11", "OCODDIAG1,C,5", "OCODDIAG2,C,5", "FECHAREG,D", "ESTADO,N,1,0", "PRIORIDAD,N,1,0", "FECHAREG,C,10", 
"PRIORIDAD,C,1", "OBSERV,C,1", "PRIORIDAD,C,15"}, 
{"RENIPRESS,C,10", "E_UBIG,C,6", "COD_DISA,C,2", "COD_RED,C,2", "COD_MRED,C,2", "FECATE,D", "HORATE,C,5", "NUMHC,C,15", "DOC_IDEN,C,11", 
"ETNIA,C,2", "FINANCIA,C,2", "SEXO,C,1", "EDAD,N,3,0", "TIPOEDAD,C,1", "NOMB,C,50", "APELL,C,50", "DIRECC,C,100", "UBIG_RESHA,C,6", "UBIG_PROCE,C,6", "ACOMPANA,C,50", "ADOC_IDEN,C,11", 
"MOTATEN,C,2", "SITOCURREN,C,6", "UPS,C,6", "CODDIAG1,C,5", "TIPDIAG1,C,1", "CODDIAG2,C,5", "TIPDIAG2,C,1", "CODDIAG3,C,5", "TIPDIAG3,C,1", "CODDIAG4,C,5", "TIPDIAG4,C,1", "CODCPT1,C,5", 
"CODCPT2,C,5", "CODCPT3,C,5", "CODCPT4,C,5", "CONDICION,C,1", "FECEGR,D", "HOREGR,C,5", "DESTINO,C,2", "DES_EESS,C,10", "DES_UPS,C,6", "CODPSAL,C,11", "OBSERV,N,1,0", "OFECING,D", 
"OHORING,C,5", "OFECEGR,D", "OHOREGR,C,5", "TOTALEST,N,3,0", "OCAMA,C,10", "OCODPSAL,C,11", "OCODDIAG1,C,5", "OCODDIAG2,C,5", "FECHAREG,D", "ESTADO,N,1,0", "PRIORIDAD,N,1,0", "FECHAREG,C,10", 
"PRIORIDAD,C,1", "OBSERV,C,1", "PRIORIDAD,C,15"})

