<?php
namespace Acme\System;

use Doctrine\DBAL\Query\QueryBuilder;
use Acme\Interfaces\EntidadePadrao;

abstract class Model {

	public static $db;

	/**
	 * Executa uma Query gerada pelo Query Builder
	 * @param  [type]  $query   [description]
	 * @param  boolean $toArray [description]
	 * @return [type]           [description]
	 */
	protected function execute($query, $toArray = True) {
		$return = self::$db->executeQuery($query)->fetchAll();
		if ($toArray) {
			return $return;
		} else {
			return (object) $return;
		}
	}

	/**
	 * Retorna sempre uma nova instância do Query Builder
	 * @return [type] [description]
	 */
	protected function newQuery() {
		return new QueryBuilder(self::$db);
	}

	/**
	 * Deleta um registro no banco
	 * @param [type] $table
	 * @param Array $where
	 * @return void
	 */
	protected function DBDelete($table, Array $where) {
		return self::$db->delete($table, $where);
	}

	/**
	 * Persiste os dados de um objecto que implemente EntidadePadrao
	 * no banco de dados
	 * @param [string]       $table  [Tabela a ser inserida]
	 * @param EntidadePadrao $entity [Objeto a ser persistido]
	 */
	protected function DBPersist($table, EntidadePadrao $entity) {
		$values = $entity->getValues();
		return $this->DBInsert($table, $values);
	}

	/**
	 * Realiza um Insert simples no banco de dados
	 * @param [type] $table  [description]
	 * @param Array  $values [description]
	 */
	protected function DBInsert($table, Array $values) {
		$res = self::$db->insert($table, $values);
		if ($res) {
			return self::$db->lastInsertId();
		} else {
			return false;
		}
	}

	/**
	 * [DBUpdate description]
	 * @param [type] $table  [description]
	 * @param Array  $values [description]
	 * @param Array  $where  [description]
	 */
	protected function DBUpdate ($table, Array $values, Array $where ) {
		return self::$db->update($table, $values, $where);
	}
}
