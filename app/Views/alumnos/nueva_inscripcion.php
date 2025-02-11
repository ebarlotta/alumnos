<script>
  $(document).ready(function(){
    $("#instituto").change(function(){
      var vINSTITUTO = $(this).val(); //Leemos el value de la opción en el select de carrera
      $.ajax({
        type:'POST',
        url:'<?php echo base_url(); ?>/alumnos/carreras_json',
        data:{
                buscar: vINSTITUTO,
            },
        
        success: function(resultado) {
            const jsonData= JSON.parse(resultado);

            let select = $("#carrera");
            select.empty(); // Limpiamos opciones del select
            select.append('<option value="">-- Seleccione una opción</option>');
            document.getElementById('materias').innerHTML = ''; // Limpiamos div-checks de Materias

            $.each(jsonData, function(index, carrera) {
                select.append(`<option value="${carrera.id}">${carrera.nombre} - Res. ${carrera.resolucion}</option>`);
            });
        },

      })
    });

    $("#carrera").change(function(){
      var vCARRERA = $(this).val(); //Leemos el value de la opción en el select de CARRERA
      $.ajax({
        type:'POST',
        url:'<?php echo base_url(); ?>/alumnos/anos_json',
        data:{
                buscar: vCARRERA,
            },
        
        success: function(resultado) {
            const jsonData= JSON.parse(resultado);
            let select = $("#ano");
            select.empty();
            select.append('<option value="">-- Seleccione una opción</option>');
            document.getElementById('materias').innerHTML = ''; // Limpiamos div-checks de Materias

            $.each(jsonData, function(index, ano) {
                select.append(`<option value="${ano.ano}">${ano.ano} ° año</option>`);
            });
        },
      })
    });

    $("#ano").change(function(){

        var vCARRERA = $(carrera).val(); //Leemos el value de la carrera
        var vANO = $(ano).val(); //Leemos el value del año

        $.ajax({
        type:'POST',
        url:'<?php echo base_url(); ?>/alumnos/materias_json',
        data:{
                buscarCarrera: vCARRERA,
                buscarAno: vANO,
            },
        
        success: function(resultado) {
            const jsonData= JSON.parse(resultado);

            // Eliminamos los elementos checkbox del div
            document.getElementById('materias').innerHTML = '';
            
            const container = document.getElementById("materias");
            // Recorre el JSON y crea checkboxes
            jsonData.forEach((item, index) => {

                const checkbox = document.createElement("input");
                checkbox.type = "checkbox";
                // Asignamos nombre al checkbox
                checkbox.name = `codigo${index}`;
                // Asignamos valor al checkbox - ID Materia
                checkbox.value = `${item.id}`;
                // Asignamos id al checkbox
                checkbox.id = `codigo${index}`;
                checkbox.checked = item.checked;
                checkbox.style = `width: 1.3rem;height: 1.3rem;`;
                const espacio = document.createElement("elemento");
                espacio.htmlFor = 'checkbox${item.id}';
                // Agregamos item con la info del jsonData
                espacio.appendChild(document.createTextNode("  " + item.id + "  -  " + item.nombre + "  -  " + item.ano + "º Año"));
                container.appendChild(checkbox);
                container.appendChild(espacio);
                container.appendChild(document.createElement("br"));
                // Asignamos cantidad de checkbox seleccionados al input que envia el valor al backend
                const input = document.getElementById("cantidad");
                input.value = index;

            });
        }
      })

    });
  });
</script>

<script>
    var clicks=0;
</script>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
            <h2 class="mt-4"><i class="fas fa-list-alt"></i> <?php echo $vTITULO;?></h2>
                <hr>
                <form method="POST" action="<?php echo base_url(); ?>/alumnos/inscribir_XXXXXX" autocomplete="off">
                    
                <p>
                    <a href="<?php echo base_url(); ?>/home" class="btn btn-secondary"><i class="fa fa-undo-alt"></i> Cancelar</a>
                    <button type="submit" onclick="clicks++;if(clicks>1){return false};" id="boton" class="btn btn-success" disabled><i class="fas fa-save"></i> Guardar</button>
                </p>

                <div class="form-group">

                    <fieldset class="form-group border p-3" style="background-color: #f2f2f2">
                        <div class="row">
                            <div class="col-sm-12">
                                <label><b>Seleccione el Instituto</b></label>
                                <select class="form-control" id="instituto" name="instituto">
                                    <option value="0" selected>-- Seleccione una opción</option>
                                    <?php foreach($vIES as $dato): ?>
                                        <option value="<?php echo $dato['id'] ?>"><?php echo 'IES ' . $dato['numero'] . ' - ' . $dato['nombre']?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 mt-2">
                                <label><b>Seleccione la Carrera</b></label>
                                <select class="form-control" id="carrera" name="carrera">

                                </select>
                            </div>
                        </div>         
                        <div class="row">
                            <div class="col-sm-12 mt-2">
                                <label><b>Seleccione el año</b></label>
                                <select class="form-control" id="ano" name="ano">
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <fieldset class="form-group border p-3" style="background-color: #f2f2f2">
                                    <label><b>Seleccione las Materias a inscribirse</b></label>
                                    <div id="materias">

                                    </div>
                                </fieldset>
                            </div>
                        </div>         
                        <input type="hidden" value="" name="cantidad" id="cantidad"/>               
                    </fieldset>
                
                </form>

            </div>

        </main>