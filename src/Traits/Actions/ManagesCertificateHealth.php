<?php

namespace Socrates\Traits\Actions;

use Socrates\Resources\CertificateHealth;

trait ManagesCertificateHealth
{
    public function certificateHealth(int $siteId): \Socrates\Resources\CertificateHealth
    {
        return new CertificateHealth($this->get("certificate-health/{$siteId}"));
    }
}
