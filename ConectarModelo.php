<?php

class ConectarModelo{
	
	public static function conexion(){
		
		try{
			
			$conexion=new PDO("mysql:host=localhost;dbname=evaluaweb","alejandro","3424");
			$conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			
		}catch(Exception $e){
				
			die ("Error ".$e->getMessage());
			echo("Linea de error ".$e->getLine());
			
		}
		return($conexion);
		
	}
}


