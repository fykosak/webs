<?php

declare(strict_types=1);

namespace App\Components\Admin;

use Nette\DI\Container;
use Fykosak\Utils\FrontendComponent\Components\AjaxComponent;
use Fykosak\Utils\Logging\Message;
use Fykosak\Utils\Logging\MessageLevel;
use Nette\Http\IResponse;

abstract class AbstractFileManagementComponent extends AjaxComponent
{
    public function __construct(
        Container $container,
        string $frontendId,
    ) {
        parent::__construct($container, $frontendId);
    }

    protected function configure(): void
    {
        $this->addAction('upload', 'upload!');
        $this->addAction('rename', 'rename!');
        $this->addAction('delete', 'delete!');
    }

    public function getRequestValue(string $key): ?string
    {
        $value = $this->getHttpRequest()->getPost($key);
        return is_string($value) ? $value : null;
    }

    public function sendResponse(string $text, int $responseCode = IResponse::S200_OK, MessageLevel $messageLevel = MessageLevel::Success): void
    {
        $this->getLogger()->log(new Message($text, $messageLevel));
        $this->sendAjaxResponse($responseCode);
    }

    abstract protected function getData(): array;

    abstract public function handleUpload(): void;

    abstract public function handleRename(): void;

    abstract public function handleDelete(): void;
}