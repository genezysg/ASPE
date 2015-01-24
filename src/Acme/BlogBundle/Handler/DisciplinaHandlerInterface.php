<?php

namespace Acme\BlogBundle\Handler;

use Acme\BlogBundle\Model\DisciplinaInterface;
use Acme\BlogBundle\Entity\Disciplina;

interface DisciplinaHandlerInterface
{    
    public function get($codigo);
     
    public function all($limite = 5, $posicao_inicio = 0);

    public function post(array $parametros);
    
    public function delete(Disciplina $disciplina);
    
    public function put(DisciplinaInterface $disciplina, array $parametros);

    public function patch(DisciplinaInterface $disciplina, array $parametros);
}