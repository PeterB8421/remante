<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model\ProduktManager;
use App\Model\TypyProduktuManager;
use App\Model\VyrobciManager;
use Nette\Application\UI\Form;
use Nette\Application\Responses\CsvResponse;
use Tracy\Debugger;


final class ProduktPresenter extends BasePresenter
{
	private $produktManager;
	private $typyProduktuManager;
	private $vyrobciManager;

	public function __construct(ProduktManager $produktManager, TypyProduktuManager $typManager, VyrobciManager $vyrManager){
		$this->produktManager = $produktManager;
		$this->typyProduktuManager = $typManager;
		$this->vyrobciManager = $vyrManager;
	}

	public function actionExport($offset, $limit, $order, $vyr_id, $typ_id, $filename = "produkty.csv", $delimiter = ";"){
		$limit = \intval($limit);
		$offset = \intval($offset);
		if($vyr_id){
			if($typ_id){
				$vals = $this->produktManager->getTypFilteredList($offset, $order, $limit, $vyr_id, $typ_id);
			}
			else{
				$vals = $this->produktManager->getVyrFilteredList($offset, $order, $limit, $vyr_id);
			}
		}
		else{
			$vals = $this->produktManager->getList($offset, $order, $limit);
		}
		$f = fopen('php://memory', 'w'); 
		foreach ($vals as $line) { 
			fputcsv($f, $line->toArray(), $delimiter); 
		}
		fseek($f, 0);
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename="'.$filename.'";');
		fpassthru($f);
	}

	public function renderDefault($order = 'id', $offset = 0, $limit = 10): void
	{
		$this->template->produkty = $this->produktManager->getList($offset, $order, $limit);
		$this->template->offset = $offset;
		$this->template->limit = $limit;
		$this->template->order = $order;
		$this->template->nextOffset = $limit + $offset;
		$this->template->prevOffset = $offset - $limit;
		$this->template->produktCount = count($this->template->produkty);
		$this->template->vyrobci = $this->vyrobciManager->getList();
		$this->template->typy = $this->typyProduktuManager->getList();
	}

	public function renderVyrobce($vyr_id, $typ_id, $order = 'id', $offset = 0, $limit = 10){
		if($typ_id){
			$this->template->produkty = $this->produktManager->getTypFilteredList($offset, $order, $limit, $vyr_id, $typ_id);
		}
		else{
			$this->template->produkty = $this->produktManager->getVyrFilteredList($offset, $order, $limit, $vyr_id);
		}
		$this->template->nextOffset = $limit + $offset;
		$this->template->prevOffset = $offset - $limit;
		$this->template->produktCount = count($this->template->produkty);
		$this->template->vyrobci = $this->vyrobciManager->getList();
		$this->template->typy = $this->typyProduktuManager->getList();
		$this->template->selectedID = $vyr_id;
		$this->template->selectedTyp = $typ_id;
	}

	public function renderVytvorit(){

	}

	public function renderVypis($id){
		$this->template->produkt = $this->produktManager->getOne($id);
	}

	public function renderUpravit($id){
		$produkt = $this->produktManager->getOne($id);
		$this->template->produkt = $produkt;
		$this['produktForm']->setDefaults($produkt->toArray());
	}

	public function handleSmazat($id){
		$this->produktManager->delete($id);
	}

	public function createComponentProduktForm(){
		$form  = new Form();
		$typy = $this->typyProduktuManager->getPairs();
		$form->addSelect("typy_produktu_id", "Typ", $typy)->setRequired();
		$vyrobci = $this->vyrobciManager->getPairs();
		$form->addSelect("vyrobci_id", "Výrobce", $vyrobci)->setRequired();
		$form->addInteger("cena", "Cena")->setRequired();
		$form->addTextArea("popis", "Popis");
		$form->addText("kod", "Kód")->setRequired();
		$form->addSubmit("submit", $this->getParameter("id")? "Upravit" : "Vytvořit");
		$form->onSuccess[] = array($this, 'produktFormSucceeded');

		return $form;
	}

	public function produktFormSucceeded(Form $form, $values){
		$id = $this->getParameter("id");
        if ($id) {
            $this->produktManager->update($id, $values);
            $this->flashMessage("Produkt byl úspěšně upraven");            
        } else {            
            $this->produktManager->create($values);
            $this->flashMessage("Produkt byl úspěšně vložen");                        
        }
        $this->redirect("default");
	}
}
