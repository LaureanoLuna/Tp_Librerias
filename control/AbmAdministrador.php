<?php
class AbmAdministrador{

/**
 * Espera como parametro un arreglo asociativo donde las claves coinciden 
 * con los nombres de las variables instancias del objeto
 * @param array $param
 * @return administrador
 */
private function cargarObjeto($param){
    $obj = null;  
    
    if( array_key_exists('ID',$param)){
        $obj = new administrador();
        $x = $obj->listar(" ID='".$param['ID']."'");
              
        $obj->setear($x[0]->getId(), $x[0]->getMail(), $x[0]->getContrasenia());
    }
    return $obj;
}

/**
 * Espera como parametro un arreglo asociativo donde las claves coinciden 
 * con los nombres de las variables instancias del objeto que son claves
 * @param array $param
 * @return administrador
 */
private function cargarObjetoConClave($param){
    $obj = null;
    if( isset($param['ID']) ){
        $obj = new administrador();
        $obj->setear($param['ID'], null, null, null);
    }
    return $obj;
}


/**
 * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
 * @param array $param
 * @return boolean
 */
private function seteadosCamposClaves($param){
   
    $resp = false;
 
    if (isset($param['ID'])){
        $resp = true;
    }
    return $resp;
}

/**
 * 
 * @param array $param
 */
public function alta($param){
    $resp = false;
        
    $obj = new administrador();
    $obj->setear($param['ID'], $param['Mail'],$param['Contrasenia']);
    if ($obj!=null and $obj->insertar()){
        $resp = true;
    }
    return $resp; 
}
/**
 * permite eliminar un objeto 
 * @param array $param
 * @return boolean
 */
public function baja($param){
    $resp = false;
    if ($this->seteadosCamposClaves($param)){
        $esteObj = $this->cargarObjetoConClave($param);
        if ($esteObj!=null and $esteObj->eliminar()){
            $resp = true;
        }
    }
    
    return $resp;
}

/**
 * permite modificar un objeto
 * @param array $param
 * @return boolean
 */
public function modificacion($param){
    $resp = false;
    if ($this->seteadosCamposClaves($param)){// devuelve true

        $esteObj = $this->cargarObjeto($param);// devuelve el objeto de con esa Patente
        
        if($esteObj!=null and $esteObj->modificar()){
            $resp = true;
        }
    }
    return $resp;
}

/**
 * permite buscar un objeto
 * @param array $param
 * @return boolean
 */
public function buscar($param){
    $where = "" ;
    if ($param != NULL){
        if  (isset($param['ID'])){
            $where=" ID ='".$param['ID']."'";
        }
        if  (isset($param['Mail'])){
            $where=" Mail ='".$param['Mail']."'";
        }
        if  (isset($param['Contrasenia'])){
            $where=" Contrasenia ='".$param['Contrasenia']."'";
        }
    }

    $arreglo = administrador::listar($where);
    
    return $arreglo;
}

}
?>