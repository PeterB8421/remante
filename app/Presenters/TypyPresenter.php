<?php

declare(strict_types=1);

namespace App\Presenters;
use App\Model\TypyProduktuManager;
use Nette\Application\UI\Form;


final class TypyPresenter extends BasePresenter
{
    private $typyManager;

    public function __construct(TypyProduktuManager $typManager){
        $this->typyManager = $typManager;
    }

	public function renderDefault(): void
	{
        $this->template->typy = $this->typyManager->getList();
    }
    
    public function renderVytvorit(){

    }

    public function renderUpravit($id){
		$typ = $this->typyManager->getOne($id);
		$this->template->typ = $typ;
		$this['typyForm']->setDefaults($typ->toArray());
    }
    
    public function handleSmazat($id){
        $this->typyManager->delete($id);
    }

    public function createComponentTypyForm(){
        $form = new Form();
        $form->addText("typ","Název typu")->setRequired()->addRule(Form::MAX_LENGTH, "Maximální délka názvu typu je 100 znaků.", 100);
        $form->addSubmit("submit", $this->getParameter("id") ? "Upravit" : "Přidat");

        $form->onSuccess[] = array($this, 'typyFormSucceeded');
        return $form;
    }

    public function typyFormSucceeded(Form $form, $data){
        $id = $this->getParameter("id");
        if($id){
            $this->typyManager->update($id, $data);
        }
        else{
            $this->typyManager->create($data);
        }
        $this->redirect("default");
    }
}
