<?php if ( ! defined('BASEPATH')) {exit('No direct script access allowed');}

class Cuentas extends CI_Controller{
 
    public function __construct() {
        parent::__construct();   
        $this->load->helper('url');
        $this->load->view('modules/menu/menu_contabilidad');

    }
    
    public function index() {
        $this->leer(1);
    }
    
     public function leer($estado){
        $this->load->model('contabilidad/catalogo/cuentas/Catalogo_cuentas_model');
        $this->load->library('table');
        $sql =$this->Catalogo_cuentas_model->leer($estado);
         
        $i=0; if($estado==1){
            
                    $data['link']='<a href="'.base_url().'index.php/contabilidad/catalogo/cuentas/cuentas/crear" class="btn btn-success">Crear Nueva Cuenta Contable</a> '.
                            '<a href="'.base_url().'index.php/contabilidad/catalogo/cuentas/cuentas/leer/0" class="btn btn-success">Listar Cuentas Inactivas</a>';
                    $encabezados = array('No°','Cuenta','Naturaleza de cuenta','Grupo de cuentas','Edicion','Inactivacion');
                    
                   while(count($sql)!=$i){ 
                       $id = $sql[$i]['idcuenta_contable'];
                       unset($sql[$i]['idcuenta_contable']);
                       array_unshift($sql[$i],$i);
                       array_push($sql[$i],'<a href="'.base_url().'index.php/contabilidad/catalogo/cuentas/cuentas/modificar/'.$id.'">Editar</a>');
                       array_push($sql[$i],'<a href="'.base_url().'index.php/contabilidad/catalogo/cuentas/cuentas/cambiar_estado/'.$id.'/0">Inactivar</a>');

                       $i++;
                   }
       
        }else 
            
            if($estado==0){
                    $data['link']='<a href="'.base_url().'index.php/contabilidad/catalogo/cuentas/cuentas/leer/1" class="btn btn-success">Listar Cuentas Activas</a>';
                    $encabezados = array('No°','Cuenta','Naturaleza de cuenta','Grupo de cuentas','Activacion');

                   while(count($sql)!=$i){ 
                       $id = $sql[$i]['idcuenta_contable'];
                       unset($sql[$i]['idcuenta_contable']);
                       array_unshift($sql[$i],$i);
                       array_push($sql[$i],'<a href="'.base_url().'index.php/contabilidad/catalogo/cuentas/cuentas/cambiar_estado/'.$id.'/1">Activar</a>');

                       $i++;
                    }
            }
       
       $estilo = array( 'table_open' =>'<table class="table table-striped table-bordered">');
       $this->table->set_template($estilo);
       $this->table->set_heading($encabezados);
       $data['cuentas'] = $sql;
        
      $this->load->view('contabilidad/catalogo/cuentas/lista_cuentas_view',$data);
        
    }
    
    
    
    public function crear() {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('cuenta_contable','Cuenta contable','required|min_length[3]');
        
        $tipocuenta = array('A'=>'Acreedora','D'=>'Deudora');
        $data['tipocuenta'] = $tipocuenta;
        
        $this->load->model('contabilidad/catalogo/grupo/Grupo_cuentas_model');
        $data['idgrupocuenta'] = $this->Grupo_cuentas_model->lista_grupo();
        
       
       if($this->input->post()){
           
           if( $this->form_validation->run()== TRUE){
               
                $this->load->model('contabilidad/catalogo/cuentas/Catalogo_cuentas_model');
                $this->Catalogo_cuentas_model->agregar();
                 
                 $this->leer(1);
           }else{
             $this->load->view('contabilidad/catalogo/cuentas/crea_cuentas_view',$data);
           }
           
       }else{
        
       $this->load->view('contabilidad/catalogo/cuentas/crea_cuentas_view',$data);
       }
        
    }
 
    public function modificar($idcatalogo) {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('cuenta_contable','Categoria','required|min_length[4]');
         $this->form_validation->set_rules('cuenta_contable','Categoria','required|min_length[4]');
         
        $data['idcatalogo']=$idcatalogo;
        
        $this->load->model('contabilidad/catalogo/cuentas/Catalogo_cuentas_model');
        $data['lista_por_id'] = $this->Catalogo_cuentas_model->encontrar_por_id($idcatalogo);
        
        $tipocuenta = array('A'=>'Acreedora','D'=>'Deudora');
        $data['tipocuenta'] = $tipocuenta;
        
        $this->load->model('contabilidad/catalogo/grupo/Grupo_cuentas_model');
        $data['idgrupocuenta'] = $this->Grupo_cuentas_model->lista_grupo();
       
        if($this->input->post()){
            
            if($this->form_validation->run() == TRUE){
                $this->load->model('contabilidad/catalogo/categoria/Catalogo_cuentas_model');
                $this->Catalogo_cuentas_model->modificar($idcatalogo);
                $this->leer(1);
          
            }else{
                $this->load->view('contabilidad/catalogo/cuentas/edita_cuentas_view',$data);
            }
            
        }else{
            $this->load->view('contabilidad/catalogo/cuentas/edita_cuentas_view',$data);
        }
    }
    
    public function cambiar_estado($idcategorias,$estado) {
        $this->load->model('contabilidad/catalogo/cuentas/Catalogo_cuentas_model');
        $this->Catalogo_cuentas_model->cambiar_estado($idcategorias,$estado);
        
        if($estado==1){$this->leer(0);}elseif($estado==0) {$this->leer(1);}
        
        
          
          
    }
    
}

/*Fin del archivo my_controller.php*/