<?php

namespace Sumbria\Esi;

use Sumbria\Esi\Esi;

class Universe extends Esi {

    public function getAllSolarSystemSystems() {
        return $this->callUrl('universe/systems');
    }

    public function getSolarSystem($solar_system_id) {
        return $this->callUrl('universe/systems/' . $solar_system_id);
    }

    public function getTypes($page = 1) {
        return $this->callUrl('universe/types/?page=' . $page);
    }

    public function getType($type_id) {
        return $this->callUrl('universe/types/' . $type_id);
    }

}
