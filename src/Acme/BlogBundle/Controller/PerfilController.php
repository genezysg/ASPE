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
	// public function getPerfilAction($codigo){
	// return $this->container->get('doctrine.entity_manager')->getRepository('perfil')->find($codigo);
	// }
	/**
	 * Lista 5 perfis.
	 *
	 * @ApiDoc(
	 * resource = true,
	 * statusCodes = {
	 * 200 = "Returned when successful"
	 * }
	 * )
	 *
	 * @Annotations\QueryParam(name="posicao_inicio", requirements="\d+", nullable=true, description="Número que indica o início da leitura dos dados.")
	 * @Annotations\QueryParam(name="limite", requirements="\d+", default="5", description="Quantas páginas serão retornadas.")
	 *
	 * @Annotations\View(
	 * templateVar="perfis"
	 * )
	 *
	 * @param Request $request
	 *        	objeto requisitado
	 * @param ParamFetcherInterface $paramFetcher
	 *        	serviço de busca de parâmetro
	 *        	
	 * @return array
	 */
	public function getPerfisAction(Request $request, ParamFetcherInterface $paramFetcher) {
		$posicao_inicio = $paramFetcher->get ( 'posicao_inicio' );
		$posicao_inicio = null == $posicao_inicio ? 0 : $posicao_inicio;
		$limite = $paramFetcher->get ( 'limite' );
		
		return $this->container->get ( 'acme_blog.perfil.handler' )->all ( $limite, $posicao_inicio );
	}
	
	/**
	 * Retorna um perfil pelo codigo passado.
	 *
	 * @ApiDoc(
	 * resource = true,
	 * description = "Retorna um perfil pelo codigo",
	 * output = "Acme\BlogBundle\Entity\Perfil",
	 * statusCodes = {
	 * 200 = "Retornado quando bem-sucedido",
	 * 404 = "Retornado quando a página não foi encontrada"
	 * }
	 * )
	 *
	 * @Annotations\View(templateVar="perfil")
	 *
	 * @param int $codigo
	 *        	codigo do perfil
	 *        	
	 * @return array
	 *
	 * @throws NotFoundHttpException quando o perfil não foi encontrado
	 */
	public function getPerfilAction($codigo) {
		$perfil = $this->getOr404 ( $codigo );
		
		return $perfil;
	}
	
	/**
	 * Apresenta o formulário para criar um novo perfil.
	 * 
	 * @ApiDoc(
	 * resource = true,
	 * statusCodes = {
	 * 200 = "Retornado quando bem-sucedido"
	 * }
	 * )
	 *
	 * @Annotations\View(
	 * templateVar = "form"
	 * )
	 *
	 * @return FormPerfilInterface
	 */
	public function newPerfilAction() {
		return $this->createForm ( new PerfilType () );
	}
	
	/**
	 * Cria um perfil a partir dos dados enviados
	 * 
	 * @ApiDoc(
	 *   resource = true,
	 *   description = "Cria um perfil a partir dos dados enviados.",
	 *   input = "Acme\BlogBundle\Form\PerfilType",
	 *   statusCodes = {
	 *   	200 = "Retornado quando bem-sucedido",
	 *   	404 = "Retornado quando a página não foi encontrada"
	 *   }
	 * )
	 *
	 * @Annotations\View(
	 *  template = "AcmeBlogBundle:Perfil:newPerfil.html.twig",
	 *  statusCode = Codes::HTTP_BAD_REQUEST,
	 *  templateVar = "form"
	 * )
	 *
	 * @param Request $request Objeto Request
	 *
	 * @return FormTypeInterface|View
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
	
			return $this->routeRedirectView('api_1_get_perfil', $routeOptions, Codes::HTTP_CREATED);
	
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}

	/**	 
	 * Atualiza os dados existentes a partir dos dados enviados ou cria um novo perfil numa localização específica.
	 * 
	 * @ApiDoc(
	 *   resource = true,
	 *   input = "Acme\DemoBundle\Form\PerfilType",
	 *   statusCodes = {
	 *     201 = "Retornado quando o perfil é criado",
	 *     204 = "Retornado quando bem-sucedido",
	 *     400 = "Retornado quando o formulário possui erros"
	 *   }
	 * )
	 *
	 * @Annotations\View(
	 *  template = "AcmeBlogBundle:Perfil:editPerfil.html.twig",
	 *  templateVar = "form"
	 * )
	 *
	 * @param Request $request 		  O objeto request
	 * @param int     $codigo      Código do perfil
	 *
	 * @return FormTypeInterface|View
	 *
	 * @throws NotFoundHttpException quando o perfil não existe
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
	
			return $this->routeRedirectView('api_1_get_perfil', $routeOptions, $statusCode);
	
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}
	
	/**
	 * Atualiza os dados existentes a partir dos dados enviados ou cria um novo perfil numa localização específica.
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   input = "Acme\DemoBundle\Form\PerfilType",
	 *   statusCodes = {
	 *     204 = "Retornado quando bem-sucedido",
	 *     400 = "Retornado quando o formulário possui erros"
	 *   }
	 * )
	 *
	 * @Annotations\View(
	 *  template = "AcmeBlogBundle:Perfil:editPerfil.html.twig",
	 *  templateVar = "form"
	 * )
	 *
	 * @param Request $request 		  O objeto request
	 * @param int     $codigo      Código do perfil
	 *
	 * @return FormTypeInterface|View
	 *
	 * @throws NotFoundHttpException quando o perfil não existe
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
	
			return $this->routeRedirectView('api_1_get_perfil', $routeOptions, Codes::HTTP_NO_CONTENT);
	
		} catch (InvalidFormException $exception) {
	
			return $exception->getForm();
		}
	}
	/**
	 * Busca uma página ou dispara uma exceção 404.
	 *
	 * @param mixed $codigo        	
	 *
	 * @return PerfilInterface
	 *
	 * @throws NotFoundHttpException
	 */
	protected function getOr404($codigo) {
		if (! ($perfil = $this->container->get ( 'acme_blog.perfil.handler' )->get ( $codigo ))) {
			throw new NotFoundHttpException ( sprintf ( 'The resource \'%s\' was not found.', $codigo ) );
		}
		
		return $perfil;
	}
}