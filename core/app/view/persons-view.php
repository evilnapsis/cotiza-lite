<?php 
// si el usuario no esta logeado
if(!isset($_SESSION["user_id"])){ Core::redir("./");}
$user= UserData::getById($_SESSION["user_id"]);
// si el id  del usuario no existe.
if($user==null){ Core::redir("./");}
?>
<?php if(isset($_GET["opt"]) && $_GET['opt']=="all"):
$contacts = PersonData::getAll();
  ?>










            <div class="row">
            <div class="col-md-12">
              <div class="card mb-4">
                <div class="card-header">Clientes</div>
                <div class="card-body">
<a href="./?view=persons&opt=new" class="btn btn-secondary">Nuevo Cliente</a>
                  <!-- /.row--><br><br>
                  <div class="table-responsive">

<?php if(count($contacts)>0):?>
                    <table class="table border mb-0">
                      <thead class="table-light fw-semibold">
                        <tr class="align-middle">

                          <th>#</th>
                          <th class="">Codigo</th>
                          <th class="">RFC</th>
                          <th class="">Nombre</th>
                          <th class="">Direccion Comercial</th>
                          <th>Telefono</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>

    <?php foreach($contacts as $con):?>
      <tr>
        <td>#<?php echo $con->id; ?></td>
        <td><?php echo $con->code; ?></td>
        <td><?php echo $con->rfc; ?></td>
        <td><?php echo $con->name." ".$con->lastname; ?></td>
        <td><?php echo $con->address; ?></td>
        <td><?php echo $con->phone; ?></td>
        <td>
          <a href="./?view=persons&opt=edit&id=<?php echo $con->id; ?>" class="btn btn-warning btn-sm"><i class="bi-pencil"></i></a>
          <a href="./?action=persons&opt=del&id=<?php echo $con->id; ?>" class="btn btn-danger btn-sm"><i class="bi-trash"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
                      </tbody>
                    </table>
<?php else:?>
  <p class="alert alert-warning">No hay Clientes</p>
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
                <div class="card-header">Clientes</div>
                <div class="card-body">
<h2>Nuevo Cliente</h2>

                <div class="row">
                  <div class="col-md-6">
<form method="post" action="./?action=persons&opt=add">


<div class="row">
  <div class="col-md-6">
<!-- -->
  <div class="form-group">
    <label for="exampleInputEmail1">Codigo Interno</label>
    <input type="text" required name="code" value="" class="form-control" id="exampleInputEmail1" placeholder="Codigo Interno">
  </div>

  <div class="form-group">
    <label for="exampleInputEmail1">RFC</label>
    <input type="text" required name="rfc" value="" class="form-control" id="exampleInputEmail1" placeholder="RFC">
  </div>


  <div class="form-group">
    <label for="exampleInputEmail1">Nombre</label>
    <input type="text" required name="name" class="form-control" id="exampleInputEmail1" placeholder="Nombre">
  </div>
  <input type="hidden" name="lastname" value="">
  <!-- 
  <div class="form-group">
    <label for="exampleInputEmail1">Apellidos</label>
    <input type="text" required name="lastname" class="form-control" id="exampleInputEmail1" placeholder="Apellidos">
  </div>
-->
  <div class="form-group">
    <label for="exampleInputEmail1">Email</label>
    <input type="text" required name="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Telefono</label>
    <input type="text" required name="phone" class="form-control" id="exampleInputPassword1" placeholder="Telefono">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Comentarios</label>
    <textarea required name="comments" class="form-control" id="exampleInputPassword1" placeholder="Comentarios"></textarea>
  </div>

<!-- -->

  </div>
  <div class="col-md-6">
    <!-- -->

  <div class="form-group">
    <label for="exampleInputPassword1">Contacto</label>
    <input type="text" required name="contact" class="form-control" id="exampleInputPassword1" placeholder="Contacto">
  </div>

  <div class="form-group">
    <label for="exampleInputPassword1">Direccion Comercial</label>
    <input type="text" required name="address" class="form-control" id="exampleInputPassword1" placeholder="Direccion Comercial">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Ciudad</label>
    <select required name="city_id" class="form-control"> 
      <option value="">-- SELECCIONE --</option>
    <?php foreach(CityData::getAll() as $city):?>
      <option value="<?php echo $city->id; ?>"><?php echo $city->name; ?></option>
    <?php endforeach; ?>
</select>
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Credito</label>
    <select required name="credit_id" class="form-control"> 
      <option value="">-- SELECCIONE --</option>
    <?php foreach(CreditData::getAll() as $city):?>
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
  <input type="reset" class="btn btn-secondary" value="Borrar Datos">
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
$contact = PersonData::getById($_GET["id"]);
  ?>
  <div class="">
<div class="row">
<div class="col-md-12">

              <div class="card mb-4">
                <div class="card-header">Clientes</div>
                <div class="card-body">
<h2>Editar Cliente</h2>
<div class="row">
  <div class="col-md-6">
<form method="post" action="./?action=persons&opt=update">


  <input type="hidden" name="_id" value="<?php echo $contact->id; ?>">


<div class="row">
  <div class="col-md-6">
    <!-- -->
  <div class="form-group">
    <label for="exampleInputEmail1">Codigo Interno</label>
    <input type="text" required name="code" value="<?php echo $contact->code; ?>" class="form-control" id="exampleInputEmail1" placeholder="Codigo Interno">
  </div>

  <div class="form-group">
    <label for="exampleInputEmail1">RFC</label>
    <input type="text" required name="rfc" value="<?php echo $contact->rfc; ?>" class="form-control" id="exampleInputEmail1" placeholder="RFC">
  </div>

  <div class="form-group">
    <label for="exampleInputEmail1">Nombre</label>
    <input type="text" required name="name" value="<?php echo $contact->name; ?>" class="form-control" id="exampleInputEmail1" placeholder="Nombre">
  </div>
  <input type="hidden" name="lastname" value="">
  <!-- 
  <div class="form-group">
    <label for="exampleInputEmail1">Apellidos</label>
    <input type="text" required name="lastname" value="<?php echo $contact->lastname; ?>" class="form-control" id="exampleInputEmail1" placeholder="Apellidos">
  </div>
-->
  <div class="form-group">
    <label for="exampleInputEmail1">Email</label>
    <input type="text" required name="email" value="<?php echo $contact->email; ?>" class="form-control" id="exampleInputEmail1" placeholder="Email">
  </div>

  <div class="form-group">
    <label for="exampleInputPassword1">Telefono</label>
    <input type="text" required name="phone" value="<?php echo $contact->phone; ?>" class="form-control" id="exampleInputPassword1" placeholder="Telefono">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Comentarios</label>
    <textarea required name="comments" class="form-control" id="exampleInputPassword1" placeholder="Comentarios"><?php echo $contact->comments; ?></textarea>
  </div>
    <!-- -->
  </div>
  <div class="col-md-6">
    <!-- -->
    <div class="form-group">
    <label for="exampleInputPassword1">Contacto</label>
    <input type="text" required name="contact"  value="<?php echo $contact->contact; ?>" class="form-control" id="exampleInputPassword1" placeholder="Contacto">
  </div>

  <div class="form-group">
    <label for="exampleInputPassword1">Direccion</label>
    <input type="text" required name="address"  value="<?php echo $contact->address; ?>" class="form-control" id="exampleInputPassword1" placeholder="Direccion">
  </div>

  <div class="form-group">
    <label for="exampleInputEmail1">Ciudad</label>
    <select required name="city_id" class="form-control"> 
      <option value="">-- SELECCIONE --</option>
    <?php foreach(CityData::getAll() as $city):?>
      <option value="<?php echo $city->id; ?>" <?php if($city->id==$contact->city_id){ echo "selected"; }?>><?php echo $city->name; ?></option>
    <?php endforeach; ?>
</select>
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Credito</label>
    <select required name="credit_id" class="form-control"> 
      <option value="">-- SELECCIONE --</option>
    <?php foreach(CreditData::getAll() as $city):?>
      <option value="<?php echo $city->id; ?>" <?php if($city->id==$contact->credit_id){ echo "selected"; }?>><?php echo $city->name; ?></option>
    <?php endforeach; ?>
</select>
  </div>
    <!-- -->
  </div>
</div>



  <br>
  <div class="d-grid gap-2">
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
