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
	// public function getUsuarioAction($matricula){
	// return $this->container->get('doctrine.entity_manager')->getRepository('usuario')->find($matricula);
	// }
	/**
	 * Lista 5 usuarios.
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
	 * templateVar="usuarios"
	 * )
	 *
	 * @param Request $request
	 *        	objeto requisitado
	 * @param ParamFetcherInterface $paramFetcher
	 *        	serviço de busca de parâmetro
	 *        	
	 * @return array
	 */
	public function getUsuariosAction(Request $request, ParamFetcherInterface $paramFetcher) {
		$posicao_inicio = $paramFetcher->get ( 'posicao_inicio' );
		$posicao_inicio = null == $posicao_inicio ? 0 : $posicao_inicio;
		$limite = $paramFetcher->get ( 'limite' );
		
		return $this->container->get ( 'acme_blog.usuario.handler' )->all ( $limite, $posicao_inicio );
	}
	
	/**
	 * Retorna um usuario pela matricula passada.
	 *
	 * @ApiDoc(
	 * resource = true,
	 * description = "Retorna um usuario pelo matricula",
	 * output = "Acme\BlogBundle\Entity\Usuario",
	 * statusCodes = {
	 * 200 = "Retornado quando bem-sucedido",
	 * 404 = "Retornado quando a página não foi encontrada"
	 * }
	 * )
	 *
	 * @Annotations\View(templateVar="usuario")
	 *
	 * @param int $matricula
	 *        	matricula do usuario
	 *        	
	 * @return array
	 *
	 * @throws NotFoundHttpException quando o usuario não foi encontrado
	 */
	public function getUsuarioAction($matricula) {
		$usuario = $this->getOr404 ( $matricula );
		
		return $usuario;
	}
	
	/**
	 * Apresenta o formulário para criar um novo usuario.
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
	 * @return FormUsuarioInterface
	 */
	public function newUsuarioAction() {
		return $this->createForm ( new UsuarioType () );
	}
	
	/**
	 * Cria um usuario a partir dos dados enviados
	 * 
	 * @ApiDoc(
	 *   resource = true,
	 *   description = "Cria um usuario a partir dos dados enviados.",
	 *   input = "Acme\BlogBundle\Form\UsuarioType",
	 *   statusCodes = {
	 *   	200 = "Retornado quando bem-sucedido",
	 *   	404 = "Retornado quando a página não foi encontrada"
	 *   }
	 * )
	 *
	 * @Annotations\View(
	 *  template = "AcmeBlogBundle:Usuario:newUsuario.html.twig",
	 *  statusCode = Codes::HTTP_BAD_REQUEST,
	 *  templateVar = "form"
	 * )
	 *
	 * @param Request $request Objeto Request
	 *
	 * @return FormTypeInterface|View
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
	 * Atualiza os dados existentes a partir dos dados enviados ou cria um novo usuario numa localização específica.
	 * 
	 * @ApiDoc(
	 *   resource = true,
	 *   input = "Acme\DemoBundle\Form\UsuarioType",
	 *   statusCodes = {
	 *     201 = "Retornado quando o usuário é criado",
	 *     204 = "Retornado quando bem-sucedido",
	 *     400 = "Retornado quando o formulário possui erros"
	 *   }
	 * )
	 *
	 * @Annotations\View(
	 *  template = "AcmeBlogBundle:Usuario:editUsuario.html.twig",
	 *  templateVar = "form"
	 * )
	 *
	 * @param Request $request 		  O objeto request
	 * @param int     $matricula      Matrícula do usuario
	 *
	 * @return FormTypeInterface|View
	 *
	 * @throws NotFoundHttpException quando o usuario não existe
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
	 * Atualiza os dados existentes a partir dos dados enviados ou cria um novo usuario numa localização específica.
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   input = "Acme\DemoBundle\Form\UsuarioType",
	 *   statusCodes = {
	 *     204 = "Retornado quando bem-sucedido",
	 *     400 = "Retornado quando o formulário possui erros"
	 *   }
	 * )
	 *
	 * @Annotations\View(
	 *  template = "AcmeBlogBundle:Usuario:editUsuario.html.twig",
	 *  templateVar = "form"
	 * )
	 *
	 * @param Request $request 		  O objeto request
	 * @param int     $matricula      Matrícula do usuario
	 *
	 * @return FormTypeInterface|View
	 *
	 * @throws NotFoundHttpException quando o usuario não existe
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
	 * Busca uma página ou dispara uma exceção 404.
	 *
	 * @param mixed $matricula        	
	 *
	 * @return UsuarioInterface
	 *
	 * @throws NotFoundHttpException
	 */
	protected function getOr404($matricula) {
		if (! ($usuario = $this->container->get ( 'acme_blog.usuario.handler' )->get ( $matricula ))) {
			throw new NotFoundHttpException ( sprintf ( 'The resource \'%s\' was not found.', $matricula ) );
		}
		
		return $usuario;
	}
}