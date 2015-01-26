<?php

namespace Acme\BlogBundle\Handler;

use Acme\BlogBundle\Model\TopicoInterface;
use Acme\BlogBundle\Entity\Topico;

interface TopicoHandlerInterface
{    
    public function get($codigo);
     
    public function all($limite = 5, $posicao_inicio = 0);

    public function post(array $parametros);
    
    public function delete(Topico $topico);
    
    public function put(TopicoInterface $topico, array $parametros);

    public function patch(TopicoInterface $topico, array $parametros);
}