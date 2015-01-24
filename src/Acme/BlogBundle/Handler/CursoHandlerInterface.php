<?php

namespace Acme\BlogBundle\Handler;

use Acme\BlogBundle\Model\CursoInterface;
use Acme\BlogBundle\Entity\Curso;

interface CursoHandlerInterface
{    
    public function get($codigo);
     
    public function all($limite = 5, $posicao_inicio = 0);

    public function post(array $parametros);
    
    public function delete(Curso $curso);
    
    public function put(CursoInterface $curso, array $parametros);

    public function patch(CursoInterface $curso, array $parametros);
}