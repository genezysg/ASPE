<?php

namespace Acme\BlogBundle\Handler;

use Acme\BlogBundle\Model\UsuarioInterface;
use Acme\BlogBundle\Entity\Usuario;

interface UsuarioHandlerInterface
{
    /**
     * Retorna um usuario por código
     *
     * @api
     *
     * @param mixed $matricula
     *
     * @return UsuarioInterface
     */
    public function get($matricula);

    /**
     * Lista de Usuarios.
     *
     * @param int $limite
     * @param int $posicao_inicio
     *
     * @return array
     */
    public function all($limite = 5, $posicao_inicio = 0);

    /**
     * Cria o usuario.
     *
     * @api
     *
     * @param array $parametros
     *
     * @return UsuarioInterface
     */
    public function post(array $parametros);
    
    /**
     * Delete a Page.
     *
     * @param PageInterface $page
     *
     */
    public function delete(Usuario $usuario);
    
    /**
     * Edita um usuario.
     *
     * @api
     *
     * @param UsuarioInterface  $usuario
     * @param array           $parametros
     *
     * @return UsuarioInterface
     */
    public function put(UsuarioInterface $usuario, array $parametros);

    /**
     * Atualiza parcialmente um usuario.
     *
     * @api
     *
     * @param UsuarioInterface  $usuario
     * @param array           $parametros
     *
     * @return UsuarioInterface
     */
    public function patch(UsuarioInterface $usuario, array $parametros);
}