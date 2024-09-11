<?php
// Inicia el búfer de salida
$titulo = "Dashboard";
require 'template/header.php';
use App\User;
//$usuario = new User();
//$usuario->Mandar();
?>


<div class='dashboard-content'>
    <div class='container'>
        <div class="row">
            <div class="col-3">
                <div class='card'>
                    <div class='card-header'>
                        <h1>hola <?php
                                echo $personal['nombres'];
                                ?></h1>
                    </div>
                    <div class='card-body'>
                        <p><?php
                                echo $personal['nombres'];
                                ?> : veterinario</p>
                        <p>ni sint autem.</p>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <div class='card'>
                    <div class='card-header'>
                        <h1>hola juan</h1>
                    </div>
                    <div class='card-body'>
                        <p>hola juan: veterinario</p>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusantium eos at voluptate dolores vel. Velit, facilis deserunt iure explicabo soluta blanditiis, porro saepe alias, maiores ducimus nam dolores odio a.
                            Repudiandae a sapiente sit eveniet quisquam ea magni voluptatem nobis dolore doloremque? Quo eos ipsum nisi ad blanditiis temporibus et mollitia, distinctio, fugiat veritatis aut, vitae voluptates odit? Eveniet, minus!
                            Debitis voluptate quo adipisci nemo maxime quibusdam maiores fugiat aliquam, rem impedit eligendi vero tempore atque voluptates vel amet velit, nulla laboriosam animi officiis non? Ut minima fugiat deserunt quas?
                            Ipsam eaque hic aut similique incidunt qui, voluptas ullam doloremque expedita eligendi pariatur veritatis magnam iusto ea placeat cupiditate sint labore beatae quasi quia repellendus totam. Minus nobis nihil explicabo.
                            Saepe officia repellat modi, id, dolores sed sit libero nesciunt provident officiis voluptatibus eligendi soluta molestiae sunt. Atque numquam laborum corrupti rerum, officiis temporibus maiores adipisci voluptatum, magni sint autem.</p>
                    </div>
                </div>
            </div>
        </div>
        <button onclick="Swal.fire({
  title: 'Macota registrado correctamente',
  
  icon: 'success'
});">hola</button>
        
    
   
</div>


<?php
require 'template/footer.php';
?>
