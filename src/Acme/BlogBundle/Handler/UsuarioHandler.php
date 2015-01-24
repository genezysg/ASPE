<?php

namespace Acme\BlogBundle\Handler;

use Acme\BlogBundle\Model\UsuarioInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\BlogBundle\Exception\InvalidFormException;
use Symfony\Component\Form\FormFactoryInterface;
use Acme\BlogBundle\Form\UsuarioType;
use Acme\BlogBundle\Entity\Usuario;

class UsuarioHandler implements UsuarioHandlerInterface {
	private $om;
	private $entityClass;
	private $repository;
	private $formFactory;
	
	public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory) {
		$this->om = $om;
		$this->entityClass = $entityClass;
		$this->repository = $this->om->getRepository ( $this->entityClass );
		$this->formFactory = $formFactory;
	}
	
	public function get($matricula) {
		return $this->repository->find ( $matricula );
	}
	
	public function all($limite = 5, $posicao_inicio = 0) {
		return $this->repository->findBy ( array (), null, $limite, $posicao_inicio );
	}
	
	public function post(array $parametros) {
		$usuario = $this->createUsuario ();
		return $this->processForm ( $usuario, $parametros, 'POST' );
	}
	
	public function put(UsuarioInterface $usuario, array $parametros) {
		return $this->processForm ( $usuario, $parametros, 'PUT' );
	}
	
	public function patch(UsuarioInterface $usuario, array $parametros) {
		return $this->processForm ( $usuario, $parametros, 'PATCH' );
	}
	
	public function delete(Usuario $usuario) {
		return $this->processDelete ( $usuario );
	}
	
	private function processDelete(Usuario $usuario) {
		$this->om->remove ( $usuario );
		$this->om->flush ( $usuario );
		
		return $usuario;
	}
	
	private function processForm(UsuarioInterface $usuario, array $parametros, $metodo = "PUT") {
		$form = $this->formFactory->create ( new UsuarioType (), $usuario, array (
				'method' => $metodo 
		) );
		$form->submit ( $parametros, 'PATCH' !== $metodo );
		if ($form->isValid ()) {
			
			$usuario = $form->getData ();
			$this->om->persist ( $usuario );
			$this->om->flush ( $usuario );
			
			return $usuario;
		}
		
		throw new InvalidFormException ( 'Invalid submitted data', $form );
	}
	
	private function createUsuario() {
		return new $this->entityClass ();
	}
}