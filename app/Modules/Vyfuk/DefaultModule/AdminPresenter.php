<?php

declare(strict_types=1);

namespace App\Modules\Vyfuk\DefaultModule;

use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;
use App\Models\Authentication\Authenticator;
use App\Models\Authentication\UserModel;
use Nette\Application\ForbiddenRequestException;

class AdminPresenter extends BasePresenter
{
    protected Authenticator $authenticator;

    public function injectService(Authenticator $authenticator): void
    {
        $this->authenticator = $authenticator;
    }

    public function checkRequirements($element): void
    {
        parent::checkRequirements($element);

        if (!$this->getUser()->isLoggedIn()) {
            $user = $this->authenticator->authenticateOIDC();
            $this->getUser()->login($user);
        }

        if (!in_array($this->authenticator->requiredGroup, $this->getLoggedUser()->groups)) {
            throw new ForbiddenRequestException();
        }
    }

    public function getLoggedUser(): ?UserModel
    {
        return $this->getUser()->getIdentity();
    }

    public function actionLogout(): void
	{
		$this->getUser()->logout();
		$this->redirect(':Default:Admin:page');
	}

    /**
     * @return NavItem[]
     */
    protected function getNavItems(): array
    {
        $items = [];

        $items[] = new NavItem(
            new PageTitle('Správa novinek', 'fa-solid fa-newspaper'),
            ':Default:Admin:news'
        );

        $items[] = new NavItem(
            new PageTitle('Správa souborů', 'fa-solid fa-file-pen'),
            ':Default:Admin:files'
        );

        $items[] = new NavItem(
            new PageTitle('Správa fotek', 'fa-solid fa-images'),
            ':Default:Admin:media'
        );

        $items[] = new NavItem(
            new PageTitle(sprintf('%s (#%d)', $this->getLoggedUser()->name, $this->getLoggedUser()->id), 'fa-solid fa-user-gear'),
            ':Default:Admin:default'
        );

        $items[] = new NavItem(
            new PageTitle('Odhlásit se', 'fa-solid fa-arrow-right-from-bracket'),
            ':Default:Admin:logout'
        );

        return $items;
    }

}