<?php

class AdministradorModelo extends UsuariosModelo{
	
	public function __construct(){
		parent::__construct();
		$this->tipo_usuario="Administrador";
	}
	
	/*---------------------------LISTAR USUARIOS ACTIVOS O NO--------------------------*/
	
	public function listarUsuarios($activo){
		require_once("ConectarModelo.php");
		try{
			$conexion=ConectarModelo::conexion();
			$activo=$_POST['activo'];
			$sql="SELECT * FROM usuarios WHERE acivo=:activo";
			
			$consulta=$conexion->prepare($sql);
			
			$consulta->binParam(':activo',$activo,PDO::PARAM_STR);
			$consulta->execute();
			$resultado=$consulta->fetchAll();
			
			$consulta->closeCursor();
			
		}catch(PDOException $e){
			
			die("No se pudo conectar con la BBDD ".$e->getMessage());
			echo("Linea de error ".$e->getLine());
		}
		
		$conexion=null;
		
		return($resultado);
	}
	/*---------------------------BLOQUEAR VALORACIÓN ADMINISTRADOR---------------------*/
	
	public function bloquearValoracion(){
	
	
		require_once("ConectarModelo.php");
		try{
			$bloqueada=$_POST['bloqueada'];
			$conexion=ConectarModelo::conexion();
			$sql="UPDATE valoraciones SET bloqueada=:bloqueada";
			
			$consulta=$conexion->prepare($sql);
			
			$consulta->bindParam(':bloqueada',$bloqueada,PDO::PARAM_STR);
			
			$resultado=$consulta->execute();
			
			$consulta->closeCursor();
			
			if($resultado){
				
				echo '<script type="text/javascript">
				alert("La valoración se bloqueó correctamente");
				</script>';
			
				
			}else{
				
				echo '<script type="text/javascript">
				alert("La valoración no se pudo bloquear");
				</script>';
			
				
			}
			
		}catch(PDOException $e){
			
			die("No se pudo conectar con la BBDD ".$e->getMessage());
			echo("Linea de error ".$e->getLine());
		}
		
		$conexion=null;
		
		return($resultado);
}

	
	/*---------------------------BLOQUEAR USUARIO ADMINISTRADOR---------------------*/
	
	public function bloquearUsuario(){
		
		require_once("ConectarModelo.php");
		try{
			
			$activo=$_POST['activo'];
			$conexion=ConectarModelo::conexion();
			$sql="UPDATE usuarios SET activo=:activo";
			
			$consulta=$conexion->prepare($sql);
			
			$consulta->bindParam(':activo',$activo,PDO::PARAM_STR);
			
			$resultado=$consulta->execute();
			
			$consulta->closeCursor();
			
			if($resultado){
				
				echo '<script type="text/javascript">
				alert("El usuario se modificó correctamente");
				</script>';
			
				
			}else{
				
				echo '<script type="text/javascript">
				alert("El usuario no se pudo modificar");
				</script>';
			
				
			}
			
		}catch(PDOException $e){
			
			die("No se pudo conectar con la BBDD ".$e->getMessage());
			echo("Linea de error ".$e->getLine());
		}
		
		$conexion=null;
		
		return($resultado);
	}
	
	/*---------------------------NUEVO PRODUCTO ADMINISTRADOR---------------------*/
	
	
	public function nuevoProductoAdministrador(){
		
		
		require_once("ConectarModelo.php");
		require_once("ProductosModelo.php");
		try{
			$conexion=ConectarModelo::conexion();
			
			$nombre_producto=$_POST['nombre_producto'];
			$anio_lanzamiento=$_POST['anio_lanzamiento'];
			$sinopsis=$_POST['sinopsis'];
			$reparto=$_POST['reparto'];
			$director=$_POST['director'];
			$genero=$_POST['genero'];
			
			$sql="INSERT INTO productos (nombre_producto,anio_lanzamiento,sinopsis,reparto,director,ccategorias_productos_genero) VALUES(:nombre,:anio,:sinopsis,:reparto,:director,:genero)";
			
			$consulta=$conexion->prepare($sql);
			
			$consulta->bindParam(':nombre',$nombre_producto,PDO::PARAM_STR);
			$consulta->bindParam(':anio',$anio_lanzamiento,PDO::PARAM_STR);
			$consulta->bindParam(':sinopsis',$sinopsis,PDO::PARAM_STR);
			$consulta->bindParam(':reparto',$reparto,PDO::PARAM_STR);
			$consulta->bindParam(':director',$director,PDO::PARAM_STR);
			$consulta->bindParam(':genero',$genero,PDO::PARAM_STR);
			
			$resultado=$consulta->execute();
			$id=$consulta->lastInsertId();
			
			$consulta->closeCursor();
			
			if($resultado){
				
				$producto=new ProductosModelo();
				
				$producto->setIdProducto($id);
				$producto->setNombreProducto($nombre_producto);
				$producto->setAnioLanzamiento($anio_lanzamiento);
				$producto->setSinopsis($sinopsis);
				$producto->setReparto($reparto);
				$producto->setDirector($director);
				
				echo '<script type="text/javascript">
				alert("El producto se creo correctamente");
				</script>';
				
				return($producto);
			}else{
				
				echo '<script type="text/javascript">
				alert("No se pudo crear el producto");
				</script>';
			}
		}catch(PDOException $e){
			
			die("No se pudo conectar con la BBDD ".$e->getMessage());
			echo("Linea de error ".$e->getLine());
		}
		
		$conexion=null;
		
	}
	
	
	/*---------------------------ELIMINAR PRODUCTO POR ID---------------------*/
	
	public function eliminarProductoAdministrador($id){
		
		require_once("ConectarModelo.php");
		require_once("ProductosModelo.php");
		
		try{
			$conexion=ConectarModelo::conexion();
			$sql="DELETE productos WHERE id_productos=:id";
			
			$consulta=$conexion->prepare($sql);
			
			$consulta->bindParam(':id',$id,PDO::PARAM_INT);
			
			$resultado=$consulta->execute();
			
			
			
		}catch(PDOException $e){
			
			die("No se pudo conectar con la BBDD ".$e->getMessage());
			echo("Linea de error ".$e->getLine());
		}
		
		$conexion=null;
		
		
	}
	/*---------------------------LISTAR PRODUCTOS POR CATEGORIA---------------------*/
	
	public function listarProductosAdministrador($genero){
		require_once("ConectarModelo.php");
		require_once("ProductosModelo.php");
		
		try{
			
			$conexion=ConectarModelo::conexion();
			
			
			$sql="SELECT id_producto,nombre_producto,anio_lanzamiento,sinopsis,reparto,director,categorias_productos_genero,nombre_imagen,directorio_imagen, valor_votacion,numero_votaciones,comentario FROM productos INNER JOIN imagenes ON id_producto=imagenes.productos_id_producto INNER JOIN valoraciones ON id_producto=valoraciones.productos_id_producto WHERE categorias_productos_genero=:genero";
			
			$consulta=$conexion->prepare($sql);
			
			$consulta->bindParam(':genero',$genero,PDO::PARAM_STR);
			
			$consulta->execute();
			$resultado=$consulta->fetchAll();
			
			$consulta->closeCursor();
			
			
			
		}catch(PDOException $e){
			
			die("No se pudo conectar con la BBDD ".$e->getMessage());
			echo("Linea de error ".$e->getLine());
		}
		
		$conexion=null;
		
		return($resultado);
	}
}
	
?>