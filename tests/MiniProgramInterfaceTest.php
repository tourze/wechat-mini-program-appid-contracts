<?php

namespace Tourze\WechatMiniProgramAppIDContracts\Tests;

use PHPUnit\Framework\TestCase;
use Tourze\WechatMiniProgramAppIDContracts\MiniProgramInterface;

class MiniProgramInterfaceTest extends TestCase
{
    private function createTestMiniProgram(): MiniProgramInterface
    {
        return new class implements MiniProgramInterface {
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
    
    /**
     * 测试getAppId方法返回的值符合预期
     */
    public function testGetAppId_returnsExpectedValue(): void
    {
        $miniProgram = $this->createTestMiniProgram();
        
        $result = $miniProgram->getAppId();
        $this->assertSame('wx123456789abc', $result);
    }
    
    /**
     * 测试getAppSecret方法返回的值符合预期
     */
    public function testGetAppSecret_returnsExpectedValue(): void
    {
        $miniProgram = $this->createTestMiniProgram();
        
        $result = $miniProgram->getAppSecret();
        $this->assertSame('abcdef123456789', $result);
    }
    
    /**
     * 测试getAppId方法返回的值不为空
     */
    public function testGetAppId_notEmpty(): void
    {
        $miniProgram = $this->createTestMiniProgram();
        
        $this->assertNotEmpty($miniProgram->getAppId());
    }
    
    /**
     * 测试getAppSecret方法返回的值不为空
     */
    public function testGetAppSecret_notEmpty(): void
    {
        $miniProgram = $this->createTestMiniProgram();
        
        $this->assertNotEmpty($miniProgram->getAppSecret());
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
        
        $this->assertSame('wx123456789abc', $miniProgram->getAppId());
        $this->assertSame('abcdef123456789', $miniProgram->getAppSecret());
    }
} 