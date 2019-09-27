<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use Tracy\Debugger;

final class SearchManager
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
    
    public function search($kod){
        $kod = "%".$kod."%";
        Debugger::barDump($kod);
        return $this->database->table('produkty')->where('kod LIKE ?', $kod)->order('kod DESC');
	}
	
	public function getFiltered($offset, $order, $limit, $vyrobci, $typy){
		if($vyrobci == null){
			$vyrobci = "%";
		}
		if($typy == null){
			$typy = "%";
		}
		return $this->database->table(self::TABLE_NAME)->limit($limit, $offset)->where("vyrobci_id", $vyrobci)->where("typy_produktu_id", $typy)->order($order)->fetchAll();
	}

}
