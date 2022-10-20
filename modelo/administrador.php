<?php 
class administrador {
private $id;
private $mail;
private $contrasenia;
private $mensajeoperacion;


public function __construct(){
    $this->id="";
    $this->mail="";
    $this->contrasenia="";
    $this->mensajeoperacion ="";
}
public function setear($id, $mail, $contrasenia) {
    $this->setId($id);
    $this->setMail($mail);
    $this->setContrasenia($contrasenia);
}

public function cargar(){
    $resp = false;
    $base=new BaseDatos();
    $sql="SELECT * FROM administrador WHERE ID = '".$this->getId()."'";
    if ($base->Iniciar()) {
        $res = $base->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                $row = $base->Registro();
                
                $this->setear($row['ID'], $row['Mail'], $row['Contrasenia'], $duenio);
            }
        }
    } else {
        $this->setMensajeoperacion("administrador->listar: ".$base->getError());
    }
    return $resp;
}

public function insertar(){
    $resp = false;
    $base=new BaseDatos();
    // Si lleva ID Autoincrement, la consulta SQL no lleva Patente. Y viceversa:
    $sql="INSERT INTO administrador(ID, Mail, Contrasenia)
        VALUES('"
        .$this->getId()."', '"
        .$this->getMail()."', '"
        .$this->getContrasenia()."');";
    if ($base->Iniciar()) {
        if ($base->Ejecutar($sql)) {
            $resp = true;
        } else {
            $this->setMensajeoperacion("administrador->insertar: ".$base->getError());
        }
    } else {
        $this->setMensajeoperacion("administrador->insertar: ".$base->getError());
    }
    return $resp;
}

public function modificar(){
    $resp = false;
    $base=new BaseDatos();
    
   
    $sql="UPDATE administrador SET Mail='".$this->getMail()."', Contrasenia='".$this->getContrasenia(). 
    "' WHERE ID='".$this->getId()."'";
   
    if ($base->Iniciar()) {
        if ($base->Ejecutar($sql)) {
            $resp = true;
         
        } else {
            $this->setMensajeoperacion("administrador->modificar: ".$base->getError());
        }
    } else {
        $this->setMensajeoperacion("administrador->modificar: ".$base->getError());
    }
    return $resp;
}

public function eliminar(){
    $resp = false;
    $base=new BaseDatos();
    $sql="DELETE FROM administrador WHERE ID='".$this->getId()."'";
    if ($base->Iniciar()) {
        if ($base->Ejecutar($sql)) {
            $resp = true;
        } else {
            $this->setMensajeoperacion("administrador->eliminar: ".$base->getError());
        }
    } else {
        $this->setMensajeoperacion("administrador->eliminar: ".$base->getError());
    }
    return $resp;
}

public static function listar($parametro=""){
    $arreglo = array();
    $base=new BaseDatos();
    $sql="SELECT * FROM administrador ";
   
    
    if ($parametro!="") {
        $sql.= "WHERE ".$parametro;
    }
    
    
    $res = $base->Ejecutar($sql);
    if($res>-1){
        if($res>0){
            while ($row = $base->Registro()){
                $obj= new administrador();
                
                $obj->setear($row['ID'], $row['Mail'], $row['Contrasenia']);
                array_push($arreglo, $obj);
            }
        }
    } else {
        $this->setMensajeoperacion("administrador->listar: ".$base->getError());
    }
    

    return $arreglo;
}
    
// -- Métodos get y set --

public function getId(){
    return $this->id;
}
public function setId($id){
    $this->id = $id;
    return $this;
}
public function getMail(){
    return $this->mail;
}
public function setMail($mail){
    $this->mail = $mail;
    return $this;
}
public function getContrasenia(){
    return $this->contrasenia;
}
public function setContrasenia($contrasenia){
    $this->contrasenia = $contrasenia;
    return $this; 
}
public function getMensajeOperacion(){
    return $this->mensajeoperacion;
}
public function setMensajeOperacion($mensaje){
    $this->mensajeoperacion = $mensaje;
    return $this;
}
} 


?>