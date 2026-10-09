<?php
$contacts =array();
if(isset($_GET["start_at"]) && isset($_GET["finish_at"])){
  if($_GET['start_at']!="" && $_GET["finish_at"]){
    $contacts = CotizationData::getAllByRange($_GET['start_at'], $_GET["finish_at"]);

  }
}

?>
            <div class="row">
            <div class="col-md-12">
              <div class="card mb-4">
                <div class="card-header">Busqueda</div>
                <div class="card-body">
<form method="get">
  <input type="hidden" name="view" value="report1">
<div class="row">
  <div class="col-md-6">
  <div class="form-group">
    <label for="exampleInputEmail1">Fecha inicial</label>
    <input type="date" required name="start_at" class="form-control" id="exampleInputEmail1" placeholder="Fecha inicial">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Fecha final</label>
    <input type="date" required name="finish_at" class="form-control" id="exampleInputEmail1" placeholder="Fecha final">
  </div>
<br>
  <div class="">
  <button type="submit" class="btn btn-primary "><i class="bi bi-check"></i> Generar Reporte</button>
  <a href="./?view=report1" class="btn btn-secondary" value="Borra Datos">Borrar Datos</a>


<?php if(count($contacts)>0):?>
 <a href="./reports/report1-xlsx.php?start=<?php echo $_GET["start_at"]; ?>&finish=<?php echo $_GET["finish_at"]; ?>" class="btn btn-success" value="Borra Datos">Descargar (.xlsx)</a>
<?php endif; ?>

</div>
</div>
</div>
</form>
                </div>
              </div>


              <div class="card mb-4">
                <div class="card-header">Resultados</div>
                <div class="">
                  <!-- /.row-->
                  <div class="table-responsive">

<?php if(count($contacts)>0):?>
                    <table class="table border mb-0">
                      <thead class="table-light fw-semibold">
                        <tr class="align-middle">

                          <th class="">Id</th>
                          <th>Total</th>
                          <th>Cliente</th>
                          <th class="">Estado</th>
                          <th class="">Fecha</th>
                        </tr>
                      </thead>
                      <tbody>

    <?php foreach($contacts as $con):
$items = CotizationItemData::getAllBy("cotization_id", $con->id);

$total = 0;
foreach ($items as $it){
$serv = ServiceData::getById($it->service_id);
$total+=($it->q*$serv->amount);
}

      ?>
      <tr>
        <td><?php echo $con->id; ?></td>
        <td>$ <?php echo number_format($total, 2, ".",",")?></td>
        <td><?php if($con->person_id!=null){ echo PersonData::getById($con->person_id)->name; }?></td>
        <td><?php echo StatusData::getById($con->status_id)->name; ?></td>
        <td><?php echo $con->created_at; ?></td>
      </tr>
    <?php endforeach; ?>
                      </tbody>
                    </table>
<?php else:?>

  <p class="alert alert-warning">No hay Cotizaciones</p>
<?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.col-->
          </div>




