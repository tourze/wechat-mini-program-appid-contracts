<?php

namespace Tourze\WechatMiniProgramAppIDContracts;

interface MiniProgramInterface
{
    public function getAppId(): string;

    public function getAppSecret(): string;
}
