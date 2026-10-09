<?php 
// si el usuario no esta logeado
if(!isset($_SESSION["user_id"])){ Core::redir("./");}
$user= UserData::getById($_SESSION["user_id"]);
// si el id  del usuario no existe.
if($user==null){ Core::redir("./");}
?>
<?php if(isset($_GET["opt"]) && $_GET['opt']=="all"):
$contacts = CotizationData::getAll();
  ?>

            <div class="row">
            <div class="col-md-12">
              <div class="card mb-4">
                <div class="card-header">Cotizaciones</div>
                <div class="card-body">
<a href="./?action=cotizations&opt=add" class="btn btn-secondary">Nueva Cotizacion</a>
                  <!-- /.row--><br><br>
                  <div class="table-responsive">

<?php if(count($contacts)>0):?>
                    <table class="table border mb-0">
                      <thead class="table-light fw-semibold">
                        <tr class="align-middle">

                          <th>#</th>
                          <th class="">Id</th>
                          <th>Total</th>
                          <th>Cliente</th>
                          <th class="">Estado</th>
                          <th class="">Fecha</th>
                          <th></th>
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
        <td><a href="./?view=cotizations&opt=edit&id=<?php echo $con->id; ?>" class="btn btn-link btn-sm">#<?php echo $con->id; ?></a></td>
        <td><?php echo $con->id; ?></td>
        <td>$ <?php echo number_format($total, 2, ".",",")?></td>
        <td><?php if($con->person_id!=null){ echo PersonData::getById($con->person_id)->name; }?></td>
        <td><?php echo StatusData::getById($con->status_id)->name; ?></td>
        <td><?php echo $con->created_at; ?></td>
        <td>
          <a href="./?view=cotizations&opt=edit&id=<?php echo $con->id; ?>" class="btn btn-warning btn-sm"><i class="bi-pencil"></i></a>
          <a href="./?action=cotizations&opt=del&id=<?php echo $con->id; ?>" class="btn btn-danger btn-sm"><i class="bi-trash"></i></a>
        </td>
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













<?php elseif(isset($_GET["opt"]) && $_GET["opt"]=="new"):?>
<div class="">
<div class="row">
<div class="col-md-12">

              <div class="card mb-4">
                <div class="card-header">Cotizaciones</div>
                <div class="card-body">
<h2>Nueva Cotizacion</h2>

                <div class="row">
                  <div class="col-md-6">
<form method="post" action="./?action=cotizations&opt=add">




  <div class="form-group">
    <label for="exampleInputEmail1">Nombre</label>
    <input type="text" required name="name" class="form-control" id="exampleInputEmail1" placeholder="Nombre">
  </div>

  <div class="d-grid gap-2">
  <button type="submit" class="btn btn-primary ">Guardar</button>
</div>
</form>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<?php elseif(isset($_GET["opt"]) && $_GET["opt"]=="edit"):
$contact = CotizationData::getById($_GET["id"]);
  ?>
  <div class="">
<div class="row">
<div class="col-md-12">



<!-- --->
<div class="card">
  <div class="card-body">
<form method="post" id="updateservice" action="./?action=cotizations&opt=update">
<input type="hidden" name="_id" value="<?php echo $contact->id; ?>">
<div class="row">
  <div class="col-md-4">

  <div class="form-group">
    <label for="exampleInputEmail1">Cliente</label>
    <select  name="person_id" class="form-control"> 
      <option value="">-- SELECCIONE --</option>
    <?php foreach(PersonData::getAll() as $city):?>
      <option value="<?php echo $city->id; ?>" <?php if($city->id==$contact->person_id){ echo "selected"; }?>><?php echo $city->name; ?></option>
    <?php endforeach; ?>
</select>
  </div>

  </div>
  <div class="col-md-4">
  <div class="form-group">
    <label for="exampleInputEmail1">Id</label>
    <input type="text" required name="name" value="<?php echo $contact->id; ?>" readonly class="form-control" id="exampleInputEmail1" placeholder="Id">
  </div>

  </div>
  <div class="col-md-4">

  <div class="form-group">
    <label for="exampleInputEmail1">Status</label>
    <select required name="status_id" class="form-control"> 
      <option value="">-- SELECCIONE --</option>
    <?php foreach(StatusData::getAll() as $city):?>
      <option value="<?php echo $city->id; ?>" <?php if($city->id==$contact->status_id){ echo "selected"; }?>><?php echo $city->name; ?></option>
    <?php endforeach; ?>
</select>
  </div>

  </div>
</div>
<div class="row">
  <div class="col-md-4">

  <div class="form-group">
    <input type="submit" class="btn btn-primary" value="Guardar">
  </div>

  </div>
</div>

</form>
</div>
</div>
<!-- --->
<br>
<!-- --->
<div class="card">
  <div class="card-body">

    <form method="post" id="addservice" action="./?action=cotizations&opt=additem">
<input type="hidden" name="_id" value="<?php echo $contact->id; ?>">


<div class="row">
  <div class="col-md-2">
  <div class="form-group">

    <label for="exampleInputEmail1">Producto / Servicio</label>

<div class="input-group mb-3">
  <input type="number" min="1" required name="q" value="1" class="form-control">
</div>
  </div>
</div>
  <div class="col-md-6">
  <div class="form-group">

    <label for="exampleInputEmail1">Producto / Servicio</label>

<div class="input-group mb-3">
    <select required name="service_id" class="form-control"> 
      <option value="">-- SELECCIONE --</option>
    <?php foreach(ServiceData::getAll() as $city):?>
      <option value="<?php echo $city->id; ?>" >#<?php echo $city->id; ?> - <?php echo $city->name; ?></option>
    <?php endforeach; ?>
</select>

  <button class="btn btn-primary" type="submit" id="button-addon2">Agregar </button>
</div>
  </div>
</div>
</div>

</form>

<!-- ----------------------------------------------->
<?php 
$items = CotizationItemData::getAllBy("cotization_id", $contact->id);
$total = 0;
?>
<?php if(count($items)>0):?>
<table class="table table-bordered table-striped table-sm">
  <thead>
    <th>Cantidad</th>
    <th>Servicio</th>
    <th>P.U</th>
    <th>Total</th>
    <th></th>
  </thead>
<?php 
foreach ($items as $it): 
$serv = ServiceData::getById($it->service_id);
  ?>
  <tr>
    <td><?php echo $it->q; ?></td>
    <td><?php echo $serv->name; ?></td>
    <td>$ <?php echo number_format($serv->amount,2,".",","); ?></td>
    <td>$ <?php echo number_format($it->q*$serv->amount,2,".",","); ?></td>
    <td><a href="./?action=cotizations&opt=delitem&item_id=<?php echo $it->id; ?>&cotization_id=<?php echo $it->cotization_id; ?>" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a></td>
  </tr>

<?php 
$total+=$it->q*$serv->amount;
endforeach; ?>
</table>
<?php else:?>
  <p class="alert alert-warning"> Aun no hay Productos / Servicios Agregados!</p>
<?php endif; ?>
<!-- ----------------------------------------------->
  </div>
  </div>
<!-- --->

<br>
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <!-- -->
          <div class="col-md-9">


    <form method="post" id="updatenotes" action="./?action=cotizations&opt=updatenotes">
<input type="hidden" name="_id" value="<?php echo $contact->id; ?>">

<div class="row">
  <div class="col-md-6">
  <div class="form-group">

    <label for="exampleInputEmail1">Forma de pago</label>

<div class="input-group mb-3">
<select class="form-control" required name="forma_id">
  <?php foreach(FormaData::getAll() as $fo):?>
    <option value="<?php echo $fo->id; ?>" <?php if($contact->forma_id==$fo->id){ echo "selected"; }?>><?php echo $fo->name; ?></option>
    <?php endforeach; ?>
</select>
</div>
  </div>
</div>

</div>

<div class="row">
  <div class="col-md-6">
  <div class="form-group">

    <label for="exampleInputEmail1">Comentarios</label>

<div class="input-group mb-3">
  <textarea name="notes" class="form-control"><?php echo $contact->notes; ?></textarea>
</div>
  </div>
</div>

</div>

</form>


            <button type="button" id="savedata" class="btn btn-primary"><i class="bi bi-save"></i> Guardar Datos</button>


            <a href="./cotization.php?id=<?php echo $contact->id; ?>" target="_blank" class="btn btn-success"><i class="bi bi-printer"></i> Imprimir</a>
<!--            <a href="./?action=cotizations&opt=sendmail&id=<?php echo $contact->id; ?>" class="btn btn-warning"><i class="bi bi-envelope"></i> Enviar por Email</a>
            <button type="button" class="btn btn-danger"><i class="bi bi-trash"></i> Borrar Datos</button>-->
          </div>
<script type="text/javascript">
  $("#savedata").click(function(e){
    e.preventDefault();
$.post("./?action=cotizations&opt=update",$("#updateservice").serialize(),function(data){ console.log(data); });
$.post("./?action=cotizations&opt=updatenotes",$("#updatenotes").serialize(),function(data){ console.log(data);});
    alert("Datos Guardados!");
  });
</script>

          <!-- -->
          <!-- -->
          <div class="col-md-3">
            <h3>RESUMEN DEL PAGO</h3>
            <h4>TOTAL: $ <?php echo number_format($total, 2, ".",",");?></h4>
          </div>
          <!-- -->
        </div>
      </div>
    </div>
  </div>
</div>

</div>
</div>
</div>
<?php endif; ?>
