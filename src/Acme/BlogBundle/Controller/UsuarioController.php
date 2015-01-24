<?php

namespace Acme\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Symfony\Component\Form\FormTypeInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Acme\BlogBundle\Exception\InvalidFormException;
use Acme\BlogBundle\Form\UsuarioType;
use Acme\BlogBundle\Model\UsuarioInterface;

class UsuarioController extends FOSRestController {
	/**	 
	 * @Annotations\View(templateVar="usuarios")
	 * 
	 * @Annotations\QueryParam(name="posicao_inicio", requirements="\d+", nullable=true, description="Índice que indica o início da leitura.")
     * @Annotations\QueryParam(name="limite", requirements="\d+", default="50", description="Limite de dados exibidos.")	 
	 */
	public function getUsuariosAction(Request $request, ParamFetcherInterface $paramFetcher) {
		$posicao_inicio = $paramFetcher->get ( 'posicao_inicio' );
		$posicao_inicio = null == $posicao_inicio ? 0 : $posicao_inicio;
		$limite = $paramFetcher->get ( 'limite' );
		
		return $this->container->get ( 'acme_blog.usuario.handler' )->all ( $limite, $posicao_inicio );
	}
	
	/**	 
	 * @Annotations\View(templateVar="usuario")	 	 
	 */
	public function getUsuarioAction($matricula) {
		$usuario = $this->getOr404 ( $matricula );
		
		return $usuario;
	}
	
	/**	 
	 * @Annotations\View(templateVar = "form")
	 */	
	public function newUsuarioAction() {
		return $this->createForm ( new UsuarioType () );
	}
	
	/**	 
	 * @Annotations\View(
	 *  template = "AcmeBlogBundle:Usuario:newUsuario.html.twig",
	 *  statusCode = Codes::HTTP_BAD_REQUEST,
	 *  templateVar = "form"
	 * )	 
	 */
	public function postUsuarioAction(Request $request)
	{
		try {
			$newUsuario = $this->container->get('acme_blog.usuario.handler')->post(
					$request->request->all()
			);
	
			$routeOptions = array(
					'matricula' => $newUsuario->getMatricula(),
					'_format' => $request->get('_format')
			);
	
			return $this->routeRedirectView('api_1_get_usuario', $routeOptions, Codes::HTTP_CREATED);
	
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}
	/**	 
	 * @Annotations\View(templateVar="form")
	 * @Annotations\Get("/usuarios/{matricula}/delete")
	 */
	public function deleteUsuarioAction($matricula, Request $request, ParamFetcherInterface $paramFetcher)
	{
		try {
			if ($usuario = $this->container->get('acme_blog.usuario.handler')->get($matricula)) {
				$statusCode = Codes::HTTP_CREATED;
				$this->container->get('acme_blog.usuario.handler')->delete($usuario);
			} else
				$statusCode = Codes::HTTP_NO_CONTENT;
			$routeOptions = array(
					'_format' => $request->get('_format')
			);
			return $this->routeRedirectView('api_1_get_usuarios', $routeOptions, $statusCode);
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}
	/**	 	 
	 * @Annotations\View(
	 *  template = "AcmeBlogBundle:Usuario:editUsuario.html.twig",
	 *  templateVar = "form"
	 * )	
	 */
	public function putUsuarioAction(Request $request, $matricula)
	{
		try {
			if (!($usuario = $this->container->get('acme_blog.usuario.handler')->get($matricula))) {
				$statusCode = Codes::HTTP_CREATED;
				$usuario = $this->container->get('acme_blog.usuario.handler')->post(
						$request->request->all()
				);
			} else {
				$statusCode = Codes::HTTP_NO_CONTENT;
				$usuario = $this->container->get('acme_blog.usuario.handler')->put(
						$usuario,
						$request->request->all()
				);
			}
	
			$routeOptions = array(
					'matricula' => $usuario->getMatricula(),
					'_format' => $request->get('_format')
			);
	
			return $this->routeRedirectView('api_1_get_usuario', $routeOptions, $statusCode);
	
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}
	
	/**	 
	 * @Annotations\View(
	 *  template = "AcmeBlogBundle:Usuario:editUsuario.html.twig",
	 *  templateVar = "form"
	 * )	 
	 */
	public function patchUsuarioAction(Request $request, $matricula)
	{
		try {
			$usuario = $this->container->get('acme_blog.usuario.handler')->patch(
					$this->getOr404($matricula),
					$request->request->all()
			);
	
			$routeOptions = array(
					'matricula' => $usuario->getMatricula(),
					'_format' => $request->get('_format')
			);
	
			return $this->routeRedirectView('api_1_get_usuario', $routeOptions, Codes::HTTP_NO_CONTENT);
	
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}
	/**
	 * @Annotations\View(templateVar = "form")
	 */
	public function editUsuarioAction($matricula, Request $request){
		try{
			$usuario = $this->container->get('acme_blog.usuario.handler')->get($matricula);
			return $this->createForm(new UsuarioType(), $usuario);
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}
	
	protected function getOr404($matricula) {
		if (! ($usuario = $this->container->get ( 'acme_blog.usuario.handler' )->get ( $matricula ))) {
			throw new NotFoundHttpException ( sprintf ( 'The resource \'%s\' was not found.', $matricula ) );
		}
		
		return $usuario;
	}
}