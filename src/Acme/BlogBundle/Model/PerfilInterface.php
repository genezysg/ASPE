<?php

namespace Acme\BlogBundle\Model;

Interface PerfilInterface
{
	/**
	 * Set Nome
	 * @param string $nome
	 * @return PerfilInterface
	 */
	public function setNome($nome);
	
	/**
	 * get Nome
	 * 
	 * @return PerfilInterface 
	 */
	public function getNome();
}