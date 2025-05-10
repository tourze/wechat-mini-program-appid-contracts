<?php

namespace Tourze\WechatMiniProgramAppIDContracts;


interface MiniProgramLoaderInterface
{
    public function loadUserByAppId(string $openId): ?MiniProgramInterface;
}
