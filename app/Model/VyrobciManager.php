<?php

declare(strict_types=1);

namespace App\Model;

use Nette;

final class VyrobciManager
{

	private const
		TABLE_NAME = 'vyrobci',
		COLUMN_ID = 'id',
		COLUMN_VYROBCE = 'vyrobce';

	private $database;


	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}

	public function getList(){
		return $this->database->table(self::TABLE_NAME)->fetchAll();
	}

	public function getOne($id){
		return $this->database->table(self::TABLE_NAME)->fetch($id);
    }
    
    public function getPairs(){
        return $this->database->fetchPairs('SELECT id, vyrobce FROM vyrobci');
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
