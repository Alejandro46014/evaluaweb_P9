<?php

class UsuariosModelo {
	
	protected $conexion;
	protected $usuario;
	protected $id;
	protected $nombre_usuario;
	protected $apellidos_usuario;
	protected $apellido1;
	protected $apellido2;
	protected $fecha_nacimiento_usuario;
	protected $pais_usuario;
	protected $fecha_alta_usuario;
	protected $tipo_usuario;
	protected $activo;
	protected $password;
    protected $email;
	
	/*---------constructor*/
	public function usuariosModelo(){
		
            
	}
	
	/*---------------getters---------------*/
	public function getId(){
		
		return($this->id);
	}
	
	public function getNombreUsuario(){
		
		return($this->nombre_usuario);
	}
	
	public function getApellidosUsuario(){
		
		return($this->apellidos_usuario);
	}
	
	

	
	
	
	public function getFechaNacimiento(){
		
		return($this->fecha_nacimiento_usuario);
	}
	
	
	public function getPais(){
		
		return($this->pais_usuario);
	}
	
	public function getFechaAltaUsuario(){
		
		return($this->fecha_alta_usuario);
	}
	
	public function getTipoUsuario(){
		
		return($this->tipo_usuario);
	}
	
	/*-----------------------setters--------------------------*/
	
	public function setId($id){
		$this->id=$id;
	}
	
	public function setNombreUsuario($nombre){
		
		$this->nombre_usuario=$nombre;
	}
	
	
	
	public function setApellidos($apellidos){
		
		$this->apellidos_usuario=$apellidos;
	}
	
	public function setApellido1($apellido){
		
		$this->apellido1=$apellido;
	}
	public function setApellido2($apellido){
		
		$this->apellido2=$apellido;
	}
	
	
	public function setFechaNacimiento($fecha_nacimiento){
		
		$this->fecha_nacimiento_usuario=$fecha_nacimiento;
	}
	
	
	public function setPais($pais){
		
		$this->pais_usuario=$pais;
	}
	public function setEmail($email){
		
		$this->email=$email;
	}
	
	public function setFechaAltaUsuario($fecha_alta){
		
		$this->fecha_alta_usuario=$fecha_alta;
	}
	
	public function setTipoUsuario($tipo_usuario){
		
		$this->tipo_usuario=$tipo_usuario;
	}
	
	public function setActivoUsuario($activo){
		
		$this->activo=$activo;
	}
	public function setPasswordUsuario($password){
		
		$this->password=$password;
	}
        
        /*---------------getTodo---------------------*/
        
        public function getTodo(){
            
            require_once 'ConectarModelo.php';
            try{
            $conexion= ConectarModelo::conexion();
            $sql="SELECT * FROM usuarios ORDER BY id_usuario";
            $consulta=$conexion->prepare($sql);
            $consulta->execute();
			$resultado=$consulta->fetchAll();
				
				$consulta->closeCursor();
			
				
			}catch(PDOException $e){
				
				die ("Error ".$e->getMessage());
			echo("Linea de error ".$e->getLine());
			}
			
			$conexion=null;
			
            return $resultado;
        }
	
	/*----------------------------getById----------------------------------*/
	
	
	public function getById($id){
		try{
		$conexion=ConectarModelo::conexion();
		$sql="SELECT * FROM usuarios INNER JOIN valoraciones ON id_usuario=usuarios_id_usuario
		INNER JOIN productos ON productos_id_producto=id_productos WHERE id_usuario=:id";
		
		$consulta=$conexion->prepare($sql);
		
		$consulta->bindParam(':id',$id,PDO::PARAM_INT);
		
		$consulta->execute();
		
		$resultado=$consulta->fetchAll();
			
			$consulta->closeCursor();
			
		}catch(PDOException $e){
			
			die ("Error ".$e->getMessage());
			echo("Linea de error ".$e->getLine());
		}
		
		$conexion=null;
		
		return($resultado);
		
	}
        /*-----------------GUARDAR USUARIO-----------------------*/
	
	public function crearUsuario(){
		
		require_once("ConectarModelo.php");
		
		
		
		
		
		$usuario=new UsuariosModelo();
		
		
		$apellido1=$_POST['apellido1'];
		$apellido2=$_POST['apellido2'];
		$apellidos=$apellido1." ".$apellido2;
		
		$usuario->setNombreUsuario($_POST['nombre']);
		$usuario->setApellidos($apellidos);
		$usuario->setApellido2($_POST['apellido2']);
		$usuario->setFechaNacimiento($_POST['fecha_nacimiento']);
		$usuario->setPais($_POST['pais']);
        $usuario->setEmail($_POST['email']);
		$usuario->setFechaAltaUsuario(date("Y-m-d"));
		$usuario->setActivoUsuario("Si");
		$usuario->setTipoUsuario("usuario_novel");
		$usuario->setPasswordUsuario($_POST['password']);
		
		$nombre=$usuario->nombre_usuario;
		$apellidos=$usuario->getApellidos();
		
		
		
		$fecha_nacimiento=$usuario->fecha_nacimiento_usuario;
		$fecha_alta=$usuario->fecha_alta_usuario;
		
		/*----------------MAYOR DE EDAD----------------------------*/
		
		$diff=strtotime($fecha_alta)-strtotime($fecha_nacimiento);
		$anys = floor($diff / (365*60*60*24));
		
		$pais=$usuario->pais_usuario;
		$email=$usuario->email;
		
		$activo=$usuario->activo;
		$tipo_usuario=$usuario->tipo_usuario;
		$password=$usuario->password;
		$rpassword=$_POST['rpassword'];
		
		
		
		
/*-----------------COMPROBAMOS TODOS LOS CAMPOS INTRODUCIDOS-------------------------*/
		
			$mal=false;
			$mal1=false;
			$mal2=false;
			$mal3=false;//declaro la variable $mal para controlar los fallos de la distintas condiciones
			$mal4=false;
			$mal5=false;
			$mal6=false;
			$mal7=false;
			$mal8=false;
			$mal9=false;
		
		
			$patron="/[a-zA-Z\s]/";
			
		if(empty($nombre) || empty($apellido1) || empty($apellido2)){//compruebo que el campo este lleno
			
			echo '<p>Los campos nombre, primer apellido y segundo apellido son obligatorios</p>';
			
			$mal=true;
		}
		if(!preg_match($patron,$nombre) || !preg_match($patron,$apellido1) || !preg_match($patron,$apellido2)){//compruebo el patron ceado para el campo
			
			echo '<p>Los campos nombre, primer apellido y segundo apellido solo pueden contener caracteres alfabéticos</p>';
			
			$mal2=true;
		}
		
		if(empty($password) || empty($rpassword)){//compruebo el patron ceado para el campo
			echo("<p>Los campos contraseña y repetir contraseña son obligatorios</p>");
			$mal3=true;
		}
			if($password!=$rpassword){//compruebo el patron ceado para el campo
			echo("<p>Los campos contraseña y repetir contraseña no coinciden</p>");
			$mal4=true;
		}
	$patron="/^(?=.*\d)(?=.*)(?=.*[A-Z])(?=.*[a-z])\S{8}$/";//contraseña longitud 8 con mayúsculas, minúsculas y dígitos
		
		if(!preg_match($patron,$password)){//compruebo que el campo cumpla el patron establecido
			echo("<p>El campo contraseña no es valido, consulte la leyenda</p>");
			$mal5=true;
		}

		$patron="/[a-zA-Z\s]/";		
		
		if(empty($pais) || !preg_match($patron,$pais)){//compruebo que el campo este lleno y respete el patron
			
			echo '<p>El campo pais es obligatorio</p>';
			
			$mal6=true;
		}
			
		
		
		if(empty($fecha_nacimiento)){//compruebo que el campo este lleno
			
			echo '<p>El campo fecha de nacimiento es obligatorio</p>';
			
			$mal7=true;
		}
		
			if(empty($email)){//compruebo que el campo este lleno
				
				echo '<p>El campo correo electrónico obligatorio</p>';
			
			$mal8=true;
		}
					if($anys < 18){//compruebo si el usuario es mayor de edad
						
						echo '<script type="text/javascript">
				alert("Debe ser mayor de edad para darse de alta");
				</script>';
			
			$mal9=true;
		}
		
			
			
			
		if($mal || $mal1 ||$mal2 || $mal3 || $mal4 || $mal5 ||$mal6 || $mal7 || $mal8 || $mal9){//con cualquiera de las variables añadimos el aviso final o tenemos exito
			echo '<script type="text/javascript">
				alert("Verifique los campos he intentelo de nuevo");
				</script>';
			
		}else{

		try{
		$conexion=ConectarModelo::conexion();
                
		$sql="INSERT INTO usuarios (nombre_usuario,apellidos_usuario,email_usuario,fecha_nacimiento_usuario,pais_usuario,fecha_alta_usuario,activo,password,tipo_usuarios_tipo_usuario) VALUES (:nombre,:apellidos,:email,:fecha_nacimiento,:pais,:fecha_alta,:activo,:password,:tipo_usuario)";
		$consulta=$conexion->prepare($sql);
		
			$consulta->bindParam(':nombre',$nombre,PDO::PARAM_STR);
			$consulta->bindParam(':apellidos',$apellidos,PDO::PARAM_STR);
			$consulta->bindParam(':fecha_nacimiento',$fecha_nacimiento,PDO::PARAM_STR);
			$consulta->bindParam(':pais',$pais,PDO::PARAM_STR);
			$consulta->bindParam(':fecha_alta',$fecha_alta,PDO::PARAM_STR);
			$consulta->bindParam(':activo',$activo,PDO::PARAM_STR);
			$consulta->bindParam(':password',$password,PDO::PARAM_STR);
			$consulta->bindParam(':email',$email,PDO::PARAM_STR);
			$consulta->bindParam(':tipo_usuario',$tipo_usuario,PDO::PARAM_STR);
			
			$resultado=$consulta->execute();
			$id=$conexion->lastInsertId();
			$usuario->id=$id;
			
			
			if($resultado){
				
				echo('<script type="text/javascript">
				alert("El usuario se dio de alta correctamente ");
				</script>');
			}else{
				echo('<script type="text/javascript">
				alert("Hubo un error durante el proceso de alta, contacte con el administrador ");
				</script>');
			}
			
			
			$consulta->closeCursor();
			
		
		}catch(PDOException $e){
			
			die ("Error ".$e->getMessage());
			echo("Linea de error ".$e->getLine());
			
		}
		$conexion=null;
		
		return($usuario);
	}
	}
	
	/*--------------------ACTUALIZAR TIPO USUARIO---------------------*/
	
	public function actualizarTipoUsuario($tipo_usuario){
		
		require_once("ConectarModelo.php");
		try{
			$conexion=ConectarModelo::conexion();
			$sql="UPDATE usuarios SET (usuarios_tipo_usuario) VALUES (:tipo_usuario)";
			
			$consulta=$conexion->prepare($sql);
			
			$consulta->bindParam(':tipo_usuario',$tipo_usuario,PDO::PARAM_STR);
			
			$resultado=$consulta->execute();
			
		}catch(PDOException $e){
			
			die ("Error ".$e->getMessage());
			echo("Linea de error ".$e->getLine());
		}
		
		return($resultado);
	}
	/*------------------DARSE DE BAJA-----------------*/
	
	public function darse_baja(){
		
		require_once("ConectarModelo.php");
		
		$id=$this->id;
	try{	
		$conexion= ConectarModelo::conexion();
			
		$sql="UPDATE usuarios SET activo='No' WHERE id=:id";
		
		$consulta=$conexion->prepare($sql);
		
		$consulta->bindParam(':id',$id,PDO::PARAM_INT);
		
		$resultado=$consulta->execute();
		if($resultado){
				
				echo('<script type="text/javascript">
				alert("El usuario se dio de baja correctamente ");
				</script>');
			}else{
				echo('<script type="text/javascript">
				alert("Hubo un error durante el proceso de baja, contacte con el administrador ");
				</script>');
			}
		
		$consulta->closeCursor();
		
		
		
	}catch(PDOException $e){
		
		die ("Error ".$e->getMessage());
			echo("Linea de error ".$e->getLine());
	}
		$conexion=null;
		
		return($resultado);
		
	}
	
	/*---------------------VALORAR--------------------*/
	
	public function valorar(){
		
		require_once("ConectarModelo.php");
		
		$id_usuario=$this->id;
		$tipo_usuario=$this->tipo_usuario;
		$id_producto=$_POST['id_producto'];
		$valoracion=$_POST['valoración'];
		$comentario=$_POST['comentario'];
		
		if($tipo_usuario=="Usuario_novel"){
			
			$numero_votaciones=1;
			$valoracion=$valoracion;
			
		}elseif($tipo_usuario=="Usuario_experto"){
			
			$numero_votaciones=2;
			$valoracion=$valoracion * 2;
			
		}elseif($tipo_usuario=="Usuario_profesional"){
			
			$numero_votaciones=3;
			$valoracion=$valoracion * 3;
		}
		
		try{
		$conexion=ConectarModelo::conexion();
		
		$sql="INSERT INTO valoraciones (usuarios_id_usuario,productos_id_producto,valor_votación,numero_votaciones,comentario,bloqueada) VALUES (:id_usuario,:id_producto,:valor_votacion,:numero_votaciones,:comentario,'No)";
		
		$consulta=$conexion->prepare($sql);
		
		$consulta->bindParam(':id_usuario',$id_usuario,PDO::PARAM_INT);
		$consulta->bindParam(':id_producto',$id_producto,PDO::PARAM_INT);
		$consulta->bindParam(':valor_votacion',$valoracion,PDO::PARAM_INT);
		$consulta->bindParam(':numero_votaciones',$numero_votaciones,PDO::PARAM_INT);
		$consulta->bindParam(':comentario',$comentario,PDO::PARAM_STR);
		
		$resultado=$consulta->execute();
			
			if(!$resultado){
				
				echo('<script type="text/javascript">
				alert("Hubo un error durante el proceso de votación, contacte con el administrador ");
				</script>');
			}
		
		$consulta->closeCursor();
			
		}catch(PDOException $e){
			
			die ("Error ".$e->getMessage());
			echo("Linea de error ".$e->getLine());
			
		}
		
		$conexion=null;
		
		return($resultado);
	}
	
	/*-------------------LOGIN------------------------------*/
	
	public function login(){
		
		require_once("ConectarModelo.php");
		
		$nombre_usuario=$_POST['email_usuario'];
		$password_usuario=$_POST['password_usuario'];
		
		if(empty($nombre_usuario) || empty($password_usuario)){
			
			echo('<script type="text/javascript">
				alert("Los campos nombre y contraseña estan vacios ");
				</script>');
			
		}else{
			
			try{
				
				$conexion=ConectarModelo::conexion();
				
				$conexion->beginTransaction();//comienza la transacción

				$sql="SELECT id_usuario,nombre_usuario,apellidos_usuario,pais_usuario,email_usuario,tipo_usuarios_tipo_usuario,fecha_alta_usuario
				,activo FROM usuarios WHERE email_usuario=:email AND password=:password";
				
				$consulta=$conexion->prepare($sql);
				
				$consulta->bindParam(':email',$nombre_usuario,PDO::PARAM_STR);
				$consulta->bindParam(':password',$password_usuario,PDO::PARAM_STR);
				
				$consulta->execute();
				
				$resultado=$consulta->fetch(PDO::FETCH_ASSOC);
				$id=$resultado['id_usuario'];
				
				$numero_filas=$consulta->rowCount();
				
				$consulta->closeCursor();
				
				/*---------------Select valoraciones-----------------*/
				
				$sql="SELECT valor_votacion FROM valoraciones WHERE usuarios_id_usuario=:id";
				$consulta=$conexion->prepare($sql);
				
				$consulta->bindParam(':id',$id,PDO::PARAM_INT);
				$consulta->execute();
				$numero_votaciones=$consulta->rowCount();
				$consulta->closeCursor();
				
				$conexion->commit();
				if($numero_filas==0){
					
					echo('<script type="text/javascript">
				alert("No existe ningun usuario con esas credenciales ");
				</script>');
					
				}else{
					
					
					
					$usuario= new UsuariosModelo();
					
					$usuario->setId($resultado['id_usuario']);
					$usuario->setNombreUsuario($resultado['nombre_usuario']);
					$usuario->setApellidos($resultado['apellidos_usuario']);
					
					$usuario->setEmail($resultado['email_usuario']);
					$usuario->setFechaAltaUsuario($resultado['fecha_alta_usuario']);
					$usuario->setActivoUsuario($resultado['activo']);
					$usuario->setTipoUsuario($resultado['usuarios_tipo_usuario']);
					$usuario->setPais($resultado['pais_usuario']);
					
					$fecha_alta=$usuario->fecha_alta_usuario;
					$fecha_actual=date("Y-m-d");
					
					$diff=abs(strtotime($fecha_actual)- strtotime($fecha_alta));
					$antiguedad= floor($diff / (30*60*60*24));
					
					if($antiguedad > 6 && $numero_votaciones > 25 && $usuario->tipo_usuario=="Usuario_novel"){
						
						if($usuario->actualizarTipoUsuario("Usuario_experto")){
						$usuario->setTipoUsuario("Usuario_experto");
							
							session_start();
						$_SESSION['usuario']=serialize($usuario);
						
						echo('<script type="text/javascript">
								alert("Su perfil se actualizó, ahora es usted un usuario experto");
							</script>');
						
						require_once("vistas/indexVista.php");

						}
						
						
					}elseif($antiguedad > 24 && $numero_votaciones > 50 && $usuario->tipo_usuario=="Usuario_experto"){
						
						if($usuario->actualizarTipoUsuario("Usuario_profesional")){
						$usuario->setTipoUsuario("Usuario_profesional");
						
							session_start();
						$_SESSION['usuario']=serialize($usuario);
							
						echo('<script type="text/javascript">
								alert("Su perfil se actualizó, ahora es usted un usuario profesional");
							</script>');
						
						require_once("vistas/indexVista.php");
							
							
						}
					}elseif($antiguedad < 6 && $numero_votaciones < 25 && $usuario->tipo_usuario=="Usuario_novel"){
						
						session_start();
						$_SESSION['usuario']=serialize($usuario);
						require_once("vistas/indexVista.php");
					}
					
				
					
				}
				
				
			}catch(PDOException $e){
				
				die ("Error ".$e->getMessage());
				echo("Linea de error ".$e->getLine());
				
				$error=$conexion->rollBack();
				
				if($error){
					
					
				}
			}
		}
		return($usuario);
	}
	
}
