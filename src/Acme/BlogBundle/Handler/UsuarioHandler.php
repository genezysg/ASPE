<?php
namespace Acme\BlogBundle\Handler;

use Acme\BlogBundle\Model\UsuarioInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\BlogBundle\Exception\InvalidFormException;
use Symfony\Component\Form\FormFactoryInterface;
use Acme\BlogBundle\Form\UsuarioType;

class UsuarioHandler implements UsuarioHandlerInterface{
	
	private $om;
	private $entityClass;
	private $repository;
	private $formFactory;
	
	public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory)
	{
		$this->om = $om;
		$this->entityClass = $entityClass;
		$this->repository = $this->om->getRepository($this->entityClass);
		$this->formFactory = $formFactory;
	}
	
	/* (non-PHPdoc)
	 * @see \Acme\BlogBundle\Handler\UsuarioHandlerInterface::get()
	 */
	public function get($matricula) 
	{
		return $this->repository->find($matricula);
	}

	/* (non-PHPdoc)
	 * @see \Acme\BlogBundle\Handler\UsuarioHandlerInterface::all()
	 */
	public function all($limite = 5, $posicao_inicio = 0) 
	{
		return $this->repository->findBy(array(), null, $limite, $posicao_inicio);
	}

	/* (non-PHPdoc)
	 * @see \Acme\BlogBundle\Handler\UsuarioHandlerInterface::post()
	 */
	public function post(array $parametros) 
	{
		$usuario = $this->createUsuario();
		return $this->processForm($usuario, $parametros, 'POST');
	}

	/* (non-PHPdoc)
	 * @see \Acme\BlogBundle\Handler\UsuarioHandlerInterface::put()
	 */
	public function put(UsuarioInterface $usuario, array $parametros)
	{		
		return $this->processForm($usuario, $parametros, 'PUT');
	}

	/* (non-PHPdoc)
	 * @see \Acme\BlogBundle\Handler\UsuarioHandlerInterface::patch()
	 */
	public function patch(UsuarioInterface $usuario, array $parametros)
	{
		return $this->processForm($usuario, $parametros, 'PATCH');
	}

	private function processForm(UsuarioInterface $usuario, array $parametros, $metodo = "PUT")
	{
		$form = $this->formFactory->create(new UsuarioType(), $usuario, array('method' => $metodo));
		$form->submit($parametros, 'PATCH' !== $metodo);
		if ($form->isValid()) {
	
			$usuario = $form->getData();
			$this->om->persist($usuario);
			$this->om->flush($usuario);
	
			return $usuario;
		}
	
		throw new InvalidFormException('Invalid submitted data', $form);
	}
	
	private function createUsuario()
	{
		return new $this->entityClass();
	}
}