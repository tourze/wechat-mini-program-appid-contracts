<?php

namespace Tourze\WechatMiniProgramAppIDContracts\Tests;

use PHPUnit\Framework\TestCase;
use Tourze\WechatMiniProgramAppIDContracts\MiniProgramInterface;
use Tourze\WechatMiniProgramAppIDContracts\MiniProgramLoaderInterface;

class IntegrationTest extends TestCase
{
    /**
     * 测试MiniProgramLoaderInterface和MiniProgramInterface的结合使用
     */
    public function testIntegration_loadAndUseAppId(): void
    {
        // 创建一个微信小程序实例
        $miniProgram = new class implements MiniProgramInterface {
            public function getAppId(): string
            {
                return 'wx123456789abc';
            }
            
            public function getAppSecret(): string
            {
                return 'abcdef123456789';
            }
        };
        
        // 创建加载器实例
        $loader = new class ($miniProgram) implements MiniProgramLoaderInterface {
            private MiniProgramInterface $miniProgram;
            
            public function __construct(MiniProgramInterface $miniProgram)
            {
                $this->miniProgram = $miniProgram;
            }
            
            public function loadUserByAppId(string $openId): ?MiniProgramInterface
            {
                if ($openId === 'valid_open_id') {
                    return $this->miniProgram;
                }
                return null;
            }
        };
        
        // 加载有效用户
        $loadedUser = $loader->loadUserByAppId('valid_open_id');
        $this->assertNotNull($loadedUser);
        $this->assertInstanceOf(MiniProgramInterface::class, $loadedUser);
        
        // 验证获取到的AppID和AppSecret
        $this->assertSame('wx123456789abc', $loadedUser->getAppId());
        $this->assertSame('abcdef123456789', $loadedUser->getAppSecret());
        
        // 加载无效用户
        $invalidUser = $loader->loadUserByAppId('invalid_open_id');
        $this->assertNull($invalidUser);
    }
    
    /**
     * 测试多个小程序用户情景
     */
    public function testIntegration_multiplePrograms(): void
    {
        // 创建多个微信小程序实例
        $miniProgram1 = new class implements MiniProgramInterface {
            public function getAppId(): string
            {
                return 'wx123456789abc';
            }
            
            public function getAppSecret(): string
            {
                return 'secret1';
            }
        };
        
        $miniProgram2 = new class implements MiniProgramInterface {
            public function getAppId(): string
            {
                return 'wx987654321xyz';
            }
            
            public function getAppSecret(): string
            {
                return 'secret2';
            }
        };
        
        // 创建加载器实例，支持多个小程序
        $loader = new class ([$miniProgram1, $miniProgram2]) implements MiniProgramLoaderInterface {
            private array $miniPrograms = [];
            
            public function __construct(array $miniPrograms)
            {
                // 用openId作为键存储小程序实例
                $this->miniPrograms = [
                    'user1' => $miniPrograms[0],
                    'user2' => $miniPrograms[1],
                ];
            }
            
            public function loadUserByAppId(string $openId): ?MiniProgramInterface
            {
                return $this->miniPrograms[$openId] ?? null;
            }
        };
        
        // 测试加载用户1
        $user1 = $loader->loadUserByAppId('user1');
        $this->assertNotNull($user1);
        $this->assertSame('wx123456789abc', $user1->getAppId());
        $this->assertSame('secret1', $user1->getAppSecret());
        
        // 测试加载用户2
        $user2 = $loader->loadUserByAppId('user2');
        $this->assertNotNull($user2);
        $this->assertSame('wx987654321xyz', $user2->getAppId());
        $this->assertSame('secret2', $user2->getAppSecret());
        
        // 测试加载不存在的用户
        $invalidUser = $loader->loadUserByAppId('user3');
        $this->assertNull($invalidUser);
    }
} 