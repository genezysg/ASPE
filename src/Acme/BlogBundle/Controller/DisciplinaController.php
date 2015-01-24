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
use Acme\BlogBundle\Form\DisciplinaType;
use Acme\BlogBundle\Model\DisciplinaInterface;

class DisciplinaController extends FOSRestController {
	
	/**
	 * @Annotations\View(templateVar="disciplina")
	 * 
	 * @Annotations\QueryParam(name="posicao_inicio", requirements="\d+", nullable=true, description="Índice que indica o início da leitura.")
     * @Annotations\QueryParam(name="limite", requirements="\d+", default="50", description="Limite de dados exibidos.")	 
	 */
	public function getDisciplinasAction(Request $request, ParamFetcherInterface $paramFetcher) {
		$posicao_inicio = $paramFetcher->get ( 'posicao_inicio' );
		$posicao_inicio = null == $posicao_inicio ? 0 : $posicao_inicio;
		$limite = $paramFetcher->get ( 'limite' );
		
		return $this->container->get ( 'acme_blog.disciplina.handler' )->all ( $limite, $posicao_inicio );
	}
	
	/**
	 * @Annotations\View(templateVar="disciplina")
	 */
	public function getDisciplinaAction($codigo) {
		$disciplina = $this->getOr404 ( $codigo );
		
		return $disciplina;
	}
	
	/**	 
	 * @Annotations\View(templateVar = "form")
	 */
	public function newDisciplinaAction() {
		return $this->createForm ( new DisciplinaType () );
	}
	
	/**	
	 * @Annotations\View(
	 *  template = "AcmeBlogBundle:Disciplina:newDisciplina.html.twig",
	 *  statusCode = Codes::HTTP_BAD_REQUEST,
	 *  templateVar = "form"
	 * )	 
	 */
	public function postDisciplinaAction(Request $request)
	{
		try {
			$newDisciplina = $this->container->get('acme_blog.disciplina.handler')->post(
					$request->request->all()
			);
	
			$routeOptions = array(
					'codigo' => $newDisciplina->getCodigo(),
					'_format' => $request->get('_format')
			);
	
			return $this->routeRedirectView('api_1_get_disciplina', $routeOptions, Codes::HTTP_CREATED);
	
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}

	/**	 
	 * @Annotations\View(templateVar="form")
	 * @Annotations\Get("/disciplina/{codigo}/delete")
	 *
	 */
	public function deleteDisciplinaAction($codigo, Request $request, ParamFetcherInterface $paramFetcher)
	{
		try {
			if ($usuario = $this->container->get('acme_blog.disciplina.handler')->get($codigo)) {
				$statusCode = Codes::HTTP_CREATED;
				$this->container->get('acme_blog.disciplina.handler')->delete($usuario);
			} else
				$statusCode = Codes::HTTP_NO_CONTENT;
			$routeOptions = array(
					'_format' => $request->get('_format')
			);
			return $this->routeRedirectView('api_1_get_disciplinas', $routeOptions, $statusCode);
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}
	/**	 	 
	 * @Annotations\View(
	 *  template = "AcmeBlogBundle:Disciplina:editDisciplina.html.twig",
	 *  templateVar = "form"
	 * )	 
	 */
	public function putDisciplinaAction(Request $request, $codigo)
	{
		try {
			if (!($disciplina = $this->container->get('acme_blog.disciplina.handler')->get($codigo))) {
				$statusCode = Codes::HTTP_CREATED;
				$disciplina = $this->container->get('acme_blog.disciplina.handler')->post(
						$request->request->all()
				);
			} else {
				$statusCode = Codes::HTTP_NO_CONTENT;
				$disciplina = $this->container->get('acme_blog.disciplina.handler')->put(
						$disciplina,
						$request->request->all()
				);
			}
	
			$routeOptions = array(
					'codigo' => $disciplina->getCodigo(),
					'_format' => $request->get('_format')
			);
	
			return $this->routeRedirectView('api_1_get_disciplina', $routeOptions, $statusCode);
	
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}
	
	/**	 
	 * @Annotations\View(
	 *  template = "AcmeBlogBundle:Disciplina:editDisciplina.html.twig",
	 *  templateVar = "form"
	 * )	 
	 */
	public function patchDisciplinaAction(Request $request, $codigo)
	{
		try {
			$disciplina = $this->container->get('acme_blog.disciplina.handler')->patch(
					$this->getOr404($codigo),
					$request->request->all()
			);
	
			$routeOptions = array(
					'codigo' => $disciplina->getCodigo(),
					'_format' => $request->get('_format')
			);
	
			return $this->routeRedirectView('api_1_get_disciplina', $routeOptions, Codes::HTTP_NO_CONTENT);
	
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}	
	/**
	 * @Annotations\View(templateVar = "form")
	 */
	public function editDisciplinaAction($codigo, Request $request){
		try{
			$disciplina = $this->container->get('acme_blog.disciplina.handler')->get($codigo);
			return $this->createForm(new DisciplinaType(), $disciplina);
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}
	protected function getOr404($codigo) {
		if (! ($disciplina = $this->container->get ( 'acme_blog.disciplina.handler' )->get ( $codigo ))) {
			throw new NotFoundHttpException ( sprintf ( 'The resource \'%s\' was not found.', $codigo ) );
		}
		
		return $disciplina;
	}
}