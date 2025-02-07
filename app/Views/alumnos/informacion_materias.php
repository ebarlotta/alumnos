<div id="layoutSidenav_content">
<script src="https://unpkg.com/htmx.org@1.8.4"></script>
    <main>
        <div class="container-fluid">

            <h2 class="mt-4"><i class="fas fa-history"></i><?php echo ' '. $vTITULO .' | ' . '<span style="color:blue">'. $vCARRERA . '</span>'; ?></h1>
            <hr>                    
            <div>
                <p>
                    <a href="<?php echo base_url(); ?>/alumnos/index" class="btn btn-secondary"><i class="fa fa-undo-alt"></i> Volver</a>
                </p>
            </div>
            
            <div class="card mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table table-bordered" id="dataTable" width="100%" cellspacing="0" data-order='[[2,"asc"]]'>
                            <thead class="thead-dark">
                                <tr>
                                    <th>Lugar de Dictado</th>
                                    <th>Año</th>
                                    <th>Régimen</th>
                                    <th>Cuatrimestre</th>
                                    <th>Resolución</th>
                                    <th>Docente a cargo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($vDATOS as $dato) { ?>
                                    <tr>
                                        <td><?php echo $dato['nombre_instituto'] ?></td>
                                        <td><?php echo $dato['ano'] ?></td>
                                        <td><?php echo $dato['regimen'] ?></td>
                                        <td><?php echo $dato['cuatrimestre']=="" ? "Anual" : $dato['cuatrimestre'] ?></td>
                                        <td style="color:red"><b>Resolución</b></td>
                                        <td><?php echo $dato['user_apellido'].', '.$dato['user_nombres'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    
                    <select name="" id="" class="form-control" hx-get="<?php echo base_url(); ?>/alumnos/listado" hx-target="#listado" hx-trigger="change">
                        <option value="">Opcion 1</option>
                        <option value="">Opcion 2</option>
                        <option value="">Opcion 3</option>
                    </select>
                    <div id="listado" style="background-color: lightgreen;">
                        Listado Vacio
                    </div>
                    <div id="listadoNuevo" style="background-color: lightseagreen;">
                        Listado Vacio
                    </div>
                    <button hx-get="<?php echo base_url(); ?>/alumnos/listado" hx-target="#contenido">Cargar más contenido + </button>
                    <div id="contenido" style="background-color: lightcoral;">
                        contenido
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>