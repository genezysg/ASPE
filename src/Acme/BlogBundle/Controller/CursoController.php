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
use Acme\BlogBundle\Form\CursoType;
use Acme\BlogBundle\Model\CursoInterface;

class CursoController extends FOSRestController {
	
	/**
	 * @Annotations\View(templateVar="curso")
	 * 
	 * @Annotations\QueryParam(name="posicao_inicio", requirements="\d+", nullable=true, description="Índice que indica o início da leitura.")
     * @Annotations\QueryParam(name="limite", requirements="\d+", default="50", description="Limite de dados exibidos.")	 
	 */
	public function getCursosAction(Request $request, ParamFetcherInterface $paramFetcher) {
		$posicao_inicio = $paramFetcher->get ( 'posicao_inicio' );
		$posicao_inicio = null == $posicao_inicio ? 0 : $posicao_inicio;
		$limite = $paramFetcher->get ( 'limite' );
		
		return $this->container->get ( 'acme_blog.curso.handler' )->all ( $limite, $posicao_inicio );
	}
	
	/**
	 * @Annotations\View(templateVar="curso")
	 */
	public function getCursoAction($codigo) {
		$curso = $this->getOr404 ( $codigo );
		
		return $curso;
	}
	
	/**	 
	 * @Annotations\View(templateVar = "form")
	 */
	public function newCursoAction() {
		return $this->createForm ( new CursoType () );
	}
	
	/**	
	 * @Annotations\View(
	 *  template = "AcmeBlogBundle:Curso:newCurso.html.twig",
	 *  statusCode = Codes::HTTP_BAD_REQUEST,
	 *  templateVar = "form"
	 * )	 
	 */
	public function postCursoAction(Request $request)
	{
		try {
			$newCurso = $this->container->get('acme_blog.curso.handler')->post(
					$request->request->all()
			);
	
			$routeOptions = array(
					'codigo' => $newCurso->getCodigo(),
					'_format' => $request->get('_format')
			);
	
			return $this->routeRedirectView('aspe_get_curso', $routeOptions, Codes::HTTP_CREATED);
	
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}

	/**	 
	 * @Annotations\View(templateVar="form")
	 * @Annotations\Get("/curso/{codigo}/delete")
	 *
	 */
	public function deleteCursoAction($codigo, Request $request, ParamFetcherInterface $paramFetcher)
	{
		try {
			if ($usuario = $this->container->get('acme_blog.curso.handler')->get($codigo)) {
				$statusCode = Codes::HTTP_CREATED;
				$this->container->get('acme_blog.curso.handler')->delete($usuario);
			} else
				$statusCode = Codes::HTTP_NO_CONTENT;
			$routeOptions = array(
					'_format' => $request->get('_format')
			);
			return $this->routeRedirectView('aspe_get_cursos', $routeOptions, $statusCode);
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}
	/**	 	 
	 * @Annotations\View(
	 *  template = "AcmeBlogBundle:Curso:editCurso.html.twig",
	 *  templateVar = "form"
	 * )	 
	 */
	public function putCursoAction(Request $request, $codigo)
	{
		try {
			if (!($curso = $this->container->get('acme_blog.curso.handler')->get($codigo))) {
				$statusCode = Codes::HTTP_CREATED;
				$curso = $this->container->get('acme_blog.curso.handler')->post(
						$request->request->all()
				);
			} else {
				$statusCode = Codes::HTTP_NO_CONTENT;
				$curso = $this->container->get('acme_blog.curso.handler')->put(
						$curso,
						$request->request->all()
				);
			}
	
			$routeOptions = array(
					'codigo' => $curso->getCodigo(),
					'_format' => $request->get('_format')
			);
	
			return $this->routeRedirectView('aspe_get_curso', $routeOptions, $statusCode);
	
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}
	
	/**	 
	 * @Annotations\View(
	 *  template = "AcmeBlogBundle:Curso:editCurso.html.twig",
	 *  templateVar = "form"
	 * )	 
	 */
	public function patchCursoAction(Request $request, $codigo)
	{
		try {
			$curso = $this->container->get('acme_blog.curso.handler')->patch(
					$this->getOr404($codigo),
					$request->request->all()
			);
	
			$routeOptions = array(
					'codigo' => $curso->getCodigo(),
					'_format' => $request->get('_format')
			);
	
			return $this->routeRedirectView('aspe_get_curso', $routeOptions, Codes::HTTP_NO_CONTENT);
	
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}	
	
	/**
	 * @Annotations\View(templateVar = "form")
	 */
	public function editCursoAction($codigo, Request $request){
		try{
			$curso = $this->container->get('acme_blog.curso.handler')->get($codigo);
			return $this->createForm(new CursoType(), $curso);
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}
	protected function getOr404($codigo) {
		if (! ($curso = $this->container->get ( 'acme_blog.curso.handler' )->get ( $codigo ))) {
			throw new NotFoundHttpException ( sprintf ( 'The resource \'%s\' was not found.', $codigo ) );
		}
		
		return $curso;
	}
}