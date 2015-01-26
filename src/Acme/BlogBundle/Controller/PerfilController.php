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
use Acme\BlogBundle\Form\PerfilType;
use Acme\BlogBundle\Model\PerfilInterface;

class PerfilController extends FOSRestController {
	
	/**
	 * @Annotations\View(templateVar="perfil")
	 * 
	 * @Annotations\QueryParam(name="posicao_inicio", requirements="\d+", nullable=true, description="Índice que indica o início da leitura.")
     * @Annotations\QueryParam(name="limite", requirements="\d+", default="50", description="Limite de dados exibidos.")	 
	 */
	public function getPerfilsAction(Request $request, ParamFetcherInterface $paramFetcher) {
		$posicao_inicio = $paramFetcher->get ( 'posicao_inicio' );
		$posicao_inicio = null == $posicao_inicio ? 0 : $posicao_inicio;
		$limite = $paramFetcher->get ( 'limite' );
		
		return $this->container->get ( 'acme_blog.perfil.handler' )->all ( $limite, $posicao_inicio );
	}
	
	/**
	 * @Annotations\View(templateVar="perfil")
	 */
	public function getPerfilAction($codigo) {
		$perfil = $this->getOr404 ( $codigo );
		
		return $perfil;
	}
	
	/**	 
	 * @Annotations\View(templateVar = "form")
	 */
	public function newPerfilAction() {
		return $this->createForm ( new PerfilType () );
	}
	
	/**	
	 * @Annotations\View(
	 *  template = "AcmeBlogBundle:Perfil:newPerfil.html.twig",
	 *  statusCode = Codes::HTTP_BAD_REQUEST,
	 *  templateVar = "form"
	 * )	 
	 */
	public function postPerfilAction(Request $request)
	{
		try {
			$newPerfil = $this->container->get('acme_blog.perfil.handler')->post(
					$request->request->all()
			);
	
			$routeOptions = array(
					'codigo' => $newPerfil->getCodigo(),
					'_format' => $request->get('_format')
			);
	
			return $this->routeRedirectView('aspe_get_perfil', $routeOptions, Codes::HTTP_CREATED);
	
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}

	/**	 
	 * @Annotations\View(templateVar="form")
	 * @Annotations\Get("/perfil/{codigo}/delete")
	 *
	 */
	public function deletePerfilAction($codigo, Request $request, ParamFetcherInterface $paramFetcher)
	{
		try {
			if ($usuario = $this->container->get('acme_blog.perfil.handler')->get($codigo)) {
				$statusCode = Codes::HTTP_CREATED;
				$this->container->get('acme_blog.perfil.handler')->delete($usuario);
			} else
				$statusCode = Codes::HTTP_NO_CONTENT;
			$routeOptions = array(
					'_format' => $request->get('_format')
			);
			return $this->routeRedirectView('aspe_get_perfils', $routeOptions, $statusCode);
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}
	/**	 	 
	 * @Annotations\View(
	 *  template = "AcmeBlogBundle:Perfil:editPerfil.html.twig",
	 *  templateVar = "form"
	 * )	 
	 */
	public function putPerfilAction(Request $request, $codigo)
	{
		try {
			if (!($perfil = $this->container->get('acme_blog.perfil.handler')->get($codigo))) {
				$statusCode = Codes::HTTP_CREATED;
				$perfil = $this->container->get('acme_blog.perfil.handler')->post(
						$request->request->all()
				);
			} else {
				$statusCode = Codes::HTTP_NO_CONTENT;
				$perfil = $this->container->get('acme_blog.perfil.handler')->put(
						$perfil,
						$request->request->all()
				);
			}
	
			$routeOptions = array(
					'codigo' => $perfil->getCodigo(),
					'_format' => $request->get('_format')
			);
	
			return $this->routeRedirectView('aspe_get_perfil', $routeOptions, $statusCode);
	
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}
	
	/**	 
	 * @Annotations\View(
	 *  template = "AcmeBlogBundle:Perfil:editPerfil.html.twig",
	 *  templateVar = "form"
	 * )	 
	 */
	public function patchPerfilAction(Request $request, $codigo)
	{
		try {
			$perfil = $this->container->get('acme_blog.perfil.handler')->patch(
					$this->getOr404($codigo),
					$request->request->all()
			);
	
			$routeOptions = array(
					'codigo' => $perfil->getCodigo(),
					'_format' => $request->get('_format')
			);
	
			return $this->routeRedirectView('aspe_get_perfil', $routeOptions, Codes::HTTP_NO_CONTENT);
	
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}	
	/**
	 * @Annotations\View(templateVar = "form")
	 */
	public function editPerfilAction($codigo, Request $request){
		try{
			$perfil = $this->container->get('acme_blog.perfil.handler')->get($codigo);
			return $this->createForm(new PerfilType(), $perfil);
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}
	protected function getOr404($codigo) {
		if (! ($perfil = $this->container->get ( 'acme_blog.perfil.handler' )->get ( $codigo ))) {
			throw new NotFoundHttpException ( sprintf ( 'The resource \'%s\' was not found.', $codigo ) );
		}
		
		return $perfil;
	}
}