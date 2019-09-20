<?php

declare(strict_types=1);

namespace App\Model;

use Nette;

final class ProduktManager
{

	private const
		TABLE_NAME = 'produkty',
		COLUMN_ID = 'id',
		COLUMN_FK_TYP = 'typy_produktu_id',
		COLUMN_FK_VYROBCE = 'vyrobci_id',
		COLUMN_CENA = 'cena',
		COLUMN_POPIS = 'popis',
		COLUMN_KOD = 'kod';

	private $database;


	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}

	public function getList($offset, $order, $limit){
		return $this->database->table(self::TABLE_NAME)->limit($limit, $offset)->order($order)->fetchAll();
	}

	public function getTypFilteredList($offset, $order, $limit, $vyrobce, $typ){
		return $this->database->table(self::TABLE_NAME)->where('vyrobci_id = ? AND typy_produktu_id = ?', $vyrobce, $typ)->limit($limit, $offset)->order($order)->fetchAll();
	}

	public function getVyrFilteredList($offset, $order, $limit, $filter){
		return $this->database->table(self::TABLE_NAME)->where(self::COLUMN_FK_VYROBCE, $filter)->limit($limit, $offset)->order($order)->fetchAll();
	}

	public function getOne($id){
		return $this->database->table(self::TABLE_NAME)->fetch($id);
	}

	public function create($data){
		$this->database->table(self::TABLE_NAME)->insert($data);
	}

	public function update($id, $data){
		$this->database->table(self::TABLE_NAME)->where(self::COLUMN_ID, $id)->update($data);
	}

	public function delete($id){
		$this->database->table(self::TABLE_NAME)->where(self::COLUMN_ID, $id)->delete();
	}

}
