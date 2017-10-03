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
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport ;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
    	$pagina = $this->params()->fromRoute('page', 1);
    	$qtdPorPagina = 1;
    	$offset = ($pagina - 1) * $qtdPorPagina;


    	$entityManager = $this->getServiceLocator()->get('Doctrine\ORM\entityManager');
    	$repositorio = $entityManager->getRepository('Estoque\Entity\Produto');

    	$produtos = $repositorio->getProdutosPaginados($qtdPorPagina, $offset);

		$view_params = array(
			'produtos' => $produtos,
      'qtdPorPagina' => $qtdPorPagina
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

   	public function removerAction() {
   		$id = $this->params()->fromRoute('id');

   		if($this->request->isPost()) {
   			$id = $this->request->getPost('id');

   			$entityManager = $this->getServiceLocator()->get('Doctrine\ORM\entityManager');
   			$repositorio = $entityManager->getRepository('Estoque\Entity\Produto');

   			$produto = $repositorio->find($id);

   			$entityManager->remove($produto);
   			$entityManager->flush();

   			$this->flashMessenger()->addMessage('Produto Removido Com Sucesso!');

   			return $this->redirect()->toUrl('/Index');
   		}

   		return new ViewModel(['id'=>$id]);
   	}

   	public function editarAction() {
   		$id = $this->params()->fromRoute('id');
        if(is_null($id)) {
            $id = $this->params()->fromPost('id');
        }

   		$entityManager = $this->getServiceLocator()->get('Doctrine\ORM\entityManager');
		$repositorio = $entityManager->getRepository('Estoque\Entity\Produto');
   		
   		$produto = $repositorio->find($id);
   		
   		if($this->request->isPost()) {
   			$produto->setNome($this->request->getPost('nome'));
            $produto->setPreco($this->request->getPost('preco'));
            $produto->setDescricao($this->request->getPost('descricao'));

   			$entityManager->persist($produto);
   			$entityManager->flush();

   			$this->flashMessenger()->addSuccessMessage('Produto Alterado Com Sucesso!');

   			return $this->redirect()->toUrl('/Index');
   		}

   		$view_params = ['produto' => $produto];
        return new ViewModel($view_params);
   	}

   	public function contatoAction() {

   		if($this->request->isPost()) {
   			$nome = $this->request->getPost('nome');
   			$email = $this->request->getPost('email');
   			$msg = $this->request->getPost('msg');

   			$msgHtml = "
   				<b>Nome:</b> {$nome}, <br>
   				<b>Email:</b> {$email}, <br>
   				<b>Mensagem:</b> {$msg}, <br>
   			";

   			$htmlPart = new MimePart($msgHtml);
   			$htmlPart->type = 'text/html';

   			$htmlMsg = new MimeMessage();
   			$htmlMsg->addPart($htmlPart);

   			$email = new Message();
   			$email->addTo('xvalniria@gmail.com');
   			$email->setSubject('Contato feito pelo site');
   			$email->addFrom('xvalniria@gmail.com');

   			$email->setBody($htmlMsg);

   			$config = array(
   				'host' => 'smtp.gmail.com',
   				'connection_class' => 'login',
   				'connection_config' => array(
   					'ssl' => 'tls',
   					'username' => 'xvalniria@gmail.com',
   					'password' => '770164186387'
   				),
   				'port' => 587
   			);
			$transport = new SmtpTransport();
			$options = new SmtpOptions($config);
			$transport->setOptions($options);

			$transport->send($email);

			$this->flashMessenger()->addMessage('Email enviado com sucesso');
			
			return $this->redirect()->toUrl('/Index');
   		}

   		return new ViewModel();
   	}
}
