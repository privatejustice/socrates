<?php

namespace Socrates\Traits\Actions;

use Socrates\Resources\CertificateHealth;

trait ManagesCertificateHealth
{
    public function certificateHealth(int $siteId)
    {
        return new CertificateHealth($this->get("certificate-health/{$siteId}"));
    }
}
