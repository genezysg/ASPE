<?php

namespace Acme\BlogBundle\Handler;

use Acme\BlogBundle\Model\PerfilInterface;
use Acme\BlogBundle\Entity\Perfil;

interface PerfilHandlerInterface
{

    public function get($codigo);

    public function all($limite = 5, $posicao_inicio = 0);

    public function post(array $parametros);

    public function delete(Perfil $perfil);

    public function put(PerfilInterface $perfil, array $parametros);

    public function patch(PerfilInterface $perfil, array $parametros);
}