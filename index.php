<?php 
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/Seguridad.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/Utiles.php';

if(isset($_SESSION['idusr'])) {
    $titulo = Seguridad::PermisoOpcion($_SESSION['idusr'], $_SERVER['REQUEST_URI']);
    
    try{
        $menuStr = Seguridad::generaMenu($_SESSION['idusr'], 19);
    }catch(Exception $ex){
        $menuStr = $ex->getMessage();
    }
}
try{
    $datoSis = Seguridad::ConfiguracionDefalut(19);
}catch(Exception $ex){
    echo '<p>'.$ex->getMessage().'</p>';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <?php 
    if(isset($datoSis))
        echo $datoSis['css']; 
    ?>
    <script type="text/javascript">
        <?php 
        if(isset($datoSis))
            echo $datoSis['js']; 
        ?>
    </script>
    <link rel="shortcut icon" href="images/favicon.png" />
    <script type="text/javascript" src="../librerias/javascript/jquery-1.7.2.js"></script> 
    <script type="text/javascript" src="../librerias/javascript/jquery-ui-1.8.9.custom.min.js"></script>
    <script type="text/javascript" src="../librerias/javascript/skrn-datagrid/datagrid3.js"></script>
    <script type="text/javascript" src="../librerias/javascript/jquery.pulsate.js"></script>
    <script type="text/javascript" src="../librerias/javascript/datagrid2.js"></script>
    <script type="text/javascript" src="js/index.js"></script>
    <title>
        <?php
            if(isset($titulo)){
                if($titulo['entrar'] == Seguridad::$AVIALABLE)
                echo $datoSis['nombre'].' - '.$titulo['titulo'];
            }else
                echo $datoSis['nombre']; 
        ?>
    </title>
</head>
<body>
<div id="mensajin"></div>
<header id="header">
    <div id="cabecera">
    	<div id="logo"></div>
        <div class="apps">
            <?php
                if(isset($_SESSION['idusr']))
                    echo Seguridad::GetSistemasAsociados($_SESSION['idusr'], Utiles::NameServer());
                ?>
        </div>
	<div id="encabezado">
		<div class="pic">
			<?php 
				if(isset($_SESSION['codigoRH'])){
					$src = Utiles::foto($_SESSION['codigoRH']);
					echo '<img src="'.$src['link'].'" width="100" height="100"/>';
				}
			?>
		</div>
                <div class="contdatosusuario">
				<p class="datosusuario">
				<?php 
					if(isset($_SESSION['nombre']))
						echo "<b>Bienvenido</b> ".$_SESSION['nombre']; 
				?>
				</p>
				<p class="datosusuario">
				<?php 
					if(isset($_SESSION['unidad']))
						echo "Unidad: ".$_SESSION['unidad']; 
				?>
				</p>
				<p class="datosusuario">
				<?php 
					if(isset($_SESSION['puesto']))
						echo "Puesto: ".$_SESSION['puesto']; 
				?>
				</p>
                </div>
                <div class="contdatosusuario">
                    <p id="indexFechaHora" class="datosusuario titulo"></p>
                    <p class="datosusuario titulo"><b><?php echo $datoSis['nombre']; ?></b></p>
                    <p class="datosusuario titulo"><b>
                        <?php 
                            if(isset($titulo))
                                if($titulo['entrar'] == Seguridad::$AVIALABLE)
                                    echo $titulo['titulo']; 
                        ?>
                        </b>
                    </p>
                </div>
            </div>
    </div>
</header>

<div id="page">
    <div id="pagina">
        <div id="sidebar">
            <ul class="menu">
                <?php 
                    if(isset($menuStr))
                        echo $menuStr;
                ?>
            </ul>
        </div>
        <div id="contenido">
            <p id= "mensaje"></p>
            <?php
                if(isset($_SESSION['idusr'])){
                    if(isset($_GET['o']) && isset($_GET['m'])){
                        $vista = 'piece/'.$_GET['m'].'/'.$_GET['o'].'.phtml';
                        if(!file_exists($vista))
                            echo '<p class="error">La página no existe</p>';
                        elseif($titulo['entrar'] == Seguridad::$NOT_FOUND)
                            echo '<p class="warning">La página no ha sido registrada</p>';
                        elseif($titulo['entrar'] == Seguridad::$AVIALABLE)
                                include($vista);
                        elseif($titulo['entrar'] == Seguridad::$LOCKED)
                            echo '<p class="lock">No tiene permisos para visualizar esta opcion</p>';
                    }
                }else 
                    include('piece/sys/login.phtml');
            ?>
        </div>
    </div>
    <div id="openhelper"></div>
    <div id="helper">
        <div class="view">
            <h1>Ayuda</h1>
            <div class="contenido">
                
            </div>
        </div>
    </div>
</div>
<footer id="footer">
    <div>
        <p><?php echo $datoSis['nombre']; ?> - Registro General de la Propiedad ©</p>
    </div>
</footer>
</body>
</html>
