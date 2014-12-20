<?php
namespace Acme\DemoBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class RandomController
{
	public function indexAction($limit)
	{
		return new Response('<html><body>Number: '.rand(1, $limit).'</body></html>');
	}
}
