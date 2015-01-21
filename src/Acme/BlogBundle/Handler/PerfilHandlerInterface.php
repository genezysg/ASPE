<?php

namespace Acme\BlogBundle\Handler;

use Acme\BlogBundle\Model\PerfilInterface;

interface PerfilHandlerInterface
{
    /**
     * Retorna um perfil por código
     *
     * @api
     *
     * @param mixed $codigo
     *
     * @return PerfilInterface
     */
    public function get($codigo);

    /**
     * Lista de Perfis.
     *
     * @param int $limite
     * @param int $posicao_inicio
     *
     * @return array
     */
    public function all($limite = 5, $posicao_inicio = 0);

    /**
     * Cria o perfil.
     *
     * @api
     *
     * @param array $parametros
     *
     * @return PerfilInterface
     */
    public function post(array $parametros);

    /**
     * Edita um perfil.
     *
     * @api
     *
     * @param PerfilInterface  $perfil
     * @param array           $parametros
     *
     * @return PerfilInterface
     */
    public function put(PerfilInterface $perfil, array $parametros);

    /**
     * Atualiza parcialmente um perfil.
     *
     * @api
     *
     * @param PerfilInterface  $perfil
     * @param array           $parametros
     *
     * @return PerfilInterface
     */
    public function patch(PerfilInterface $perfil, array $parametros);
}