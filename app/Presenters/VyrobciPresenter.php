<?php

declare(strict_types=1);

namespace App\Presenters;
use App\Model\VyrobciManager;
use Nette\Application\UI\Form;


final class VyrobciPresenter extends BasePresenter
{
    private $vyrManager;
    public function __construct(VyrobciManager $vyrMan){
        $this->vyrManager = $vyrMan;
    }

	public function renderDefault(): void
	{
		$this->template->vyrobci = $this->vyrManager->getList();
    }
    
    public function renderPridat(){

    }

    public function renderUpravit($id){
        $vyrobce = $this->vyrManager->getOne($id);
		$this->template->vyrobce = $vyrobce;
		$this['vyrobciForm']->setDefaults($vyrobce->toArray());
    }

    public function handleSmazat($id){
        $this->vyrManager->delete($id);
    }

    public function createComponentVyrobciForm(){
        $form = new Form();
        $form->addText("vyrobce", "Jméno výrobce")->setRequired()->addRule(Form::MAX_LENGTH, "Maximální délka názvu výrobce je 45 znaků.", 45);
        $form->addSubmit("submit", $this->getParameter("id") ? "Upravit" : "Přidat");
        $form->onSuccess[] = array($this, 'vyrobciFormSucceeded');

        return $form;
    }

    public function vyrobciFormSucceeded(Form $form, $data){
        $id = $this->getParameter("id");
        if($id){
            $this->vyrManager->update($id, $data);
        }
        else{
            $this->vyrManager->create($data);
        }
        $this->redirect("default");
    }
}
