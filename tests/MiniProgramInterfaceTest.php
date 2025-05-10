<?php

namespace Tourze\WechatMiniProgramAppIDContracts\Tests;

use PHPUnit\Framework\TestCase;
use Tourze\WechatMiniProgramAppIDContracts\MiniProgramInterface;

class MiniProgramInterfaceTest extends TestCase
{
    /**
     * 测试getAppId方法返回字符串类型
     */
    public function testGetAppId_returnsString(): void
    {
        $miniProgram = $this->createMock(MiniProgramInterface::class);
        $miniProgram->method('getAppId')->willReturn('wx123456789abc');
        
        $this->assertIsString($miniProgram->getAppId());
    }
    
    /**
     * 测试getAppSecret方法返回字符串类型
     */
    public function testGetAppSecret_returnsString(): void
    {
        $miniProgram = $this->createMock(MiniProgramInterface::class);
        $miniProgram->method('getAppSecret')->willReturn('abcdef123456789');
        
        $this->assertIsString($miniProgram->getAppSecret());
    }
    
    /**
     * 测试getAppId方法返回的值不为空
     */
    public function testGetAppId_notEmpty(): void
    {
        $miniProgram = $this->createMock(MiniProgramInterface::class);
        $miniProgram->method('getAppId')->willReturn('wx123456789abc');
        
        $this->assertNotEmpty($miniProgram->getAppId());
    }
    
    /**
     * 测试getAppSecret方法返回的值不为空
     */
    public function testGetAppSecret_notEmpty(): void
    {
        $miniProgram = $this->createMock(MiniProgramInterface::class);
        $miniProgram->method('getAppSecret')->willReturn('abcdef123456789');
        
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