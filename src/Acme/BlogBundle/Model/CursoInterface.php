<?php
namespace Acme\BlogBundle\Model;

Interface CursoInterface
{
	public function getCodigo();

	public function setNome($nome);
	public function getNome();
	
}