<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Estoque\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Estoque\Entity\Produto;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
    	$entityManager = $this->getServiceLocator()->get('Doctrine\ORM\entityManager');
    	$repositorio = $entityManager->getRepository('Estoque\Entity\Produto');

    	$produtos = $repositorio->findAll();

		$view_params = array(
			'produtos' => $produtos
		);
		return new ViewModel($view_params);
    }

	public function cadastrarAction() {

		if($this->request->isPost()){
			$nome = $this->request->getPost('nome');
			$preco = $this->request->getPost('preco');
			$descricao = $this->request->getPost('descricao');

			$produto = new Produto($nome, $preco, $descricao);

			$entityManager = $this->getServiceLocator()->get('Doctrine\ORM\entityManager');

			$entityManager->persist($produto);
			$entityManager->flush();

			return $this->redirect()->toUrl('Index/Index');

		}
   		return new ViewModel();
   	}
}
