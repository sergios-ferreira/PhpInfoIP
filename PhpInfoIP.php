<?php
 
/*
   </> Sérgio DEV - COPYLEFT © 2017 - sergio_tech@hotmail.com
   
   Script para verificar Informações de um IP/Domínio.
   
   #MODO USO:
   Setar o valor abaixo no lugar do endereço do google para verificar informações.
   
   #Version 0.1
   
*/
# Habilite apenas uma por vez...
define(ENDERECO_INFO, "www.google.com"); # Para pegar informações de um IP/Domínio Específico ...
#define(ENDERECO_INFO, $_SERVER['REMOTE_ADDR']); # Para pegar informações de um IP/Domínio de quem está acessando a página ...
    
    
class IPAPI {
    static $campos = 58361; // Acesse para Pegar o Número dos Campos: http://ip-api.com/docs/api:returned_values#field_generator         
    static $api = "http://ip-api.com/php/";
 
    /* 
       Variáveis disponíveis para consulta referente ao código 58361       
    */
    public $status, $country, $regionName, $city, $zip, $lat, $lon, $timezone, $isp, $query, $message;
 
    public static function consulta($q) 
    {
        $dados      = self::comunicacaoAPI($q);
        $resultados = new static;
        
        foreach($dados as $chave => $valor) {
            $resultados->$chave = $valor;
        }
        
        return $resultados;
    }
 
    
    private function comunicacaoAPI($q) {
        $q_hash = md5('ipapi'.$q);
   
        if(is_callable('curl_init')) {
            $c = curl_init();
            curl_setopt($c, CURLOPT_URL, self::$api.$q.'?fields='.self::$campos);
            curl_setopt($c, CURLOPT_HEADER, false);
            curl_setopt($c, CURLOPT_TIMEOUT, 30);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
            $resultado_array = unserialize(curl_exec($c));
            curl_close($c);
        } else {
            $resultado_array = unserialize(file_get_contents(self::$api.$q.'?fields='.self::$campos));
        }
     
        return $resultado_array;
    }
}
 
    $query    = IPAPI::consulta(ENDERECO_INFO);
    if($query->status == 'success')
    {    
       echo '<br>Consulta:<pre>';     
       echo " IP/Domínio: ".ENDERECO_INFO."<br/>"; 
       echo " Cidade: ".$query->city."<br/>";
       echo " Estado: ".$query->regionName."<br/>";
       echo " CEP: ".$query->zip."<br/>";    
       echo " País: ".$query->country."<br/>";
       echo " Mapa: <a target='_blank' href='https://maps.google.com.br/maps?&z=15&mrt=yp&t=k&q=".$query->lat.",".$query->lon." '>Ver no Google Maps</a>";            
    }
    else if($query->status == 'fail')
    {    
        die('[Erro na Consulta]: '.$query->message);
    }
    else 
    {
        die('[Erro na Consulta]: Site Indisponível');
    }    

 echo '<br><br>Debug: <pre>';
 var_dump($query);
 echo '</pre>';
?>
