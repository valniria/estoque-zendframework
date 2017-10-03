<?php

namespace Estoque\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ProdutoRepository extends EntityRepository {

	public function getProdutosPaginados($qtdPorPagina, $offset) {
		$entityManager = $this->getEntityManager();
		$queryBuilder = $entityManager->createQueryBuilder();

		$queryBuilder->select('p')
		->from('Estoque\Entity\Produto', 'p')
		->setMaxResults($qtdPorPagina)
		->setFirstResult($offset)
		->orderBy('p.id');

		$query = $queryBuilder->getQuery();

		$paginator = new Paginator($query);

		return $paginator;
	}

}