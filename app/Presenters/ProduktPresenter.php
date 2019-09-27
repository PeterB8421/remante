<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model\ProduktManager;
use App\Model\TypyProduktuManager;
use App\Model\VyrobciManager;
use Nette\Application\UI\Form;
use Nette\Application\Responses\FileResponse;
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

	public function actionExport ($offset, $limit, $order, $vyr_id, $typ_id, $filename = "produkty.csv", $delimiter = ";"){
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
		$result = "id, typ, vyrobce, cena, kod \r \n";
		foreach ($vals as $line) { 
			$result = $result . $line->id .",". $line->ref('typy_produktu' ,'typy_produktu_id')->typ .",". $line->ref('vyrobci', 'vyrobci_id')->vyrobce .",". $line->cena .",". $line->kod ."\r \n";
		}
		$resultFile = fopen("produkty.csv", "w");
		fputs($resultFile, $result);
		$this->sendResponse(new FileResponse("produkty.csv"));
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

	public function handleFilter($vyrobci, $typy, $offset = 0, $order = "id", $limit = 10){
		Debugger::barDump($vyrobci);
		Debugger::barDump($typy);
		$results = $this->produktManager->getFiltered($offset, $order, $limit, $vyrobci, $typy);
		$this->payload->produkty = $results;
		$this->redrawControl("produkty");
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

	public function createComponentSearchForm(){
		$form = new Form();
		$form->addText("search", "Vyhledávání kódů")->setRequired();
		$form->addSubmit("submit", "Hledat");
		$form->onSuccess[] = array($this, 'searchFormSucceeded');

		return $form;
	}

	public function searchFormSucceeded(Form $form, $values){
		$this->redirect('Search:default', $form->getValues()->search);
	}

	public function createComponentFiltrForm(){
		$form = new Form();
		$vyrobci = $this->vyrobciManager->getPairs();
		$form->addMultiSelect("vyrobci", "Výrobci", $vyrobci);
		$typy = $this->typyProduktuManager->getPairs();
		$form->addMultiSelect("typy", "Typy produktů", $typy);
		$form->addSubmit("filter", "Filtrovat")->setAttribute("class", "ajax")->setAttribute("n:href", "filter!");
		return $form;
	}
}
