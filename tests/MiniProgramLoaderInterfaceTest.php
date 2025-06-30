<?php

namespace Tourze\WechatMiniProgramAppIDContracts\Tests;

use PHPUnit\Framework\TestCase;
use Tourze\WechatMiniProgramAppIDContracts\MiniProgramInterface;
use Tourze\WechatMiniProgramAppIDContracts\MiniProgramLoaderInterface;

class MiniProgramLoaderInterfaceTest extends TestCase
{
    private function createTestLoader(): MiniProgramLoaderInterface
    {
        return new class implements MiniProgramLoaderInterface {
            private array $users = [];
            
            public function __construct()
            {
                $this->users['valid_open_id'] = new class implements MiniProgramInterface {
                    public function getAppId(): string
                    {
                        return 'wx123456789abc';
                    }
                    
                    public function getAppSecret(): string
                    {
                        return 'abcdef123456789';
                    }
                };
            }
            
            public function loadUserByAppId(string $openId): ?MiniProgramInterface
            {
                return $this->users[$openId] ?? null;
            }
        };
    }
    
    /**
     * 测试使用有效openId加载用户时返回MiniProgramInterface实例
     */
    public function testLoadUserByAppId_withValidOpenId(): void
    {
        $loader = $this->createTestLoader();
        
        $result = $loader->loadUserByAppId('valid_open_id');
        
        $this->assertInstanceOf(MiniProgramInterface::class, $result);
    }
    
    /**
     * 测试使用无效openId加载用户时返回null
     */
    public function testLoadUserByAppId_withInvalidOpenId(): void
    {
        $loader = $this->createTestLoader();
        
        $result = $loader->loadUserByAppId('invalid_open_id');
        
        $this->assertNull($result);
    }
    
    /**
     * 测试使用空openId加载用户的情况
     */
    public function testLoadUserByAppId_withEmptyOpenId(): void
    {
        $loader = $this->createTestLoader();
        
        $result = $loader->loadUserByAppId('');
        
        $this->assertNull($result);
    }
    
    /**
     * 测试返回的类型是否符合预期（MiniProgramInterface或null）
     */
    public function testLoadUserByAppId_returnsCorrectType(): void
    {
        $loader = $this->createTestLoader();
        
        // 有效openId应返回MiniProgramInterface实例
        $result1 = $loader->loadUserByAppId('valid_open_id');
        $this->assertInstanceOf(MiniProgramInterface::class, $result1);
        
        // 无效openId应返回null
        $result2 = $loader->loadUserByAppId('invalid_open_id');
        $this->assertNull($result2);
    }
    
    /**
     * 测试使用具体实现类的方式
     */
    public function testWithConcreteImplementation(): void
    {
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
        
        $loader = new class implements MiniProgramLoaderInterface {
            private array $users = [];
            
            public function __construct()
            {
                $this->users['valid_open_id'] = new class implements MiniProgramInterface {
                    public function getAppId(): string
                    {
                        return 'wx123456789abc';
                    }
                    
                    public function getAppSecret(): string
                    {
                        return 'abcdef123456789';
                    }
                };
            }
            
            public function loadUserByAppId(string $openId): ?MiniProgramInterface
            {
                return $this->users[$openId] ?? null;
            }
        };
        
        // 测试有效openId
        $result1 = $loader->loadUserByAppId('valid_open_id');
        $this->assertInstanceOf(MiniProgramInterface::class, $result1);
        $this->assertSame('wx123456789abc', $result1->getAppId());
        $this->assertSame('abcdef123456789', $result1->getAppSecret());
        
        // 测试无效openId
        $result2 = $loader->loadUserByAppId('invalid_open_id');
        $this->assertNull($result2);
    }
} 