<div id="layoutSidenav_content">

    <main>
        <div class="container-fluid">

            <h2 class="mt-4"><i class="fas fa-history"></i><?php echo ' '. $vTITULO .' | ' . '<span style="color:blue">'. $vCARRERA['nombre'] . '</span>'; ?></h1>
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
                                    <th>Id</th>
                                    <th>Nombre</th>
                                    <th>Año</th>
                                    <th>Régimen</th>
                                    <th>Año</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($vDATOS as $dato) { ?>
                                    <tr>
                                        <td><?php echo $dato['id'] ?></td>
                                        <td><?php echo $dato['nombre'] ?></td>
                                        <td><?php echo $dato['ano'] ?></td>
                                        <td><?php echo $dato['regimen'] ?></td>
                                        <td><?php echo $dato['ano'] ?></td>
                                        <td>                        
                                            <a href="<?php echo base_url(). '/alumnos/informacion_materias/'. $dato['id'] ?>">
                                                <input type="button" value="Información">
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- Confirma Elimina en Modal -->
        <script>
            $('#modal-eliminar').on('show.bs.modal', function(e){
                $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
            });
        </script>




    </main>
