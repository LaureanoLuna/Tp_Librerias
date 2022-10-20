<?php
$Titulo = 'Consultas';
include_once('./estructura/cabecera.php'); ?>

<?php $data = data_submitted();?>

<div class="container-sm p-4">
    <div class="container text-center">
        <h4><i class="fa-solid fa-clipboard-question"></i> Consultas</h4>
        <h5>Compártenos tu duda, en breve te la responderemos</h5>
    </div>

    <?php 
    if (!empty($data['accion'])){
        if ($data['accion'] == true) {
            echo
            "
                <div class='container text-center'> 
                    <div class='card bg-warning'>
                        <div class='card-body'>
                            <h3>Su consulta fue enviada</h3>
                        </div>
                    </div>
                </div>
            ";
        }else{
            echo 
            "
                <div class='container text-center'> 
                    <div class='card bg-danger'>
                        <div class='card-body'>
                            <h3>Su consulta no se puedo enviar</h3>
                        </div>
                    </div>
                </div>
            ";
        }
    }
    ?>

    <hr>

    <form action="./accionComentario.php" method="post" name="Consulta" id="Consulta" autocomplete=off novalidate class="row g-3">
        <div class="form-group col-md-6">
            <label for=nombre class="form-label">Nombre: </label>
            <input class="form-control bg-light p-2 bg-opacity-150" name=nombre id=nombre type=text readonly value=<?php echo $AUTH->getUsername()?>>
        </div>
        <div class="form-group col-md-6">
            <label for=email class="form-label">Email: </label>
            <input type="email" class="form-control bg-light p-2 bg-opacity-100" name="email" id="email" aria-describedby="emailHelpId" readonly value=<?php echo $AUTH->getEmail()?>>
        </div>
        <div class="form-group col-md-12">
            <label for="comentario" class="form-label">Consulta: </label>
            <textarea class="form-control" name="comentario" id="comentario" rows="5"></textarea>
        </div>
        <div class="col-md-6">
            <input type=submit value="Enviar" class="btn btn-outline-success">
        </div>
        <div class="form-group">
            <input hidden checked class="form-check-input" type="radio" name="motivo" id="motivo" value="Consulta">
        </div>
    </form>
    <hr>
    <div class="col-md-12">
        <form action="indexSeguro.php" method="post">
            <input type="submit" class="btn btn-outline-dark w-100" value="Volver">
        </form>

        </div>
</div>

</div>


<?php include_once('./estructura/pie.php'); ?>