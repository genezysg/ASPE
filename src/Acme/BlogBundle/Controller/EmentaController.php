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
use Acme\BlogBundle\Form\EmentaType;
use Acme\BlogBundle\Model\EmentaInterface;

class EmentaController extends FOSRestController {
	
	/**
	 * @Annotations\View(templateVar="ementa")
	 * 
	 * @Annotations\QueryParam(name="posicao_inicio", requirements="\d+", nullable=true, description="Índice que indica o início da leitura.")
     * @Annotations\QueryParam(name="limite", requirements="\d+", default="50", description="Limite de dados exibidos.")	 
	 */
	public function getEmentasAction(Request $request, ParamFetcherInterface $paramFetcher) {
		$posicao_inicio = $paramFetcher->get ( 'posicao_inicio' );
		$posicao_inicio = null == $posicao_inicio ? 0 : $posicao_inicio;
		$limite = $paramFetcher->get ( 'limite' );
		
		return $this->container->get ( 'acme_blog.ementa.handler' )->all ( $limite, $posicao_inicio );
	}
	
	/**
	 * @Annotations\View(templateVar="ementa")
	 */
	public function getEmentaAction($codigo) {
		$ementa = $this->getOr404 ( $codigo );
		
		return $ementa;
	}
	
	/**	 
	 * @Annotations\View(templateVar = "form")
	 */
	public function newEmentaAction() {
		return $this->createForm ( new EmentaType () );
	}
	
	/**	
	 * @Annotations\View(
	 *  template = "AcmeBlogBundle:Ementa:newEmenta.html.twig",
	 *  statusCode = Codes::HTTP_BAD_REQUEST,
	 *  templateVar = "form"
	 * )	 
	 */
	public function postEmentaAction(Request $request)
	{
		try {
			$newEmenta = $this->container->get('acme_blog.ementa.handler')->post(
					$request->request->all()
			);
	
			$routeOptions = array(
					'codigo' => $newEmenta->getCodigo(),
					'_format' => $request->get('_format')
			);
	
			return $this->routeRedirectView('api_1_get_ementa', $routeOptions, Codes::HTTP_CREATED);
	
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}

	/**	 
	 * @Annotations\View(templateVar="form")
	 * @Annotations\Get("/ementa/{codigo}/delete")
	 *
	 */
	public function deleteEmentaAction($codigo, Request $request, ParamFetcherInterface $paramFetcher)
	{
		try {
			if ($usuario = $this->container->get('acme_blog.ementa.handler')->get($codigo)) {
				$statusCode = Codes::HTTP_CREATED;
				$this->container->get('acme_blog.ementa.handler')->delete($usuario);
			} else
				$statusCode = Codes::HTTP_NO_CONTENT;
			$routeOptions = array(
					'_format' => $request->get('_format')
			);
			return $this->routeRedirectView('api_1_get_ementas', $routeOptions, $statusCode);
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}
	/**	 	 
	 * @Annotations\View(
	 *  template = "AcmeBlogBundle:Ementa:editEmenta.html.twig",
	 *  templateVar = "form"
	 * )	 
	 */
	public function putEmentaAction(Request $request, $codigo)
	{
		try {
			if (!($ementa = $this->container->get('acme_blog.ementa.handler')->get($codigo))) {
				$statusCode = Codes::HTTP_CREATED;
				$ementa = $this->container->get('acme_blog.ementa.handler')->post(
						$request->request->all()
				);
			} else {
				$statusCode = Codes::HTTP_NO_CONTENT;
				$ementa = $this->container->get('acme_blog.ementa.handler')->put(
						$ementa,
						$request->request->all()
				);
			}
	
			$routeOptions = array(
					'codigo' => $ementa->getCodigo(),
					'_format' => $request->get('_format')
			);
	
			return $this->routeRedirectView('api_1_get_ementa', $routeOptions, $statusCode);
	
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}
	
	/**	 
	 * @Annotations\View(
	 *  template = "AcmeBlogBundle:Ementa:editEmenta.html.twig",
	 *  templateVar = "form"
	 * )	 
	 */
	public function patchEmentaAction(Request $request, $codigo)
	{
		try {
			$ementa = $this->container->get('acme_blog.ementa.handler')->patch(
					$this->getOr404($codigo),
					$request->request->all()
			);
	
			$routeOptions = array(
					'codigo' => $ementa->getCodigo(),
					'_format' => $request->get('_format')
			);
	
			return $this->routeRedirectView('api_1_get_ementa', $routeOptions, Codes::HTTP_NO_CONTENT);
	
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}	
	
	/**
	 * @Annotations\View(templateVar = "form")
	 */
	public function editEmentaAction($codigo, Request $request){
		try{
			$ementa = $this->container->get('acme_blog.ementa.handler')->get($codigo);
			return $this->createForm(new EmentaType(), $ementa);
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}
	protected function getOr404($codigo) {
		if (! ($ementa = $this->container->get ( 'acme_blog.ementa.handler' )->get ( $codigo ))) {
			throw new NotFoundHttpException ( sprintf ( 'The resource \'%s\' was not found.', $codigo ) );
		}
		
		return $ementa;
	}
}