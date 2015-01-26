<?php

namespace Acme\BlogBundle\Model;

Interface PerfilInterface
{
	public function getCodigo();
	
	public function setNome($nome);
	public function getNome();
}