<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPerformanceIndexesToSiafTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Índices para pres_base_siafweb_detalle
        // Esta es la tabla más grande y crítica para el rendimiento
        Schema::table('pres_base_siafweb_detalle', function (Blueprint $table) {
            // Índice compuesto para filtrado principal por importación y unidad ejecutora
            // Mejora consultas que usan whereIn('basesiafweb_id') y whereIn('unidadejecutora_id')
            $table->index(['basesiafweb_id', 'unidadejecutora_id'], 'idx_swd_base_ue');
            
            // Índices para claves foráneas usadas en JOINS
            $table->index('categoriapresupuestal_id', 'idx_swd_cp');
            $table->index('categoriagasto_id', 'idx_swd_cg');
            $table->index('rubro_id', 'idx_swd_rubro');
            $table->index('especificadetalle_id', 'idx_swd_ed');
        });

        // Índices para pres_base_siafweb
        Schema::table('pres_base_siafweb', function (Blueprint $table) {
            // Mejora filtrado por año y mes en subconsultas
            $table->index('anio', 'idx_sw_anio');
            $table->index(['anio', 'mes'], 'idx_sw_anio_mes');
            $table->index('importacion_id', 'idx_sw_importacion');
        });

        // Índices para par_importacion
        Schema::table('par_importacion', function (Blueprint $table) {
            // Mejora la búsqueda de la última importación (PR) y fecha
            $table->index(['estado', 'fechaActualizacion'], 'idx_imp_estado_fecha');
        });

        // Índices para pres_rubro
        Schema::table('pres_rubro', function (Blueprint $table) {
            // Mejora el join con fuente de financiamiento
            $table->index('fuentefinanciamiento_id', 'idx_rubro_ff');
        });
        
        // Índices para pres_categoriapresupuestal
        Schema::table('pres_categoriapresupuestal', function (Blueprint $table) {
            // Mejora búsquedas por tipo de categoría
            $table->index('tipo_categoria_presupuestal', 'idx_cp_tipo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pres_base_siafweb_detalle', function (Blueprint $table) {
            $table->dropIndex('idx_swd_base_ue');
            $table->dropIndex('idx_swd_cp');
            $table->dropIndex('idx_swd_cg');
            $table->dropIndex('idx_swd_rubro');
            $table->dropIndex('idx_swd_ed');
        });

        Schema::table('pres_base_siafweb', function (Blueprint $table) {
            $table->dropIndex('idx_sw_anio');
            $table->dropIndex('idx_sw_anio_mes');
            $table->dropIndex('idx_sw_importacion');
        });

        Schema::table('par_importacion', function (Blueprint $table) {
            $table->dropIndex('idx_imp_estado_fecha');
        });

        Schema::table('pres_rubro', function (Blueprint $table) {
            $table->dropIndex('idx_rubro_ff');
        });

        Schema::table('pres_categoriapresupuestal', function (Blueprint $table) {
            $table->dropIndex('idx_cp_tipo');
        });
    }
}
