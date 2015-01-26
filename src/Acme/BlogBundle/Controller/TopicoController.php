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
use Acme\BlogBundle\Form\TopicoType;
use Acme\BlogBundle\Model\TopicoInterface;

class TopicoController extends FOSRestController {
	
	/**
	 * @Annotations\View(templateVar="topico")
	 * 
	 * @Annotations\QueryParam(name="posicao_inicio", requirements="\d+", nullable=true, description="Índice que indica o início da leitura.")
     * @Annotations\QueryParam(name="limite", requirements="\d+", default="50", description="Limite de dados exibidos.")	 
	 */
	public function getTopicosAction(Request $request, ParamFetcherInterface $paramFetcher) {
		$posicao_inicio = $paramFetcher->get ( 'posicao_inicio' );
		$posicao_inicio = null == $posicao_inicio ? 0 : $posicao_inicio;
		$limite = $paramFetcher->get ( 'limite' );
		
		return $this->container->get ( 'acme_blog.topico.handler' )->all ( $limite, $posicao_inicio );
	}
	
	/**
	 * @Annotations\View(templateVar="topico")
	 */
	public function getTopicoAction($codigo) {
		$topico = $this->getOr404 ( $codigo );
		
		return $topico;
	}
	
	/**	 
	 * @Annotations\View(templateVar = "form")
	 */
	public function newTopicoAction() {
		return $this->createForm ( new TopicoType () );
	}
	
	/**	
	 * @Annotations\View(
	 *  template = "AcmeBlogBundle:Topico:newTopico.html.twig",
	 *  statusCode = Codes::HTTP_BAD_REQUEST,
	 *  templateVar = "form"
	 * )	 
	 */
	public function postTopicoAction(Request $request)
	{
		try {
			$newTopico = $this->container->get('acme_blog.topico.handler')->post(
					$request->request->all()
			);
	
			$routeOptions = array(
					'codigo' => $newTopico->getCodigo(),
					'_format' => $request->get('_format')
			);
	
			return $this->routeRedirectView('aspe_get_topico', $routeOptions, Codes::HTTP_CREATED);
	
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}

	/**	 
	 * @Annotations\View(templateVar="form")
	 * @Annotations\Get("/topico/{codigo}/delete")
	 *
	 */
	public function deleteTopicoAction($codigo, Request $request, ParamFetcherInterface $paramFetcher)
	{
		try {
			if ($usuario = $this->container->get('acme_blog.topico.handler')->get($codigo)) {
				$statusCode = Codes::HTTP_CREATED;
				$this->container->get('acme_blog.topico.handler')->delete($usuario);
			} else
				$statusCode = Codes::HTTP_NO_CONTENT;
			$routeOptions = array(
					'_format' => $request->get('_format')
			);
			return $this->routeRedirectView('aspe_get_topicos', $routeOptions, $statusCode);
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}
	/**	 	 
	 * @Annotations\View(
	 *  template = "AcmeBlogBundle:Topico:editTopico.html.twig",
	 *  templateVar = "form"
	 * )	 
	 */
	public function putTopicoAction(Request $request, $codigo)
	{
		try {
			if (!($topico = $this->container->get('acme_blog.topico.handler')->get($codigo))) {
				$statusCode = Codes::HTTP_CREATED;
				$topico = $this->container->get('acme_blog.topico.handler')->post(
						$request->request->all()
				);
			} else {
				$statusCode = Codes::HTTP_NO_CONTENT;
				$topico = $this->container->get('acme_blog.topico.handler')->put(
						$topico,
						$request->request->all()
				);
			}
	
			$routeOptions = array(
					'codigo' => $topico->getCodigo(),
					'_format' => $request->get('_format')
			);
	
			return $this->routeRedirectView('aspe_get_topico', $routeOptions, $statusCode);
	
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}
	
	/**	 
	 * @Annotations\View(
	 *  template = "AcmeBlogBundle:Topico:editTopico.html.twig",
	 *  templateVar = "form"
	 * )	 
	 */
	public function patchTopicoAction(Request $request, $codigo)
	{
		try {
			$topico = $this->container->get('acme_blog.topico.handler')->patch(
					$this->getOr404($codigo),
					$request->request->all()
			);
	
			$routeOptions = array(
					'codigo' => $topico->getCodigo(),
					'_format' => $request->get('_format')
			);
	
			return $this->routeRedirectView('aspe_get_topico', $routeOptions, Codes::HTTP_NO_CONTENT);
	
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}	
	
	/**
	 * @Annotations\View(templateVar = "form")
	 */
	public function editTopicoAction($codigo, Request $request){
		try{
			$topico = $this->container->get('acme_blog.topico.handler')->get($codigo);
			return $this->createForm(new TopicoType(), $topico);
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}
	protected function getOr404($codigo) {
		if (! ($topico = $this->container->get ( 'acme_blog.topico.handler' )->get ( $codigo ))) {
			throw new NotFoundHttpException ( sprintf ( 'The resource \'%s\' was not found.', $codigo ) );
		}
		
		return $topico;
	}
}