<?php

declare(strict_types=1);

namespace App\Models\Authentication;

use Jumbojett\OpenIDConnectClient;

class Authenticator {
    public function __construct(readonly private string $providerUrl,
                                readonly private string $clientId,
                                readonly private string $clientSecret,
                                readonly public string $requiredGroup)
    {}

    public function authenticateOIDC(): UserModel
    {
        $oidc = new OpenIDConnectClient($this->providerUrl,
                                        $this->clientId,
                                        $this->clientSecret);

        $oidc->setRedirectURL('http://localhost:8080/admin'); // TODO smazat před použitím

        $oidc->authenticate();

        $personId = (int) $oidc->requestUserInfo('person_id');
        $name = $oidc->requestUserInfo('name');
        $groups = $oidc->requestUserInfo('groups');

        return new UserModel($personId, $name, $groups);
    }
}