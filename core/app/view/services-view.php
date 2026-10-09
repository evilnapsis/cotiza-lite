<?php 
// si el usuario no esta logeado
if(!isset($_SESSION["user_id"])){ Core::redir("./");}
$user= UserData::getById($_SESSION["user_id"]);
// si el id  del usuario no existe.
if($user==null){ Core::redir("./");}
?>
<?php if(isset($_GET["opt"]) && $_GET['opt']=="all"):
$contacts = ServiceData::getAll();
  ?>










            <div class="row">
            <div class="col-md-12">
              <div class="card mb-4">
                <div class="card-header">Servicios</div>
                <div class="card-body">
<a href="./?view=services&opt=new" class="btn btn-secondary">Nuevo Servicio</a>
                  <!-- /.row--><br><br>
                  <div class="table-responsive">

<?php if(count($contacts)>0):?>
                    <table class="table border mb-0">
                      <thead class="table-light fw-semibold">
                        <tr class="align-middle">

                          <th>#</th>
                          <th class="">Codigo</th>
                          <th class="">Nombre</th>
                          <th class="">Tipo de Servicio</th>
                          <th>Costo</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>

    <?php foreach($contacts as $con):?>
      <tr>
        <td><a href="./?view=services&opt=open&id=<?php echo $con->id; ?>" class="btn btn-link btn-sm">#<?php echo $con->id; ?></a></td>
        <td><?php echo $con->code; ?></td>
        <td><?php echo $con->name; ?></td>
        <td><?php echo $con->kind; ?></td>
        <td>$ <?php echo $con->amount; ?></td>
        <td>
          <a href="./?view=services&opt=edit&id=<?php echo $con->id; ?>" class="btn btn-warning btn-sm"><i class="bi-pencil"></i></a>
          <a href="./?action=services&opt=del&id=<?php echo $con->id; ?>" class="btn btn-danger btn-sm"><i class="bi-trash"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
                      </tbody>
                    </table>
<?php else:?>
  <p class="alert alert-warning">No hay Servicios</p>
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
                <div class="card-header">Servicios</div>
                <div class="card-body">
<h2>Nuevo Servicio</h2>

                <div class="row">
                  <div class="col-md-6">
<form method="post" action="./?action=services&opt=add">

<div class="row">
  <div class="col-md-6">
    <!-- -->
  <div class="form-group">
    <label for="exampleInputEmail1">Codigo Interno</label>
    <input type="text" required name="code" value="" class="form-control" id="exampleInputEmail1" placeholder="Codigo Interno">
  </div>


  <div class="form-group">
    <label for="exampleInputEmail1">Nombre</label>
    <input type="text" required name="name" class="form-control" id="exampleInputEmail1" placeholder="Nombre">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Tipo de Servicio</label>
    <input type="text" required name="kind" class="form-control" id="exampleInputEmail1" placeholder="Tipo de Servicio">
  </div>
    <!-- -->
  </div>
  <div class="col-md-6">
    <!-- -->
  <div class="form-group">
    <label for="exampleInputEmail1">Costo</label>
    <input type="text" required name="amount" class="form-control" id="exampleInputEmail1" placeholder="Costo">
  </div>

  <div class="form-group">
    <label for="exampleInputEmail1">Servicio Previo</label>
    <select  class="form-control" name="service_id"> 
      <option value="">-- SELECCIONE --</option>
    <?php foreach(ServiceData::getAll() as $city):?>
      <option value="<?php echo $city->id; ?>"><?php echo $city->name; ?></option>
    <?php endforeach; ?>
</select>
  </div>
    <!-- -->
  </div>
</div>

<br>
  <div class="">
  <button type="submit" class="btn btn-primary ">Guardar</button>
  <input type="reset" value="Borrar Datos" class="btn btn-secondary">
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
$contact = ServiceData::getById($_GET["id"]);
  ?>
  <div class="">
<div class="row">
<div class="col-md-12">

              <div class="card mb-4">
                <div class="card-header">Servicios</div>
                <div class="card-body">
<h2>Editar Servicio</h2>
<div class="row">
  <div class="col-md-6">
<form method="post" action="./?action=services&opt=update">

<div class="row">
  <div class="col-md-6">
    <!-- -->
  <input type="hidden" name="_id" value="<?php echo $contact->id; ?>">

  <div class="form-group">
    <label for="exampleInputEmail1">Codigo Interno</label>
    <input type="text" required name="code" value="<?php echo $contact->code; ?>" class="form-control" id="exampleInputEmail1" placeholder="Codigo Interno">
  </div>

  <div class="form-group">
    <label for="exampleInputEmail1">Nombre</label>
    <input type="text" required name="name" value="<?php echo $contact->name; ?>" class="form-control" id="exampleInputEmail1" placeholder="Nombre">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Tipo de Servicio</label>
    <input type="text" required name="kind" value="<?php echo $contact->kind; ?>" class="form-control" id="exampleInputEmail1" placeholder="Tipo de Servicio">
  </div>
    <!-- -->
  </div>
  <div class="col-md-6">
    <!-- -->
  <div class="form-group">
    <label for="exampleInputEmail1">Costo</label>
    <input type="text" required name="amount" value="<?php echo $contact->amount; ?>" class="form-control" id="exampleInputEmail1" placeholder="Costo">
  </div>

  <div class="form-group">
    <label for="exampleInputEmail1">Servicio Previo</label>
    <select  class="form-control" name="service_id"> 
      <option value="">-- SELECCIONE --</option>
    <?php foreach(ServiceData::getAll() as $city):?>
      <option value="<?php echo $city->id; ?>" <?php if($city->id==$contact->service_id){ echo "selected"; }?>><?php echo $city->name; ?></option>
    <?php endforeach; ?>
</select>
  </div>
    <!-- -->
  </div>
</div>

<br>

  <div class="">
  <button type="submit" class="btn btn-success ">Actualizar</button>
</div>
</form>

</div>
</div>

</div>
</div>

</div>
</div>
</div>
<?php endif; ?>
