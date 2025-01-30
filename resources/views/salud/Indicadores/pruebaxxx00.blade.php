<div class="container mt-3">
 <div class="card">
     <div class="card-header bg-primary text-white d-flex flex-column flex-md-row justify-content-between align-items-md-center">
         <h6 class="mb-2 mb-md-0 text-center text-md-left text-wrap">
             <i class="fas fa-chart-bar"></i> PORCENTAJE DE NIÑAS Y NIÑOS MENORES DE 6 AÑOS DEL PADRÓN NOMINAL CON INFORMACIÓN HOMOLOGADA Y ACTUALIZADA
         </h6>
         <div class="d-flex align-items-center">
             <select id="filtroEdad" class="form-control form-control-sm mr-2">
                 <option value="">📅 Filtrar por Edad</option>
                 <option value="18">18 años</option>
                 <option value="25">25 años</option>
                 <option value="30">30 años</option>
                 <option value="35">35 años</option>
             </select>
             <button class="btn btn-success btn-sm mr-1" onclick="nuevoRegistro()">
                 <i class="fas fa-plus"></i> Nuevo
             </button>
             <button class="btn btn-light btn-sm" onclick="refrescarTabla()">
                 <i class="fas fa-sync-alt"></i> Actualizar
             </button>
         </div>
     </div>

     <!-- 📊 Tabla con DataTables -->
     <div class="card-body p-2">
         <div class="table-responsive">
             <table id="tablaDatos" class="table table-sm table-striped table-bordered text-center">
                 <thead class="bg-secondary text-white">
                     <tr>
                         <th>ID</th>
                         <th>Nombre</th>
                         <th>Edad</th>
                         <th>Email</th>
                         <th>Acciones</th>
                     </tr>
                 </thead>
                 <tbody>
                     <tr>
                         <td>1</td>
                         <td>Juan Pérez</td>
                         <td>28</td>
                         <td>juan@example.com</td>
                         <td>
                             <button class="btn btn-success btn-sm"><i class="fas fa-edit"></i></button>
                             <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                         </td>
                     </tr>
                     <tr>
                         <td>2</td>
                         <td>Maria López</td>
                         <td>35</td>
                         <td>maria@example.com</td>
                         <td>
                             <button class="btn btn-success btn-sm"><i class="fas fa-edit"></i></button>
                             <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                         </td>
                     </tr>
                 </tbody>
             </table>
         </div>
     </div>
 </div>
</div>

<!-- Scripts de DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
