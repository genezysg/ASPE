<?php

namespace Acme\BlogBundle\Handler;

use Acme\BlogBundle\Model\EmentaInterface;
use Acme\BlogBundle\Entity\Ementa;

interface EmentaHandlerInterface
{    
    public function get($codigo);
     
    public function all($limite = 5, $posicao_inicio = 0);

    public function post(array $parametros);
    
    public function delete(Ementa $ementa);
    
    public function put(EmentaInterface $ementa, array $parametros);

    public function patch(EmentaInterface $ementa, array $parametros);
}