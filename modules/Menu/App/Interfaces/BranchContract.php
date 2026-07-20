<?php

namespace Modules\Menu\App\Interfaces;

interface BranchContract
{
    public function toTree();

    public function setMaxLevel(int $level): static;
}
