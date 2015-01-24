<?php

namespace Acme\BlogBundle\Handler;

use Acme\BlogBundle\Model\UsuarioInterface;
use Acme\BlogBundle\Entity\Usuario;

interface UsuarioHandlerInterface
{

    public function get($matricula);

    public function all($limite = 5, $posicao_inicio = 0);

    public function post(array $parametros);
    
    public function delete(Usuario $usuario);
    
    public function put(UsuarioInterface $usuario, array $parametros);

    public function patch(UsuarioInterface $usuario, array $parametros);
}